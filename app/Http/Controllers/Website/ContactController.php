<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use App\Models\Website\Contact;
use Exception;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    //
    public function post_contact(Request $request)
    {
        try {
            // Check if the user is authenticated
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized: User not authenticated'], 401);
            }
    
            // Validate the request and store validated data
            $validated = $request->validate([
                'transNo' => 'required|string|max:255',
                'contact_title' => 'required|string',
                'description' => 'required|string',
                'fbpage' => 'required|string',
                'mLink' => 'required|url',
                'phoneNumber' => 'required|regex:/^[0-9]{10,15}$/'
            ]);
    
            // Check if transNo already exists
            if (Contact::where('transNo', $validated['transNo'])->exists()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Duplicate Entry',
                    'message' => "The TransNo '{$validated['transNo']}' already exists."
                ], 201); // Use 409 Conflict status instead of 201
            }
    
            $user = Auth::user(); // Get authenticated user
    
            // Save to the database
            $data = new Contact();
            $data->transNo = $validated['transNo'];
            $data->contact_title = $validated['contact_title'];
            $data->description = $validated['description'] ?? null;
            $data->fbpage = $validated['fbpage'];
            $data->phoneNumber = $validated['phoneNumber'];
            $data->mLink = $validated['mLink'];
            $data->created_by = $user->fullname;
            $data->updated_by = $user->fullname;
            $data->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Contact saved successfully!',
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
                    ], 200);
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

    public function update_contact(Request $request, $transNo)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized: User not authenticated'], 401);
            }

            $request->validate([
                'contact_title' => 'required|string',
                'description' => 'nullable|string',
                'fbpage' => 'required|string',
                'mLink' => 'required|url',
                'phoneNumber' => 'required|regex:/^[0-9]{10,15}$/'
            ]);

            $contact = Contact::where('transNo', $transNo)->first();

            if (!$contact) {
                return response()->json([
                    'success' => false,
                    'error' => 'Contact not found'
                ], 404);
            }

            $user = Auth::user();

            // Update contact details
            $contact->contact_title = $request->contact_title;
            $contact->description = $request->description;
            $contact->fbpage = $request->fbpage;
            $contact->mLink = $request->mLink;
            $contact->phoneNumber = $request->phoneNumber;
            $contact->updated_by = $user->fullname;
            $contact->save();

            return response()->json([
                'success' => true,
                'message' => 'Contact updated successfully!',
                'data' => $contact
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong!',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function delete_contact($transNo)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized: User not authenticated'], 401);
            }

            $contact = Contact::where('transNo', $transNo)->first();

            if (!$contact) {
                return response()->json([
                    'success' => false,
                    'error' => 'Contact not found'
                ], 404);
            }

            $contact->delete();

            return response()->json([
                'success' => true,
                'message' => "Contact '{$transNo}' deleted successfully!"
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong!',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    
    //not authinticated
    public function get_contactByRole(Request $request)
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
                $c = Contact::where('transNo', $request->transNo)->first();
    
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
            $data = Contact::all()->map(fn($item) => $this->filterContactData($item, $user, $timezone));
    
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



    public function get_contact(Request $request)
    {
        try {
            // Fetch data with RIGHT JOIN
            $data = DB::table('module')
                ->rightJoin('contacts', 'module.transNo', '=', 'contacts.transNo')
                ->select(
                    'module.transNo',
                    'contacts.contact_title',
                    'contacts.description',
                    'contacts.fbpage',
                    'contacts.mLink',
                    'contacts.phoneNumber'
                )
                ->get();
    
            // Check if data exists
            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Not Found',
                    'message' => 'No matching records found.'
                ], 404);
            }
    
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



}
