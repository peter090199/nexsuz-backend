<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 
use App\Models\Userprofile;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use DB;


class LoginController extends Controller
{

    public function loginUsername(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }

        // Attempt to authenticate the user using username
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            // Authentication passed
            $user = Auth::user();

            // Check if the user's status is "I" (inactive)
            if ($user->status === 'I') {
                Auth::logout();
                return response()->json([
                    'success' => false,
                    'message' => "Your account is inactive. Please activate your account through the email we sent."
                ]);
            }

            // If the account is active, create a token
            $token = $user->createToken('Personal Access Token')->plainTextToken;

            $userprofileexist = Userprofile::where('code', $user->code)->count();

            if ($user->role_code === 'DEF-CLIENT' || $user->role_code === 'DEF-MASTERADMIN' || $userprofileexist > 0) {
                // Redirect DEF-CLIENT directly to home
                return response()->json([
                    'success' => true,
                    'token' => $token,
                    'message' => 0, // Home
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'token' => $token,
                    'message' => 1, // Other destination
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'The username or password is incorrect. Please check your credentials.'
        ]);
    }

    public function login(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
    
        // Attempt to authenticate the user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Authentication passed
            $user = Auth::user();
    
            // Check if the user's status is "I" (inactive)
            if ($user->status === 'I') {
                // Log out the user
                Auth::logout();
                return response()->json(['success' => false, 'message' => "Your account is inactive. Please activate your account through the email we sent to your Gmail."]);
           
            }

            // Update user's online status
            $user->is_online = true;
            $user->save();

            // If the account is active, create a token
            $token = $user->createToken('Personal Access Token')->plainTextToken;

            $userprofileexist = Userprofile::where('code', Auth::user()->code)->count();

            if(Auth::user()->role_code === 'DEF-CLIENT' || Auth::user()->role_code === 'DEF-MASTERADMIN'  || $userprofileexist > 0){
                // DEF-CLIENT DIRECT TO HOME 0
                return response()->json([
                    'success' => true,
                    'token' => $token,
                    'message' => 0,
                    'is_online' => true
                ]);
            }else{
                return response()->json([
                    'success' => true,
                    'token' => $token,
                    'message' => 1,
                    'is_online' => true
                ]);
            }
        }
        return response()->json(['success' => false, 'message' => 'The email or password is incorrect. Please check your credentials.']);
    }

    
    public function logout(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();
    
        if ($user) {
            // Set the user as offline
            $user->is_online = false;
            $user->save();
    
            // Revoke the token that was used to authenticate the request
            $user->currentAccessToken()->delete();
        }
    
        return response()->json(['success' => true, 'message' => 'You have been logged out successfully.']);
    }
    
    public function logoutx(Request $request)
    {
        // Revoke the token that was used to authenticate the request
        $request->user()->currentAccessToken()->delete();
        $user->is_online = false;
        $user->save();

        return response()->json(['success' => true , 'message' => 'You have been logged out successfully.']);
    }
    public function getIsOnline()
    {
        $users = DB::table('users')->select('code', 'is_online')->get();
        return response()->json([
            'success' => true,
            'online' => $users->where('is_online', true)->values(),
            'offline' => $users->where('is_online', false)->values()
        ]);
    }
    
    public function getIsOnlinexx(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|exists:users,code', // Ensure 'code' exists in 'users' table
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
    
        // Fetch user by 'code'
        $user = User::where('code', $request->code)->first();
    
        return response()->json([
            'success' => true,
            'is_online' => (bool) $user->is_online
        ]);
    }
    
}


// login POST
// {
//     "email" : "TEST@gmail.com", 
//     "password": "1",
// }