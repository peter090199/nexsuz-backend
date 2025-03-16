<?php

namespace App\Http\Controllers\SearchAccount;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function searchUsers(Request $request)
    {
        $search = trim($request->input('search', ''));

        $users = DB::table('users')
            ->leftJoin('userprofiles', 'userprofiles.code', '=', 'users.code')
            ->leftJoin('userskills', 'userskills.code', '=', 'users.code')
            ->select(
                'users.code', 
                'users.status', 
                'users.fullname', 
                'users.is_online',  // Added is_online field
                DB::raw('GROUP_CONCAT(DISTINCT userskills.skills ORDER BY userskills.skills SEPARATOR ", ") as skills'),
                DB::raw('ANY_VALUE(userprofiles.photo_pic) as photo_pic') 
            )
            ->where('users.status', 'A')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('users.fullname', 'LIKE', "%$search%")
                    ->orWhere('userskills.skills', 'LIKE', "%$search%");
                });
            })
            ->groupBy('users.code', 'users.status', 'users.fullname', 'users.is_online')
            ->orderByRaw("
                CASE 
                    WHEN users.fullname = ? THEN 1 
                    WHEN users.fullname LIKE ? THEN 2
                    WHEN GROUP_CONCAT(userskills.skills ORDER BY userskills.skills SEPARATOR ', ') LIKE ? THEN 3
                    ELSE 4 
                END ASC", [$search, "$search%", "%$search%"])
            ->orderByRaw("LOWER(users.fullname) ASC") // ✅ Case-insensitive sorting
            ->get();

        // Separate online and offline users
        $onlineUsers = $users->where('is_online', true)->values();
        $offlineUsers = $users->where('is_online', false)->values();

        return response()->json([
            'success' => true,
            'online' => $onlineUsers,
            'offline' => $offlineUsers
        ]);
    }

    public function searchUsers11(Request $request)
    {
        $search = trim($request->input('search', ''));
    
        $users = DB::table('users')
            ->leftJoin('userprofiles', 'userprofiles.code', '=', 'users.code')
            ->leftJoin('userskills', 'userskills.code', '=', 'users.code')
            ->select(
                'users.code', 
                'users.status', 
                'users.fullname', 
                DB::raw('GROUP_CONCAT(DISTINCT userskills.skills ORDER BY userskills.skills SEPARATOR ", ") as skills'),
                DB::raw('ANY_VALUE(userprofiles.photo_pic) as photo_pic') 
            )
            ->where('users.status', 'A')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('users.fullname', 'LIKE', "%$search%")
                      ->orWhere('userskills.skills', 'LIKE', "%$search%");
                });
            })
            ->groupBy('users.code', 'users.status', 'users.fullname')
            ->orderByRaw("
                CASE 
                    WHEN users.fullname = ? THEN 1 
                    WHEN users.fullname LIKE ? THEN 2
                    WHEN GROUP_CONCAT(userskills.skills ORDER BY userskills.skills SEPARATOR ', ') LIKE ? THEN 3
                    ELSE 4 
                END ASC", [$search, "$search%", "%$search%"])
            ->orderByRaw("LOWER(users.fullname) ASC") // ✅ Case-insensitive sorting
            ->get();
    
        return response()->json($users);
    }
    

    

    public function searchUsers1(Request $request)
    {
        $search = $request->input('search', ''); // Get search input or default to empty

        $users = DB::table('users')
            ->leftJoin('userprofiles', 'userprofiles.code', '=', 'users.code')
            ->leftJoin('userskills', 'userskills.code', '=', 'users.code')
            ->select(
                'users.code', 
                'users.status', 
                'users.fullname', 
                DB::raw('GROUP_CONCAT(userskills.skills SEPARATOR ", ") as skills'), 
                'userprofiles.photo_pic',
                
            )
            ->where('users.status', 'A')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('users.fullname', 'LIKE', "%$search%")
                    ->orWhere('userskills.skills', 'LIKE', "%$search%");
                });
            })
            ->groupBy('users.code', 'users.status', 'users.fullname', 'userprofiles.photo_pic')
            ->orderBy('users.code', 'DESC')
            ->get();

        return response()->json($users);
    }

    public function searchUsersxx(Request $request)
    {
        $search = $request->input('search', '');

        $users = DB::table('users')
            ->leftJoin('userprofiles', 'userprofiles.code', '=', 'users.code')
            ->leftJoin('userskills', 'userskills.code', '=', 'users.code')
            ->select(
                'users.code', 
                'users.status', 
                'users.fullname', 
                DB::raw('GROUP_CONCAT(userskills.skills SEPARATOR ", ") as skills'), 
                'userprofiles.photo_pic'
            )
            ->where('users.status', 'A')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('users.fullname', 'LIKE', "%$search%")
                       ->orWhere('userskills.skills', 'LIKE', "%$search%");
                });
            })
            ->groupBy('users.code', 'users.status', 'users.fullname', 'userprofiles.photo_pic')
            ->orderBy('users.code', 'DESC')
            ->get();

        return response()->json($users);
    }
}
