<?php

namespace App\Http\Controllers\System\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use Auth;

use App\Models\Roleaccessmenu;
use App\Models\Roleaccesssubmenu;
use App\Models\Submenu;
use App\Models\Menu;
use App\Models\User;
use DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
     private $description = "Roles";
    public function index(Request $request)
    {
        $request->merge(['description' => $this->description]);
        $accessResponse = $this->accessmenu($request);

        if ($accessResponse !== 1) {
            return response()->json(['success' => false,'message' => 'Unauthorized']);
        }

        if(Auth::check()){
            if (Auth::user()->role_code == 'DEF-MASTERADMIN') {
                $data = Role::all();
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
        // 

        $request->merge(['description' => $this->description]);
        $accessResponse = $this->accessmenu($request);

        if ($accessResponse !== 1) {
            return response()->json(['success' => false,'message' => 'Unauthorized']);
        }

        if(Auth::check()){
            if (Auth::user()->role_code == 'DEF-MASTERADMIN') {
                try {
                    DB::beginTransaction();
                
                    $validator = Validator::make($request->all(), [
                        "rolecode" => 'required|string',
                        "description" => 'required|string'
                    ]);
                
                    if ($validator->fails()) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => $validator->errors()
                        ]);
                    }
        
                    $exist = Role::where('rolecode', $request->rolecode)->exists();
                    if ($exist) {
                        DB::rollBack();
                        return response()->json(['success' => false, 'message' => "Role code already exists."]);
                    }
            
                    Role::create([
                        "rolecode" => $request->rolecode,
                        "description" => $request->description,
                        "created_by" => Auth::user()->fullname
                    ]);
                
                    DB::commit();
                    return response()->json(['success' => true, 'message' => "Role code inserted successfully."]);
                
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => $th->getMessage()]);
                } 
            } else {
                return response()->json(['success' => false, 'message' => "You have no rights."]);
            }
        }
        else{
            return response()->json(['success' => false, 'message' => "Unauthorized"]);
        }

        

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request , string $id)
    {
        //
        $request->merge(['description' => $this->description]);
        $accessResponse = $this->accessmenu($request);

        if ($accessResponse !== 1) {
            return response()->json(['success' => false,'message' => 'Unauthorized']);
        }

        if(Auth::check()){

            if (Auth::user()->role_code == 'DEF-MASTERADMIN') {
                try {
                    $data = Role::findOrFail($id);
                    return response()->json(['success' => true,'message' => $data
                    ]);
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    return response()->json(['success' => false,'message' => 'Role not found.']);
                }
            } else {
                return response()->json(['success' => false, 'message' => "You have no rights."]);
            }
        }
        else{
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

        $request->merge(['description' => $this->description]);
        $accessResponse = $this->accessmenu($request);

        if ($accessResponse !== 1) {
            return response()->json(['success' => false,'message' => 'Authorized']);
        }
        if (Auth::user()->role_code == 'DEF-MASTERADMIN') {
            try {
                DB::beginTransaction();
        
                // Validate the request data
                $validator = Validator::make($request->all(), [
                    "rolecode" => 'required|string',
                    "description" => 'required|string'
                ]);
        
                if ($validator->fails()) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()
                    ]);
                }
        
                // Fetch the role to be updated
                $role = Role::findOrFail($id);
        
                // Check for associations before updating
                $usersCount = User::where('role_code', $role->rolecode)->count();
                $roleaccessmenu = Roleaccessmenu::where('rolecode', $role->rolecode)->count();
                $roleaccesssubmenu = Roleaccesssubmenu::where('rolecode', $role->rolecode)->count();
        
                // Check if there are any associated users or menu items
                if ($roleaccessmenu > 0) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Cannot update role. {$roleaccessmenu} role access menus are associated with this role."
                    ]);
                }
        
                if ($roleaccesssubmenu > 0) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Cannot update role. {$roleaccesssubmenu} role access submenus are associated with this role."
                    ]);
                }
        
                if ($usersCount > 0) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Cannot update role. {$usersCount} users are associated with this role."
                    ]);
                }
        
                // Check if the role code already exists for another role
                $exist = Role::where('rolecode', $request->rolecode)
                            ->where('id', '!=', $id)
                            ->exists();
        
                if ($exist) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => "Role code already exists."]);
                }
        
                // Update the role
                $role->update([
                    "rolecode" => $request->rolecode,
                    "description" => $request->description,
                    "updated_by" => Auth::user()->fullname
                ]);
        
                DB::commit();
                return response()->json(['success' => true, 'message' => "Role updated successfully."]);
        
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => $th->getMessage()]);
            }
        } else {
            return response()->json(['success' => false, 'message' => "You have no rights."]);
        } 
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
        $request->merge(['description' => $this->description]);
        $accessResponse = $this->accessmenu($request);

        if ($accessResponse !== 1) {
            return response()->json(['success' => false,'message' => 'Unauthorized']);
        }



        if (Auth::user()->role_code == 'DEF-MASTERADMIN') {
            try {
                DB::beginTransaction();
        
                $role = Role::where('id', $id)->first(); 
        
                if ($role) {
                    $usersCount = User::where('role_code', $role->rolecode)->count();
                    $roleaccessmenu = Roleaccessmenu::where('rolecode',$role->rolecode)->count();
                    $roleaccesssubmenu = Roleaccesssubmenu::where('rolecode',$role->rolecode)->count();
    
                    if ($roleaccessmenu > 0) {
                        DB::rollBack(); 
                        return response()->json([
                            'success' => false,
                            'message' => "Cannot delete role. {$usersCount} roleaccessmenu are associated with this role."
                        ]);
                    }
    
                    if ($roleaccesssubmenu > 0) {
                        DB::rollBack(); 
                        return response()->json([
                            'success' => false,
                            'message' => "Cannot delete role. {$usersCount} roleaccesssubmenu are associated with this role."
                        ]);
                    }
    
                    if ($usersCount > 0) {
                        DB::rollBack(); 
                        return response()->json([
                            'success' => false,
                            'message' => "Cannot delete role. {$usersCount} users are associated with this role."
                        ]);
                    }
                    
                    $role->delete();
                    DB::commit();
    
                    return response()->json([
                        'success' => true,
                        'message' => "Role deleted successfully."
                    ]);
                } else {
                    DB::rollBack(); // Rollback if role is not found
                    return response()->json(['success' => false, 'message' => 'Role not found.']);
                }
        
            } catch (\Throwable $th) {
                DB::rollBack(); // Rollback on any error
                return response()->json(['success' => false, 'message' => $th->getMessage()]);
            }
        }else {
            return response()->json(['success' => false, 'message' => "You have no rights."]);
        } 
    }
}
