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

    public function getImages()
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
                    'file_path' => url("storage/app/public/uploads/{$image->user_code}/TransNo/{$image->trans_no}/default.png/{$image->file_path}")
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
