<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Userprofile;
class ProfilepictureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $imagePath = "https://red-anteater-382469.hostingersite.com/public/storage/app/public/uploads/702/cvphoto/d0bd7bb8-72f5-43ef-9f26-c382181982f9/HjL8tqsDplfNwrImxwf1YqANUilOt2KL5si1AVQ3.png";
        return view('testuploads',compact('imagePath'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        
        // Validate file input before starting the transaction
        $validator = Validator::make($data, [
            'photo_pic' => 'required|file|image|max:3000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        if (!$request->hasFile('photo_pic')) {
            return response()->json([
                'success' => false,
                'message' => 'No file was uploaded.',
            ]);
        }

        try {
            DB::beginTransaction(); // Start the transaction

            $file = $request->file('photo_pic');
            $userCode = Auth::user()->code;
            $uuid = Str::uuid();
            $folderPath = "uploads/{$userCode}/cvphoto/{$uuid}";

            // Store the file with a readable name
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $photoPath = $file->storeAs($folderPath, $fileName, 'public');

            // Construct the full file URL
            $photoUrl = asset(Storage::url($photoPath));

            // Check if the user profile exists
            $exists = UserProfile::where('code', $userCode)->first();

            if ($exists) {
                UserProfile::where('code', $userCode)->update([
                    'photo_pic' => "https://red-anteater-382469.hostingersite.com/storage/app/public/".$folderPath."/".$fileName,
                ]);
            } else {
                $transNo = UserProfile::max('transNo');
                $newTrans = empty($transNo) ? 1 : $transNo + 1;

                UserProfile::insert([
                    'code' => $folderPath,
                    'transNo' => $newTrans,
                    'photo_pic' => "https://red-anteater-382469.hostingersite.com/storage/app/public/".$folderPath,
                ]);
            }
            DB::commit(); // Commit the transaction
            return response()->json([
                'success' => true,
                'photo_path' => $photoUrl,
            ]);

        } catch (\Throwable $th) {
            DB::rollBack(); // Rollback the transaction on error

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $th->getMessage(),
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
