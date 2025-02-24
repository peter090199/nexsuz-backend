<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Require authentication
    }


    public function uploadImages(Request $request)
    {
        // Validate the request
        $request->validate([
            'files' => 'required|array', // Ensure files is an array
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each file
        ]);

        $uploadedFiles = [];

        if ($request->hasFile('files')) {
            $user = Auth::user(); // Get authenticated user
            $userCode = $user->code ?? 'default_user'; // Get user code (fallback if null)
            
            foreach ($request->file('files') as $file) {
                $uuid = Str::uuid(); // Generate unique identifier
                
                // Define the storage path for each image
                $storagePath = "uploads/{$userCode}/Images/{$uuid}";

                // Store the file in 'storage/app/public/uploads/{userCode}/cvphoto/{uuid}'
                $path = $file->store($storagePath, 'public');

                // Save file path in the database
                $image = Image::create([
                    'user_code' => $user->code, // Associate image with authenticated user
                    'file_path' => $path, // Store the correct path
                ]);

                // Append the image data with full accessible URL
                $uploadedFiles[] = [
                    'user_code' => $image->user_code,
                    'file_path' => asset("storage/{$path}") // Generate public URL
                ];
            }
        }

        return response()->json([
            'message' => 'Images uploaded successfully!',
            'files' => $uploadedFiles
        ], 201);
    }

    public function getImages()
    {
        $user = Auth::user(); // Get authenticated user

        // Retrieve images belonging to the authenticated user
        $images = Image::where('user_code', $user->code)->get();

        // Format response with full storage URL
        $formattedImages = $images->map(function ($image) {
            return [
                'user_code' => $image->user_code,
                'file_path' => asset("storage/{$image->file_path}") // Generate accessible URL
            ];
        });

        return response()->json([
            'message' => 'User images retrieved successfully!',
            'images' => $formattedImages
        ], 200);
    }

    
    public function getImagesx()
    {
        $files = Storage::files('public/uploads'); // Get all files in the uploads folder

        $images = array_map(function ($file) {
            return asset(str_replace('public/', 'storage/', $file)); // Convert storage path to public URL
        }, $files);

        return response()->json([
            'success' => true,
            'images' => $images
        ]);
    }



}
