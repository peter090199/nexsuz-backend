<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 
use App\Models\Image;

class ImageController extends Controller
{

    public function uploadImages(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'module' => 'required|string',
            'description' => 'required|string',
        ]);

        $uploadedFiles = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename);

                // Save each uploaded image with its related form data
                $image = Image::create([
                    'module' => $request->module,
                    'description' => $request->description,
                    'price' => json_encode($request->price),
                    'desc_images' => json_encode($request->desc_images),
                    'image' => 'uploads/' . $filename
                ]);

                $uploadedFiles[] = $image;
            }
        }

        return response()->json(['success' => true, 'images' => $uploadedFiles], 201);
    }
}
