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
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum'); // Require authentication
    // }


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

    public function getImagesPublic()
    {
        try {
            // Fetch all images from the database
            $images = Image::all(['id', 'file_path']);

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
                    'url' => Storage::url($image->file_path) // Correct local storage path
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
            $images = Image::all(['id', 'file_path']);
    
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
                    'id' => $image->id,
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