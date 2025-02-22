<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Website\About;
use Exception;
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;
use Carbon\CarbonTimeZone;


class ModuleTask extends Controller
{
    public function post(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized: User not authenticated'], 401);
            }

            // Validate input (removing unique validation to handle it manually)
            $validated = $request->validate([
                'transNo' => 'required|string|max:255',  
                'about' => 'required|string',
                'description' => 'nullable|string|max:1000',
            ]);

            // Check if transNo already exists
            if (About::where('transNo', $validated['transNo'])->exists()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Duplicate Entry',
                    'message' => "The TransNo '{$validated['transNo']}' already exists."
                ], 201); // 409 Conflict status
            }

            $user = Auth::user(); // Get authenticated user

            // Save to the database
            $data = new About();
            $data->transNo = $validated['transNo'];
            $data->about = $validated['about'];
            $data->description = $validated['description'] ?? null;
            $data->created_by = $user->fullname; 
            $data->updated_by = $user->fullname;
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'About saved successfully!',
                'data' => $data
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong!',
                'message' => $e->getMessage()
            ], 500);
        }
    }

public function get(Request $request)
{
    try {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized: User not authenticated'], 401);
        }

        $timezone = new CarbonTimeZone('Asia/Manila');

        // Check if transNo is provided to fetch a specific record
        if ($request->has('transNo')) {
            $about = About::where('transNo', $request->transNo)->first();

            if (!$about) {
                return response()->json([
                    'success' => false,
                    'error' => 'Not Found',
                    'message' => "No record found for TransNo '{$request->transNo}'."
                ], 404);
            }

            // Format datetime in Manila timezone
            $about->created_at = Carbon::parse($about->created_at)->setTimezone($timezone)->format('Y-m-d H:i:s');
            $about->updated_at = Carbon::parse($about->updated_at)->setTimezone($timezone)->format('Y-m-d H:i:s');

            return response()->json([
                'success' => true,
                'data' => $about
            ], 200);
        }

        // Fetch all records
        $data = About::all()->map(function ($item) use ($timezone) {
            $item->created_at = Carbon::parse($item->created_at)->setTimezone($timezone)->format('Y-m-d H:i:s');
            $item->updated_at = Carbon::parse($item->updated_at)->setTimezone($timezone)->format('Y-m-d H:i:s');
            return $item;
        });

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

//not authinticated

public function getAbout(Request $request)
{
    try {

        $timezone = new CarbonTimeZone('Asia/Manila');

        // Check if transNo is provided to fetch a specific record
        if ($request->has('transNo')) {
            $about = About::where('transNo', $request->transNo)->first();

            if (!$about) {
                return response()->json([
                    'success' => false,
                    'error' => 'Not Found',
                    'message' => "No record found for TransNo '{$request->transNo}'."
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $about
            ], 200);
        }

        // Fetch all records
        $data = About::all()->map(function ($item) use ($timezone) {
            $item->created_at = Carbon::parse($item->created_at)->setTimezone($timezone)->format('Y-m-d H:i:s');
            $item->updated_at = Carbon::parse($item->updated_at)->setTimezone($timezone)->format('Y-m-d H:i:s');
            return $item;
        });

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


public function delete($transNo)
{
    try {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized: User not authenticated'], 401);
        }

        $about = About::where('transNo', $transNo)->first();

        if (!$about) {
            return response()->json([
                'success' => false,
                'error' => 'Not Found',
                'message' => "No record found for TransNo '{$transNo}'."
            ], 404);
        }

        // Delete the record
        $about->delete();

        return response()->json([
            'success' => true,
            'message' => "Record with TransNo '{$transNo}' deleted successfully."
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Something went wrong!',
            'message' => $e->getMessage()
        ], 500);
    }
}

}