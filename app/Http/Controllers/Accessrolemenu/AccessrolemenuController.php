<?php

namespace App\Http\Controllers\Accessrolemenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Validator;
use App\Models\Roleaccessmenu;
use App\Models\Roleaccesssubmenu;
use App\Models\Submenu;
use App\Models\Menu;
use DB;

class AccessrolemenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   // REVISED CODE
   public function index(Request $request)
   {

        if (Auth::check()) {
            $modules = Roleaccessmenu::where('rolecode', Auth::user()->role_code)->get(); 

            $result = [];
            for ($m = 0; $m < count($modules); $m++) {
                
                $menus = Menu::where('id', $modules[$m]->menus_id)
                    ->where('status', 'A')
                    ->where('desc_code', $request->desc_code)
                    ->orderBy('sort')
                    ->get();

            
                for ($me = 0; $me < count($menus); $me++) {
                    
                    $submodule = Roleaccesssubmenu::where([
                        ['rolecode', Auth::user()->role_code],
                        ['transNo', $modules[$m]->transNo]
                    ])->get();

                    // Initialize an empty submenus array
                    $sub = [];

            
                    for ($sb = 0; $sb < count($submodule); $sb++) {
                        $submenus = Submenu::where('id', $submodule[$sb]->submenus_id)
                            ->where('status', 'A')
                            ->where('desc_code', $request->desc_code)
                            ->orderBy('sort')
                            ->get();
                        for ($su = 0; $su < count($submenus); $su++) {
                            $sub[] = [
                                "description" => $submenus[$su]->description,
                                "icon" => $submenus[$su]->icon,
                                "route" => $submenus[$su]->routes,
                                "sort" => $submenus[$su]->sort
                            ];
                        }
                    }

                
                    $result[] = [
                        "description" => $menus[$me]->description,
                        "icon" => $menus[$me]->icon,
                        "route" => $menus[$me]->routes,
                        "sort" => $menus[$me]->sort,
                        "submenus" => $sub
                    ];
                }
            }

        
            usort($result, function($a, $b) {
                return $a['sort'] <=> $b['sort'];
            });

            return response()->json($result);
        } else {
            return response("authenticated");
        }


        
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
        //
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
// accessmenu.index GET 
// {
//     "desc_code" : "tnavigation_token"
// }