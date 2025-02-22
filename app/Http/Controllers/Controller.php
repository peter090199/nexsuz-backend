<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use DB;
use Auth;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;




    public function accessmenu(Request $request)
    {
        $roleaccessmenu = DB::select('SELECT COUNT(m.id) as count 
            FROM roleaccessmenus AS r 
            INNER JOIN menus AS m ON m.id = r.menus_id 
            WHERE r.rolecode = ? AND m.description = ? AND m.status = "A"',
            [Auth::user()->role_code, $request->description]
        );

        $roleaccesssub = DB::select('SELECT COUNT(s.id) as count 
            FROM roleaccesssubmenus AS r 
            INNER JOIN submenus AS s ON s.id = r.submenus_id 
            WHERE r.rolecode = ? AND s.description = ? AND s.status = "A"',
            [Auth::user()->role_code, $request->description]
        );

        // Get the counts
        $menuCount = $roleaccessmenu[0]->count ?? 0;
        $subMenuCount = $roleaccesssub[0]->count ?? 0;

        // Check if user has access
        if ($menuCount > 0 || $subMenuCount > 0) {
            return 1; // Access granted
        } else {
            return response()->json(['success' => false, 'message' => "Unauthorized"], 403); // Access denied
        }
    }
}
