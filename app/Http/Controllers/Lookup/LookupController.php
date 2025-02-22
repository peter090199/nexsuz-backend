<?php

namespace App\Http\Controllers\Lookup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resource;
use DB;
use Auth;

class LookupController extends Controller
{
    //

    public function userlists(Request $request) {
        if (empty($request->s1)) {
            return response()->json([]);
        }
        $data = DB::select('SELECT 
            fname AS fname,
            lname AS lname,
            code AS code,
            CASE 
                WHEN EXISTS (SELECT 1 FROM userprofiles WHERE code = resources.code LIMIT 1) 
                THEN (SELECT photo_pic FROM userprofiles WHERE code = resources.code LIMIT 1)
                ELSE NULL 
            END AS profile_pic
        FROM resources 
        WHERE fname LIKE ? OR lname LIKE ?', ['%' . $request->s1 . '%', '%' . $request->s1 . '%']);
    
        return response()->json($data);
    }
    

}
