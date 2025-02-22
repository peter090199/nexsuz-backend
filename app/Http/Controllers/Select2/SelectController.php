<?php

namespace App\Http\Controllers\Select2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use Auth;

class SelectController extends Controller
{
    //

    public function rolecode(Request $request){

        if(Auth::check()){
            if (Auth::user()->role_code == 'DEF-MASTERADMIN') {

                $role = Role::where('rolecode', 'like', '%' . $request->search . '%')->orwhere('description', 'like', '%' . $request->search . '%')
                ->select('description', 'rolecode')
                ->get();
                return response()->json($role);
            } else {
                return response()->json(['success' => false, 'message' => "You have no rights."]);
            }
        }
        else{
            return response()->json(['success' => false, 'message' => "Unauthorized"]);
        }  
    }
}
