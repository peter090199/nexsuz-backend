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

class BlogImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Require authentication
    }


    public function uploadImages(Request $request)
    {
        $data = $request->all();

        // Validate file input
        $validator = Validator::make($data, [
            'files' => 'required|array',
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'transNo' => 'required|string'
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
                    'trans_no'  => $transNo
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


    public function uploadImagescc(Request $request)
    {
        // Validate the request
        $request->validate([
            'files' => 'required|array', // Ensure files is an array
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each file
            'transNo' => 'required|string' // Ensure transNo is provided
        ]);

        $uploadedFiles = [];

        if ($request->hasFile('files')) {
            $user = Auth::user(); // Get authenticated user
            $userCode = $user->code ?? 'default_user'; // Get user code (fallback if null)
            $transNo = $request->input('transNo'); // Get transaction number

            foreach ($request->file('files') as $file) {
               // $uuid = Str::uuid(); // Generate unique identifier
                $originalFileName = $file->getClientOriginalName();
                $storagePath = "uploads/{$userCode}/TransNo/{$transNo}/{$originalFileName}";

                // Store the file in 'storage/app/public/uploads/{userCode}/Images/{uuid}'
                $path = $file->store($storagePath, 'public');
             //   https://red-anteater-382469.hostingersite.com/storage/app/public/uploads/DEFAULTPROFILE/DEFAULTPROFILE.png'  ,
                // Save file path and transNo in the database
                $image = Image::create([
                    'user_code' => $user->code, // Associate image with authenticated user
                    'file_path' => $path, // Store the correct path
                    'trans_no'  => $transNo // Store transaction number
                ]);

                // Append the image data with full accessible URL
                $uploadedFiles[] = [
                    'user_code' => $image->user_code,
                    'trans_no'  => $image->trans_no,
                    'file_path' => asset("storage/{$path}") // Generate public URL
                ];
            }
        }

        return response()->json([
            'message' => 'Images uploaded successfully!',
            'files' => $uploadedFiles
        ], 201);
    }


    public function uploadImagessss(Request $request)
    {
        // Validate the request
        $request->validate([
            'files' => 'required|array', // Ensure files is an array
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each file
            'transNo' => 'required|string' // Ensure transNo is provided
        ]);
    
        $uploadedFiles = [];
    
        if ($request->hasFile('files')) {
            $user = Auth::user(); // Get authenticated user
            $userCode = $user->code ?? 'default_user'; // Get user code (fallback if null)
            $transNo = $request->input('transNo'); // Get transaction number
    
            foreach ($request->file('files') as $file) {
                $fileName = $file->hashName(); // Generate unique filename
                $storagePath = "https://exploredition.com/uploads/{$userCode}/TransNo/{$transNo}/default.png/{$fileName}";
                
                // Store the file in 'storage/app/public/uploads/{userCode}/TransNo/{transNo}/default.png/{file_name}'
                $path = $file->storeAs("public/{$storagePath}", $fileName);
    
                // Save file path and transNo in the database
                $image = Image::create([
                    'user_code' => $user->code, // Associate image with authenticated user
                    'file_path' => $storagePath, // Store the correct path
                    'trans_no'  => $transNo // Store transaction number
                ]);
    
                // Append the image data with full accessible URL
                $uploadedFiles[] = [
                    'user_code' => $image->user_code,
                    'trans_no'  => $image->trans_no,
                    'file_path' => url("storage/app/public/{$storagePath}") // Generate correct public URL
                ];
            }
        }
    
        return response()->json([
            'message' => 'Images uploaded successfully!',
            'files' => $uploadedFiles
        ], 201);
    }

    public function uploadImagesx(Request $request)
    {
        // Validate the request
        $request->validate([
            'files' => 'required|array', // Ensure files is an array
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each file
            'transNo' => 'required|string' // Ensure transNo is provided
        ]);

        $uploadedFiles = [];

        if ($request->hasFile('files')) {
            $user = Auth::user(); // Get authenticated user
            $userCode = $user->code ?? 'default_user'; // Get user code (fallback if null)
            $transNo = $request->input('transNo'); // Get transaction number

            foreach ($request->file('files') as $file) {
                $fileName = $file->hashName(); // Generate unique filename
                $filePath = "uploads/{$userCode}/TransNo/{$transNo}/{$fileName}";

                // Store the file in 'storage/app/public/uploads/{userCode}/TransNo/{transNo}/{file_name}'
                $file->storeAs("public/{$filePath}", $fileName);

                // Save file path and transNo in the database
                $image = Image::create([
                    'user_code' => $user->code, // Associate image with authenticated user
                    'file_path' => $filePath, // Store the correct path
                    'trans_no'  => $transNo // Store transaction number
                ]);

                // Append the image data with full accessible URL
                $uploadedFiles[] = [
                    'user_code' => $image->user_code,
                    'trans_no'  => $image->trans_no,
                    'file_path' => $image->$filePath // Generate correct public URL
                ];
            }
        }

        return response()->json([
            'message' => 'Images uploaded successfully!',
            'files' => $uploadedFiles
        ], 201);
    }



    public function getImagesss()
    {
        $user = Auth::user(); // Get authenticated user
    
        // Retrieve all images associated with the user
        $images = Image::where('user_code', $user->code)->get();
    
        // Format the image paths correctly
        $imageData = $images->map(function ($image) {
            return [
                'id' => $image->id,
                'trans_no' => $image->trans_no,
                'file_path' => url("{$image->file_path}"), // Generate correct URL
            ];
        });
    
        return response()->json([
            'message' => 'All images retrieved successfully!',
            'images' => $imageData
        ], 200);
    }

    public function getImages()
    {
        try {
            // Fetch all images from the database
            $images = Image::all(['file_path']);

            // If no images are found, return a message
            if ($images->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No images found.',
                ], 404);
            }

            // Convert file paths to full URLs
            $imageUrls = $images->map(function ($image) {
                return asset("https://exploredition.com/storage/app/public/{$image->file_path}");
            });

            return response()->json([
                'success' => true,
                'message' => 'All images retrieved successfully!',
                'images' => $imageUrls
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $th->getMessage(),
            ], 500);
        }
    }




//     public function getImages()
// {
//     $user = Auth::user();

//     // Retrieve all images for the authenticated user
//     $images = Image::where('user_code', $user->code)->get();

//     // Format URLs correctly
//     $imageData = $images->map(function ($image) {
//         return [
//             'id' => $image->id,
//             'trans_no' => $image->trans_no,
//             'file_path' => asset("{$image->file_path}") // ✅ Correct Laravel storage path
//         ];
//     });

//     return response()->json([
//         'message' => 'All images retrieved successfully!',
//         'images' => $imageData
//     ], 200);
// }

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



    public function getImagesxx()
    {
        $user = Auth::user();
    
        // Retrieve images from database
        $images = Image::where('user_code', $user->code)->get();
    
        // Generate the full URL for each image
        return response()->json([
            'message' => 'User images retrieved successfully!',
            'images' => $images->map(function ($image) {
                return [
                    'user_code' => $image->user_code,
                    'trans_no' => $image->trans_no,
                    'file_path' =>  $image->file_path,
                ];
            }),
        ], 200);
    }
    



//dynamic

// public function uploadImages(Request $request)
// {
//     // Validate the request
//     $request->validate([
//         'files' => 'required|array', // Ensure files is an array
//         'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each file
//         'transNo' => 'required|string' // Ensure transNo is provided
//     ]);

//     $uploadedFiles = [];

//     if ($request->hasFile('files')) {
//         $user = Auth::user(); // Get authenticated user
//         $userCode = $user->code ?? 'default_user'; // Get user code (fallback if null)
//         $transNo = $request->input('transNo'); // Get transaction number

//         foreach ($request->file('files') as $file) {
//             $originalFileName = $file->getClientOriginalName();
//             $storagePath = "uploads/{$userCode}/TransNo/{$transNo}";

//             // Store file in `storage/app/public/uploads/{userCode}/TransNo/{transNo}/`
//             $path = $file->storeAs($storagePath, $originalFileName, 'public');

//             // Save file path in database
//             $image = Image::create([
//                 'user_code' => $user->code,
//                 'file_path' => $path,
//                 'trans_no'  => $transNo
//             ]);

//             // Generate public URL
//             $uploadedFiles[] = [
//                 'user_code' => $image->user_code,
//                 'trans_no'  => $image->trans_no,
//                 'file_path' => asset("storage/" . $path) // Corrected URL format
//             ];
//         }
//     }

//     return response()->json([
//         'message' => 'Images uploaded successfully!',
//         'files' => $uploadedFiles
//     ], 201);
// }

// public function uploadImages(Request $request)
// {
//     // Validate the request
//     $request->validate([
//         'files' => 'required|array', // Ensure files is an array
//         'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each file
//         'transNo' => 'required|string' // Ensure transNo is provided
//     ]);

//     $uploadedFiles = [];

//     if ($request->hasFile('files')) {
//         $user = Auth::user(); // Get authenticated user
//         $userCode = $user->code ?? 'default_user'; // Get user code (fallback if null)
//         $transNo = $request->input('transNo'); // Get transaction number

//         foreach ($request->file('files') as $file) {
//             $uuid = Str::uuid()->toString(); // Generate a unique UUID
//             $extension = $file->getClientOriginalExtension(); // Get file extension
//             $newFileName = $uuid . '.' . $extension; // Rename file with UUID

//             $storagePath = "uploads/{$userCode}/TransNo/{$transNo}";

//             // Store file in `storage/app/public/uploads/{userCode}/TransNo/{transNo}/`
//             $path = $file->storeAs($storagePath, $newFileName, 'public');

//             // Save file path and UUID in database
//             $image = Image::create([
//                 'user_code' => $user->code,
//                 'file_path' => $path,
//                 'trans_no'  => $transNo,
//                 'uuid'      => $uuid // Save UUID in the database
//             ]);

//             // Generate public URL
//             $uploadedFiles[] = [
//                 'user_code' => $image->user_code,
//                 'trans_no'  => $image->trans_no,
//                 'uuid'      => $image->uuid, // Return UUID in response
//                 'file_path' => asset("storage/" . $path) // Corrected URL format
//             ];
//         }
//     }

//     return response()->json([
//         'message' => 'Images uploaded successfully!',
//         'files' => $uploadedFiles
//     ], 201);
// }
public function getImagesxxxc()
{
    $user = Auth::user();

    // Retrieve images from database
    $images = Image::where('user_code', $user->code)->get();

    // Generate the full URL for each image
    return response()->json([
        'message' => 'User images retrieved successfully!',
        'images' => $images->map(function ($image) {
            return [
                'user_code' => $image->user_code,
                'trans_no' => $image->trans_no,
                'file_path' => url("storage/" . $image->file_path) // Ensure full URL
            ];
        }),
    ], 200);
}

}
