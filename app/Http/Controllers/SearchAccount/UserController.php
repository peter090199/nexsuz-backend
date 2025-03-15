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
