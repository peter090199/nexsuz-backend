<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Module; 

class BlogImageController extends Controller
{
    public function saveOrUpdateImages(Request $request)
    {
        $validated = $request->all();

        // Decode JSON string to an array if needed
        if ($request->has('stats') && is_string($request->stats)) {
            $validated['stats'] = json_decode($request->stats, true);
        }

        // Validate input fields
        $validator = Validator::make($validated, [
            'files' => 'nullable|array',
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'transNo' => 'required|string',
            'transCode' => 'nullable|integer|exists:images,transCode',
            'title' => 'required|string',
            'description' => 'required|string',
            'stats' => 'required|array',
            'stats.*.value' => 'required|string',
            'stats.*.label' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $transNo = $validated['transNo'];
            $title = $validated['title'];
            $description = $validated['description'];
            $stats = $validated['stats'];
            $transCode = $validated['transCode'] ?? null;

            if ($transCode) {
                // Update existing record
                DB::table('images')->where('transCode', $transCode)->update([
                    'title' => $title,
                    'description' => $description,
                    'updated_at' => now(),
                ]);
                DB::table('stats')->where('transCode', $transCode)->delete();
            } else {
                // Delete the last transCode before creating a new one
                $lastTransCode = DB::table('images')->max('transCode');
                if ($lastTransCode) {
                    DB::table('images')->where('transCode', $lastTransCode)->delete();
                    DB::table('stats')->where('transCode', $lastTransCode)->delete();
                }

                // Create new transCode
                $transCode = $lastTransCode ? $lastTransCode + 1 : 1;
            }

            foreach ($stats as $stat) {
                DB::table('stats')->insert([
                    'user_code' => $user->code,
                    'trans_no' => $transNo,
                    'transCode' => $transCode,
                    'value' => $stat['value'],
                    'label' => $stat['label'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $uploadedFiles = [];
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $uuid = Str::uuid();
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $folderPath = "uploads/{$user->code}/TransNo/{$transNo}/{$uuid}";
                    $photoPath = $file->storeAs($folderPath, $fileName, 'public');

                    $image = Image::create([
                        'user_code' => $user->code,
                        'file_path' => $photoPath,
                        'trans_no' => $transNo,
                        'transCode' => $transCode,
                        'title' => $title,
                        'description' => $description,
                    ]);

                    $uploadedFiles[] = [
                        'user_code' => $image->user_code,
                        'trans_no' => $image->trans_no,
                        'file_path' => asset(Storage::url($photoPath))
                    ];
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $request->has('transCode') ? 'Updated successfully!' : 'Saved successfully!',
                'files' => $uploadedFiles,
            ], 201);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
    

    public function uploadImages(Request $request)
    {
        $data = $request->all();
    
        // Decode JSON string to array
        if ($request->has('stats')) {
            $data['stats'] = json_decode($request->input('stats'), true);
        }
    
        // Validate input fields
        $validator = Validator::make($data, [
            'files' => 'required|array',
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'transNo' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'stats' => 'required|array', // Ensure 'stats' is an array
            'stats.*.value' => 'required|string',
            'stats.*.label' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
            ]);
        }
    
        // Check if files are uploaded
        if (!$request->hasFile('files')) {
            return response()->json([
                'success' => false,
                'message' => 'No file was uploaded.',
            ]);
        }
    
        try {
            DB::beginTransaction(); // Start transaction
    
            $user = Auth::user();
            $userCode = $user->code ?? 'default_user'; 
            $transNo = $request->input('transNo');
            $title = $request->input('title');
            $description = $request->input('description');
            $stats = $data['stats']; // Now stats is properly formatted as an array
            $uploadedFiles = [];
    
            foreach ($request->file('files') as $file) {
                $uuid = Str::uuid(); 
                $fileName = time() . '_' . $file->getClientOriginalName();
                $folderPath = "uploads/{$userCode}/TransNo/{$transNo}/{$uuid}";
    
                // Store the file
                $photoPath = $file->storeAs($folderPath, $fileName, 'public');
                $photoUrl = asset(Storage::url($photoPath));
    

                $lastTransCode = DB::table('images')->max('transCode'); 
                $controlNumbers = empty($lastTransCode) ? 1 : $lastTransCode + 1;
                // Save file details in DB
                $image = Image::create([
                    'user_code' => $user->code,
                    'file_path' => $photoPath,
                    'trans_no'  => $transNo,
                    'transCode' => $controlNumbers,
                    'title' => $title,
                    'description' => $description,
                ]);
    
                $uploadedFiles[] = [
                    'user_code' => $image->user_code,
                    'trans_no'  => $image->trans_no,
                    'file_path' => $photoUrl
                ];
            }
    
            // Save Stats
            foreach ($stats as $stat) {
                DB::table('stats')->insert([
                    'user_code' => $user->code,
                    'trans_no' => $transNo,
                    'transCode' => $controlNumbers,
                    'value' => $stat['value'],
                    'label' => $stat['label'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
    
            DB::commit(); // Commit transaction
    
            return response()->json([
                'success' => true,
                'message' => 'Images and stats uploaded successfully!',
                'files' => $uploadedFiles
            ], 201);
    
        } catch (\Throwable $th) {
            DB::rollBack(); // Rollback on error
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $th->getMessage(),
            ]);
        }
    }
    

    public function updateImages(Request $request)
    {
        $data = $request->all();

        // Decode JSON string to array
        if ($request->has('stats')) {
            $data['stats'] = json_decode($request->input('stats'), true);
        }

        // Validate input fields
        $validator = Validator::make($data, [
            'transCode' => 'required|integer|exists:images,transCode', // Ensure transCode exists in DB
            'title' => 'required|string',
            'description' => 'required|string',
            'stats' => 'required|array',
            'stats.*.value' => 'required|string',
            'stats.*.label' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        try {
            DB::beginTransaction(); // Start transaction

            $user = Auth::user();
            $transCode = $request->input('transCode');
            $title = $request->input('title');
            $description = $request->input('description');
            $stats = $data['stats'];

            // Update image details where transCode matches
            $updated = DB::table('images')
                ->where('transCode', $transCode)
                ->update([
                    'title' => $title,
                    'description' => $description,
                    'updated_at' => now(),
                ]);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching record found for transCode ' . $transCode,
                ]);
            }

            // Update stats (delete old stats and insert new ones)
            DB::table('stats')->where('transCode', $transCode)->delete();
            foreach ($stats as $stat) {
                DB::table('stats')->insert([
                    'user_code' => $user->code,
                    'trans_no' => DB::table('images')->where('transCode', $transCode)->value('trans_no'),
                    'transCode' => $transCode,
                    'value' => $stat['value'],
                    'label' => $stat['label'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit(); // Commit transaction

            return response()->json([
                'success' => true,
                'message' => 'Image details and stats updated successfully!',
            ]);

        } catch (\Throwable $th) {
            DB::rollBack(); // Rollback on error
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $th->getMessage(),
            ]);
        }
    }



    public function uploadImageskk(Request $request)
    {
        $data = $request->all();

        // Validate file input
        $validator = Validator::make($data, [
            'files' => 'required|array',
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'transNo' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'stats' => 'nullable|array',
            'stats.*.value' => 'required|string',
            'stats.*.label' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        if (!$request->hasFile('files')) {
            return response()->json([
                'success' => false,
                'message' => 'No file was uploaded.',
            ]);
        }

        try {
            DB::beginTransaction(); // Start the transaction

            $user = Auth::user();
            $userCode = $user->code ?? 'default_user'; // Fallback for user code
            $transNo = $request->input('transNo');
            $title = $request->input('title');
            $description = $request->input('description');
            $stats = $request->input('stats', []); // Get stats or default empty array
            $uploadedFiles = [];

            foreach ($request->file('files') as $file) {
                $uuid = Str::uuid(); // Generate unique identifier
                $fileName = time() . '_' . $file->getClientOriginalName();
                $folderPath = "uploads/{$userCode}/TransNo/{$transNo}/{$uuid}";

                // Store the file in 'storage/app/public/uploads/{userCode}/TransNo/{transNo}/{uuid}'
                $photoPath = $file->storeAs($folderPath, $fileName, 'public');

                // Construct the full file URL
                $photoUrl = asset(Storage::url($photoPath));

                // Save file path and transNo in the database
                $image = Image::create([
                    'user_code' => $user->code,
                    'file_path' => $photoPath, // Store relative path
                    'trans_no'  => $transNo,
                    'title'=> $title,
                    'description' => $description,
                    'stats' => json_encode($stats), // Store stats as JSON
                ]);

                // Append the image data with full accessible URL
                $uploadedFiles[] = [
                    'user_code' => $image->user_code,
                    'trans_no'  => $image->trans_no,
                    'file_path' => $photoUrl // Generate correct public URL
                ];
            }

            DB::commit(); // Commit the transaction

            return response()->json([
                'success' => true,
                'message' => 'Images uploaded successfully!',
                'files' => $uploadedFiles
            ], 201);

        } catch (\Throwable $th) {
            DB::rollBack(); // Rollback transaction on error

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $th->getMessage(),
            ]);
        }
    }

    public function getImagesPublic()
    {
        try {
            // Fetch all images from the database
            $images = Image::all(['id', 'file_path','title','description']);

            if ($images->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No images found.',
                ], 201);
            }

            // Convert file paths to full URLs
            $imageData = $images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'title' => $image->title,
                    'description' => $image->description,
                    'url' => asset("https://lightcyan-ram-803545.hostingersite.com/storage/app/public/{$image->file_path}")
                ];
            });
            

            return response()->json([
                'success' => true,
                'message' => 'All images retrieved successfully!',
                'images' => $imageData
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function getImagesPubliccc()
    {
        try {
            $images = Image::all(['id', 'file_path']);

            if ($images->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No images found.',
                ], 200);
            }

            $imageData = $images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'url' => asset("storage/{$image->file_path}")
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'All images retrieved successfully!',
                'images' => $imageData
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $th->getMessage(),
            ], 500);
        }
    }


    public function getImages()
    {
        try {
            // Fetch all images from the database
            $images = Image::all(['id','transCode','title','description','file_path']);
    
            // If no images are found, return a message
            if ($images->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No images found.',
                ], 201);
            }
         
            // Convert file paths to full URLs and include the ID
            $imageData = $images->map(function ($image) {
                return [
                    'id' => $image->id, // Correctly reference image properties
                    'transCode' => $image->transCode,
                    'title' => $image->title,
                    'description' => $image->description,
                    'url' => asset("https://exploredition.com/storage/app/public/{$image->file_path}")
                ];
            });

            
            return response()->json([
                'success' => true,
                'message' => 'All images retrieved successfully!',
                'images' => $imageData
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $th->getMessage(),
            ], 500);
        }
    }
    public function getImagesWithStats()
    {
        try {
            // Fetch all images with transCode
            $images = Image::all(['id', 'transCode', 'title', 'description', 'file_path']);
    
            if ($images->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No images found.',
                ], 201);
            }
    
