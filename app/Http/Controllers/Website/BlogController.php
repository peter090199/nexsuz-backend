<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use App\Models\Website\Blog;
use Exception;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    //post
    public function post_blog(Request $request)
    {
        try {
            // Check if the user is authenticated
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized: User not authenticated'], 401);
            }
    
            // Validate the request and store validated data
            $validated = $request->validate([
                'transNo' => 'required|string|max:255',
                'blog_title' => 'required|string',
                'description' => 'required|string',
              
            ]);
    
            // Check if transNo already exists
            if (Blog::where('transNo', $validated['transNo'])->exists()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Duplicate Entry',
                    'message' => "The TransNo '{$validated['transNo']}' already exists."
                ], 201); // Use 409 Conflict status instead of 201
            }
    
            $user = Auth::user(); // Get authenticated user
    
            // Save to the database
            $data = new Blog();
            $data->transNo = $validated['transNo'];
            $data->blog_title = $validated['blog_title'];
            $data->description = $validated['description'] ?? null;
            $data->created_by = $user->fullname;
            $data->updated_by = $user->fullname;
            $data->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Blog saved successfully!',
                'data' => $data
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong!',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
}
