<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Website\About;
use Exception;
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use App\Models\Module;
use Illuminate\Support\Facades\DB;


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

    public function update_about(Request $request, $transNo)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized: User not authenticated'], 401);
            }

            $request->validate([
                'about' => 'required|string',
                'description' => 'nullable|string',
              
            ]);

            $about = About::where('transNo', $transNo)->first();

            if (!$about) {
                return response()->json([
                    'success' => false,
                    'error' => 'About not found'
                ], 404);
            }

            $user = Auth::user();

            // Update contact details
            $about->about = $request->about;
            $about->description = $request->description;
            $about->updated_by = $user->fullname;
            $about->save();

            return response()->json([
                'success' => true,
                'message' => 'About updated successfully!',
                'data' => $about
            ], 200);

        } catch (\Exception $e) {
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
                $c = About::where('transNo', $request->transNo)->first();
    
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
            $data = About::all()->map(fn($item) => $this->filterContactData($item, $user, $timezone));
    
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

public function getxx(Request $request)
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
public function getAbout()
{
    try {
        $data = DB::table('module')
            ->rightJoin('about', 'module.transNo', '=', 'about.transNo')
            ->select('module.id', 'module.transNo','about.about', 'about.description')
            ->get();

        return response()->json([
            'success' => true,
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