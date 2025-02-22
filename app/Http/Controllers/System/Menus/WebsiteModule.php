<?php

namespace App\Http\Controllers\System\Menus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WebsiteModule extends Controller
{
    
    public function createModule(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'module' => 'required|string',
            'routes' => 'required|string',
            'sort'   => 'required|integer',
            'status' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $data = $validator->validated();

            // Check if a module with the same name already exists
            $existingModule = Module::where('module', $data['module'])->first();
            if ($existingModule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Module already exists'
                ], 409); // 409 Conflict
            }
          
            $trans = Module::max('transNo');
            $transNo = empty($trans) ? 1 : $trans + 1;

            // Create the new module record
            Module::insert([
                'transNo'    => $transNo,
                'module'     => $data['module'],
                'routes'     => $data['routes'],
                'sort'       => $data['sort'],
                'status'     => $data['status'] ?? 'I',
  
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Website Module created successfully'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

     // New function to get all modules
     public function getAllModules()
     {
         try {
             $modules = Module::all();
 
             return response()->json([
                 'success' => true,
                 'data'    => $modules
             ], 200);
         } catch (\Exception $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Error: ' . $e->getMessage()
             ], 500);
         }
     }


       // Edit module by transNo
    public function editModule(Request $request, $transNo)
    {
        $validator = Validator::make($request->all(), [
            'module' => 'required|string',
            'routes' => 'required|string',
            'sort'   => 'required|integer',
            'status' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        }

        try {
            // Find the module by transNo
            $module = Module::where('transNo', $transNo)->first();

            if (!$module) {
                return response()->json([
                    'success' => false,
                    'message' => 'Module not found'
                ], 404); // 404 Not Found
            }

            // Update the module with new data
            $data = $validator->validated();

            $module->module = $data['module'];
            $module->routes = $data['routes'];
            $module->sort = $data['sort'];
            $module->status = $data['status'] ?? 'I'; 
            $module->updated_at = Carbon::now();
            $module->save(); // Save the updated module

            return response()->json([
                'success' => true,
                'message' => 'Website Module updated successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete module by transNo
    public function deleteModule($transNo)
    {
        try {
            // Find the module by transNo
            $module = Module::where('transNo', $transNo)->first();

            if (!$module) {
                return response()->json([
                    'success' => false,
                    'message' => 'Module not found'
                ], 404); // 404 Not Found
            }

            // Delete the module
            $module->delete();

            return response()->json([
                'success' => true,
                'message' => 'Website Module deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


}