            // Map images with corresponding stats
            $imageData = $images->map(function ($image) {
                // Get stats linked to the transCode of the image
                $stats = DB::table('stats')
                    ->where('transCode', $image->transCode)
                    ->select('id', 'trans_no', 'transCode', 'value', 'label', 'created_at')
                    ->get();
    
                return [
                    'id' => $image->id,
                    'transCode' => $image->transCode,
                    'title' => $image->title,
                    'description' => $image->description,
                    'url' => asset("https://exploredition.com/storage/app/public/{$image->file_path}"),
                    'stats' => $stats, // Attach the stats
                ];
            });
    
            return response()->json([
                'success' => true,
                'message' => 'All images with stats retrieved successfully!',
                'data' => $imageData
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $th->getMessage(),
            ], 500);
        }
    }
    


    public function getImagesByTransNo(Request $request)
    {
        // Validate the request
        $request->validate([
            'transNo' => 'required|string' // Ensure transNo is provided
        ]);

        $transNo = $request->input('transNo'); // Get transaction number
        $user = Auth::user(); // Get authenticated user

        // Retrieve images associated with the transaction number and user
        $images = Image::where('user_code', $user->code)
                    ->where('trans_no', $transNo)
                    ->get();

        // Format the image paths correctly
        $imageData = $images->map(function ($image) {
            return [
                'id' => $image->id,
                'trans_no' => $image->trans_no,
                'file_path' => url("storage/app/public/{$image->file_path}"), // Generate correct URL
            ];
        });

        return response()->json([
            'message' => 'Images retrieved successfully!',
            'images' => $imageData
        ], 200);
    }

    public function deleteByTransCode($transCode)
    {
        try {
            DB::beginTransaction(); // Start transaction

            // Check if the transaction exists
            $imageExists = DB::table('images')->where('transCode', $transCode)->exists();
            if (!$imageExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found.'
                ], 404);
            }

            // ✅ Delete stats first (foreign key dependency)
            DB::table('stats')->where('transCode', $transCode)->delete();

            // ✅ Delete images
            DB::table('images')->where('transCode', $transCode)->delete();

            DB::commit(); // Commit transaction

            return response()->json([
                'success' => true,
                'message' => "Records with transCode {$transCode} deleted successfully."
            ], 200);

        } catch (\Throwable $th) {
            DB::rollBack(); // Rollback on error
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $th->getMessage(),
            ]);
        }
    }

    public function delete_blogImage($id)
    {

        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized: User not authenticated'], 200);
            }

            $data = Image::where('id', $id)->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'error' => 'id not found'
                ], 404);
            }

            $data->delete();

            return response()->json([
                'success' => true,
                'message' => "Image '{$id}' deleted successfully!"
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong!',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    

}