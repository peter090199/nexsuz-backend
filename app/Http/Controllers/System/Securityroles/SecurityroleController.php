<?php

namespace App\Http\Controllers\System\Securityroles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Role;
use App\Models\Submenu;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Auth; 
use App\Models\Roleaccessmenu;
use App\Models\Roleaccesssubmenu;

class SecurityroleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $description = "Security roles";
    public function index(Request $request)
    {
        $request->merge(['description' => $this->description]);
        $accessResponse = $this->accessmenu($request);

        if ($accessResponse !== 1) {
            return response()->json(['success' => false,'message' => 'Authorized']);
        }
        
        if(Auth::check()){
    
            if (Auth::user()->role_code == 'DEF-MASTERADMIN') {
                $data = Role::select('id', 'rolecode', 'description','created_by','updated_by')->orderby('id')->get();
                return response()->json(['success' => true, 'message' => $data]);
            } else {
                return response()->json(['success' => false, 'message' => "You have no rights."]);
            }
        }
        else{
            return response()->json(['success' => false, 'message' => "Unauthorized"]);
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
         $request->merge(['description' => $this->description]);
         $accessResponse = $this->accessmenu($request);
     
         if ($accessResponse !== 1) {
             return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
         }
     
         try {
             DB::beginTransaction();
             $data = $request->all();
     
             foreach ($data['header'] as $header) {
                 $trans = Roleaccessmenu::max('transNo');
                 $transNo = empty($trans) ? 1 : $trans + 1;
     
                 $head = Validator::make($header, [
                     'rolecode' => 'required|string',
                     'menus_id' => 'required|numeric'
                 ]);
 
     
                 if ($head->fails()) {
                     DB::rollBack();
                     return response()->json(['success' => false, 'message' => $head->errors()], 422);
                 }
     
                  // Delete existing records for the given rolecode
                 //  Roleaccessmenu::where('rolecode', $header['rolecode'])->delete();
                 //  Roleaccesssubmenu::where('rolecode', $header['rolecode'])->delete();
 
                 // Insert new role access menu
                 Roleaccessmenu::insert([
                     "rolecode" => $header['rolecode'],
                     "transNo" => $transNo,
                     "menus_id" => $header['menus_id'],
                     "created_by" => Auth::user()->fullname,
                     "updated_by" => Auth::user()->fullname
                 ]);
     
                 // Insert role access submenus if provided
                 if (!empty($header['lines']) && is_array($header['lines'])) {
                     foreach ($header['lines'] as $line) {
                         if (!empty($line['submenus_id'])) {
                             $line['rolecode'] = $line['rolecode'] ?? $header['rolecode'];
     
                             $l = Validator::make((array) $line, [
                                 "submenus_id" => 'required|numeric'
                             ]);
     
                             if ($l->fails()) {
                                 DB::rollBack();
                                 return response()->json(['success' => false, 'message' => $l->errors()], 422);
                             }
     
                             Roleaccesssubmenu::insert([
                                 "rolecode" => $line['rolecode'],
                                 "transNo" => $transNo,
                                 "submenus_id" => $line['submenus_id'],
                                 "created_by" => Auth::user()->fullname,
                                 "updated_by" => Auth::user()->fullname
                             ]);
                         }
                     }
                 }
             }
     
             DB::commit();
             return response()->json(['success' => true, 'message' => 'Data inserted successfully']);
         } catch (\Throwable $th) {
             DB::rollBack();
             return response()->json(['success' => false, 'message' => $th->getMessage()]);
         }
     }
 
    
    /**
     * Display the specified resource.
     */


    // public function show(Request $request, string $id)
    // {
    //     $request->merge(['description' => $this->description]);
    //     $accessResponse = $this->accessmenu($request);
    
    //     if ($accessResponse !== 1) {
    //         return response()->json(['success' => false, 'message' => 'Unauthorized']);
    //     }
    
    //     if (Auth::check()) {
    //         if (Auth::user()->role_code == 'DEF-MASTERADMIN') {
    //             // Fetch all menu items and the related role-based access modules
    //             $menu = Menu::all();
    //             $modules = Roleaccessmenu::where('rolecode', $id)->get(); // Get the modules for the given role code
                
    //             $result = [];
    
    //             // Iterate over each menu item
    //             foreach ($menu as $m) {
    //                 // Check if the menu item has access for the role
    //                 $access = $modules->contains('menus_id', $m->id) ? true : false;
    //                 $submenu = Submenu::where('transNo', $m->transNo)->get();  // Get submenus related to this menu
    //                 $submodule = Roleaccesssubmenu::where('rolecode', $id)->get(); // Get submodules for this role
    
    //                 $sub = [];
    
    //                 // Iterate over submenus
    //                 foreach ($submenu as $s) {
    //                     $saccess = $submodule->contains('submenus_id', $s->id) ? true : false;
    
    //                     $sub[] = [
    //                         "desc_code" => $s->desc_code,
    //                         "transNo" =>  $s->transNo,
    //                         "description" => $s->description,
    //                         "submenus_id" => $s->id,
    //                         "sort" => $s->sort,
    //                         "access" => $saccess
    //                     ];
    //                 }
    
    //                 // Add the menu item along with its submenus to the result array
    //                 $result[] = [
    //                     "desc_code" => $m->desc_code,
    //                     "transNo" =>  $m->transNo,
    //                     "description" => $m->description,
    //                     "menus_id" => $m->id,
    //                     "sort" => $m->sort,
    //                     "access" => $access,
    //                     "submenu" => $sub,
    //                 ];
    //             }

    //             // Group the result by 'desc_code' and sort the submenus and menus
    //             $grouped = [];
    //             foreach ($result as $item) {
    //                 // Use 'desc_code' as the key
    //                 $grouped[$item['desc_code']][] = $item;
    //             }
    
    //             // Format the grouped data to match the desired structure
    //             $finalResult = [];
    //             foreach ($grouped as $desc_code => $menus) {
    //                 // Sort the menus and submenus by their 'sort' value
    //                 usort($menus, function ($a, $b) {
    //                     return $a['sort'] <=> $b['sort'];
    //                 });
    
    //                 // Sort submenus inside each menu item
    //                 foreach ($menus as &$menu) {
    //                     usort($menu['submenu'], function ($a, $b) {
    //                         return $a['sort'] <=> $b['sort'];
    //                     });
    //                 }
    
    //                 // Push the result into the final result array with 'desc_code' as the key
    //                 $finalResult[] = [
    //                     'desc_code' => $desc_code,
    //                     'datas' => $menus
    //                 ];
    //             }
    
    //             // Return the grouped result with 'datas' under each 'desc_code'
    //             return response()->json($finalResult);
    //         } else {
    //             return response()->json(['success' => false, 'message' => "You have no rights."]);
    //         }
    //     } else {
    //         return response()->json(['success' => false, 'message' => "Unauthorized"]);
    //     }
    // }

    public function show(Request $request, string $id)
    {
        $request->merge(['description' => $this->description]);
        $accessResponse = $this->accessmenu($request);

        if ($accessResponse !== 1) {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }

        if (Auth::check()) {
            if (Auth::user()->role_code == 'DEF-MASTERADMIN') {
                // Fetch all menu items and the related role-based access modules
                $menu = Menu::all();
                $modules = Roleaccessmenu::where('rolecode', $id)->get(); // Get the modules for the given role code
                
                $result = [];

                // Iterate over each menu item
                foreach ($menu as $m) {
                    // Check if the menu item has access for the role
                    $access = $modules->contains('menus_id', $m->id);
                    $submenu = Submenu::where('transNo', $m->transNo)->get();  // Get submenus related to this menu
                    $submodule = Roleaccesssubmenu::where('rolecode', $id)->get(); // Get submodules for this role

                    $sub = [];

                    // Iterate over submenus
                    foreach ($submenu as $s) {
                        $saccess = $submodule->contains('submenus_id', $s->id);

                        $sub[] = [
                            "desc_code" => $s->desc_code,
                            "transNo" =>  $s->transNo,
                            "description" => $s->description,
                            "submenus_id" => $s->id,
                            "sort" => $s->sort,
                            "access" => $saccess
                        ];
                    }

                    // Add the menu item along with its submenus to the result array under desc_code
                    $result[$m->desc_code][] = [
                        "desc_code" => $m->desc_code,
                        "transNo" =>  $m->transNo,
                        "description" => $m->description,
                        "menus_id" => $m->id,
                        "sort" => $m->sort,
                        "access" => $access,
                        "submenu" => $sub,
                    ];
                }

                // Format the grouped data to match the desired structure by sorting menus and submenus within each desc_code group
                $finalResult = [];
                foreach ($result as $desc_code => $menus) {
                    // Sort the menus and submenus by their 'sort' value
                    usort($menus, function ($a, $b) {
                        return $a['sort'] <=> $b['sort'];
                    });

                    // Sort submenus inside each menu item
                    foreach ($menus as &$menu) {
                        usort($menu['submenu'], function ($a, $b) {
                            return $a['sort'] <=> $b['sort'];
                        });
                    }

                    // Push the result into the final result array with 'desc_code' as the key
                    $finalResult[] = [
                        'desc_code' => $desc_code,
                        'datas' => $menus
                    ];
                }

                // Return the grouped result with 'datas' under each 'desc_code'
                return response()->json($finalResult);
            } else {
                return response()->json(['success' => false, 'message' => "You have no rights."]);
            }
        } else {
            return response()->json(['success' => false, 'message' => "Unauthorized"]);
        }
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




// security POST
// {
//     "hearder": [
//         {
//             "rolecode": "admin",
//             "menus_id": 1,
//             "lines": [
//                 {
//                     "submenus_id": 10,
//                     "rolecode": "admin"
//                 },
//                 {
//                     "submenus_id": 12,
//                     "rolecode": "admin"
//                 }
//             ]
//         },
//         {
//             "rolecode": "user",
//             "menus_id": 2,
//             "lines": [
//                 {
//                     "submenus_id": 20
//                 },
//                 {
//                     "submenus_id": 22
//                 }
//             ]
//         }
//     ]
// }
