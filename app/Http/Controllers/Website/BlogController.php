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
    
    public function get_blogByRole(Request $request)
    {
        try {
            $timezone = new CarbonTimeZone('Asia/Manila');
    
            // Check if the user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized',
                    'message' => 'You must be logged in to access this resource.'
                ], 401);

            }
      
            $user = Auth::user();
    
            // Fetch a specific record if transNo is provided
            if ($request->has('transNo')) {
                $c = Blog::where('transNo', $request->transNo)->first();
    
                if (!$c) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Not Found',
                        'message' => "No record found for TransNo '{$request->transNo}'."
                    ], 404);
                }
    
                return response()->json([
                    'success' => true,
                    'data' => $this->filterContactData($c, $user)
                ], 200);
            }
    
            // Fetch all records
            $data = Blog::all()->map(fn($item) => $this->filterContactData($item, $user, $timezone));
    
            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);
    
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong!',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Filters contact data based on user role
     */
    private function filterContactData($contact, $user, $timezone = null)
    {
        $contactArray = $contact->toArray();
    
        // Format timestamps if timezone is provided
        if ($timezone) {
            $contactArray['created_at'] = Carbon::parse($contact->created_at)->setTimezone($timezone)->format('Y-m-d H:i:s');
            $contactArray['updated_at'] = Carbon::parse($contact->updated_at)->setTimezone($timezone)->format('Y-m-d H:i:s');
        }
    
        // Hide fields for DEF-ADMIN
        if ($user->role_code == 'DEF-ADMIN') {
            unset($contactArray['created_by'], $contactArray['updated_by'], $contactArray['created_at'], $contactArray['updated_at']);
        }
    
        return $contactArray;
    }

    
    public function update_blog(Request $request, $transNo)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized: User not authenticated'], 401);
            }

            $request->validate([
                'blog_title' => 'required|string',
                'description' => 'nullable|string',
              
            ]);

            $data = Blog::where('transNo', $transNo)->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'error' => 'Blog not found'
                ], 404);
            }

            $user = Auth::user();

            $data->blog_title = $request->blog_title;
            $data->description = $request->description;
            $data->updated_by = $user->fullname;
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'Blog updated successfully!',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong!',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function delete_blog($transNo)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized: User not authenticated'], 401);
            }

            $data = Blog::where('transNo', $transNo)->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'error' => 'Blog not found'
                ], 404);
            }

            $data->delete();

            return response()->json([
                'success' => true,
                'message' => "Blog '{$transNo}' deleted successfully!"
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
