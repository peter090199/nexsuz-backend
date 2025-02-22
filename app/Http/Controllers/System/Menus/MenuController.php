<?php

namespace App\Http\Controllers\System\Menus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Menu;
use App\Models\Submenu;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Auth; 

class MenuController extends Controller
{
 

    private $description = "Menus";

    public function index(Request $request)
    {
        $request->merge(['description' => $this->description]);
        $accessResponse = $this->accessmenu($request);

        if ($accessResponse !== 1) {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }

        $menu = Menu::orderBy('sort', 'asc')->get();
        $result = [];

        foreach ($menu as $m) {
            $submenu = Submenu::where('transNo', $m->transNo)->orderBy('sort', 'asc')->get();
            $sub = [];

            foreach ($submenu as $su) {
                $sub[] = [
                    "id" => $su->id,
                    "transNo" => $su->transNo,
                    "desccode" => $su->desc_code,
                    "description" => $su->description,
                    "icon" => $su->icon,
                    "route" => $su->routes,
                    "sort" => $su->sort,
                    "status" => $su->status,
                    "updated_by" => $su->updated_by,
                ];
            }

            $result[] = [
                "id" => $m->id,
                "transNo" => $m->transNo,
                "desccode" => $m->desc_code,
                "description" => $m->description,
                "icon" => $m->icon,
                "route" => $m->routes,
                "sort" => $m->sort,
                "submenu" => $sub,
                "status" => $m->status,
                "updated_by" => $m->updated_by,
            ];
        }

        return response()->json($result);
    }

    public function store(Request $request)
    {


        // $request->merge(['description' => $this->description]);
        // $accessResponse = $this->accessmenu($request);

        // if ($accessResponse !== 1) {
        //     return response()->json(['success' => false, 'message' => 'Unauthorized']);
        // }

        try {
            DB::beginTransaction();
            $data = $request->all();
            $header = Validator::make($data, [
                'desc_code' => 'required|string',
                'description' => 'required|string',
                'icon' => 'required|string',
                'class' => 'required|string',
                'routes' => 'required|string',
                'sort' => 'required|integer',
                'status' => 'nullable|string'
            ]);

            if ($header->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $header->errors()
                ]);
            }

            // Check if the menu description already exists
            $menuexists = Menu::where('description', $data['description'])->exists();

            if ($menuexists) {
                return response()->json(['success' => false, 'message' => 'Menu description already exists. Please avoid duplicates.']);
            }
 
            $trans = Menu::max('transNo');
            $transNo = empty($trans) ? 1 : $trans + 1;

            // Create the menu
            Menu::insert([
                "transNo" => $transNo,
                'desc_code' => $data['desc_code'],
                "description" => $data['description'],
                'icon' => $data['icon'],
                'class' => $data['class'],
                'routes' => $data['routes'],
                'sort' => $data['sort'],
                'status' => $data['status'] ?: 'I',
                'created_by' => Auth::user()->fullname,
                'updated_by' => Auth::user()->fullname
            ]);

            $lineErrors = [];

            // Loop through the submenu lines
            foreach ($data['lines'] as $index => $line) {
                $lineValidator = Validator::make($line, [
                    'description' => 'required|string',
                    'icon' => 'required|string',
                    'class' => 'required|string',
                    'routes' => 'required|string',
                    'sort' => 'required|integer',
                    'status' => 'nullable|string'
                ]);

                if ($lineValidator->fails()) {
                    $lineErrors[$index] = $lineValidator->errors();
                }

                // Check if the submenu description already exists
                $subexists = Submenu::where('description', $line['description'])->exists();

                if ($subexists) {
                    return response()->json(['success' => false, 'message' => 'Submenu description already exists. Please avoid duplicates.']);
                }

                // Insert submenu if no errors
                Submenu::insert([
                    "transNo" => $transNo,
                    "desc_code" => $data['desc_code'],
                    "description" => $line['description'],
                    'icon' => $line['icon'],
                    'class' => $line['class'],
                    'routes' => $line['routes'],
                    'sort' => $line['sort'],
                    'status' => $line['status'] ?: 'I',
                    'created_by' => Auth::user()->fullname,
                    'updated_by' => Auth::user()->fullname,
                ]);
            }

            if (!empty($lineErrors)) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Submenu validation failed', 'errors' => $lineErrors]);
            }

            // Commit the transaction if everything is fine
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Menu and submenus created successfully',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }


    public function storeSubmenus(Request $request)
    {
        try {
            DB::beginTransaction(); // Start a database transaction
    
            $data = $request->all();
    
            // Validate the incoming request
            $submenuErrors = [];
    
            foreach ($data['lines'] as $index => $line) {
                $lineValidator = Validator::make($line, [
                    'description' => 'required|string',
                    'icon' => 'required|string',
                    'class' => 'required|string',
                    'routes' => 'required|string',
                    'sort' => 'required|integer',
                    'status' => 'nullable|string'
                ]);
    
                if ($lineValidator->fails()) {
                    $submenuErrors[$index] = $lineValidator->errors();
                }
    
                // Check if the submenu description already exists
                $subexists = Submenu::where('description', $line['description'])
                    ->where('transNo', $data['transNo']) // Ensure submenu is unique within the same transNo
                    ->exists();
    
                if ($subexists) {
                    return response()->json(['success' => false, 'message' => 'Submenu description already exists. Please avoid duplicates.']);
                }
            }
    
            // If there are validation errors, roll back the transaction
            if (!empty($submenuErrors)) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Submenu validation failed', 'errors' => $submenuErrors]);
            }
    
            // Check if the Menu exists by transNo
            $menu = Menu::where('transNo', $data['transNo'])->first();
            if (!$menu) {
                return response()->json(['success' => false, 'message' => 'Menu not found for the given transNo']);
            }
    
            // Get the desc_code from the Menu
            $desc_code = $menu->desc_code;
    
            // Insert submenus
            foreach ($data['lines'] as $line) {
                Submenu::insert([
                    "transNo" => $data['transNo'], // Link the submenu to the provided transNo
                    "desc_code" => $desc_code, // Add desc_code from the Menu
                    "description" => $line['description'],
                    'icon' => $line['icon'],
                    'class' => $line['class'],
                    'routes' => $line['routes'],
                    'sort' => $line['sort'],
                    'status' => $line['status'] ?: 'I', // Default to 'I' if status is not provided
                    'created_by' => Auth::user()->fullname,
                    'updated_by' => Auth::user()->fullname,
                ]);
            }
    
            // Commit the transaction
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Submenus created successfully and linked to the menu',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
    
    public function createMenu(Request $request)
    {
    
        try {
            DB::beginTransaction();
            $data = $request->all();
            
            $header = Validator::make($data, [
                'desc_code' => 'required|string',
                'description' => 'required|string',
                'icon' => 'required|string',
                'class' => 'required|string',
                'routes' => 'required|string',
                'sort' => 'required|integer',
                'status' => 'nullable|string'
            ]);
    
            if ($header->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $header->errors()
                ]);
            }
    
            // Check if the menu description already exists
            if (Menu::where('description', $data['description'])->exists()) {
                return response()->json(['success' => false, 'message' => 'Menu description already exists. Please avoid duplicates.']);
            }
    
            $trans = Menu::max('transNo');
            $transNo = empty($trans) ? 1 : $trans + 1;
    
            // Create the menu
            Menu::insert([
                "transNo" => $transNo,
                'desc_code' => $data['desc_code'],
                "description" => $data['description'],
                'icon' => $data['icon'],
                'class' => $data['class'],
                'routes' => $data['routes'],
                'sort' => $data['sort'],
                'status' => $data['status'] ?: 'I',
                'created_by' => Auth::user()->fullname,
                'updated_by' => Auth::user()->fullname
            ]);
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Menu created successfully',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
    
    public function updateSubmenu(Request $request, $transNo)
    {
        // Validate the input data
        $data = $request->all();
        $submenuValidator = Validator::make($data, [
            'lines' => 'required|array',
            'lines.*.description' => 'required|string',
            'lines.*.icon' => 'required|string',
            'lines.*.class' => 'required|string',
            'lines.*.routes' => 'required|string',
            'lines.*.sort' => 'required|integer',
            'lines.*.status' => 'nullable|string'
        ]);
    
        if ($submenuValidator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $submenuValidator->errors()
            ]);
        }
    
        // Check if the parent Menu exists
        $menuExists = Menu::where('transNo', $transNo)->exists();
    
        if (!$menuExists) {
            return response()->json(['success' => false, 'message' => 'Menu with this transNo does not exist']);
        }
    
        // Start the transaction
        DB::beginTransaction();
    
        try {
            // Loop through the submenu lines and update them
            $lineErrors = [];
            foreach ($data['lines'] as $index => $line) {
                // Validate each submenu line
                $lineValidator = Validator::make($line, [
                    'description' => 'required|string',
                    'icon' => 'required|string',
                    'class' => 'required|string',
                    'routes' => 'required|string',
                    'sort' => 'required|integer',
                    'status' => 'nullable|string'
                ]);
    
                if ($lineValidator->fails()) {
                    $lineErrors[$index] = $lineValidator->errors();
                    continue;
                }
                $submenu = Menu::where('transNo', $transNo)
                ->first();

               
    
                if ($submenu) {
                    $submenu->update([
                        'description' => $line['description'],
                        'icon' => $line['icon'],
                        'class' => $line['class'],
                        'routes' => $line['routes'],
                        'sort' => $line['sort'],
                        'status' => $line['status'] ?: 'I', // Default status if not provided
                        'updated_by' => Auth::user()->fullname
                    ]);
                } else {
                    $lineErrors[$index][] = 'Submenu not found with the specified description.';
                }
            }
    
            // If there are errors, roll back the transaction
            if (!empty($lineErrors)) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Submenu update failed', 'errors' => $lineErrors]);
            }
    
            // Commit the transaction after successful updates
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Submenus updated successfully',
            ]);
    
        } catch (\Throwable $th) {
            // If an error occurs, roll back the transaction
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
    
    
 public function destroyByTransNo($transNo)
{
    try {
        DB::beginTransaction();
        // Find the menu by transNo
        $menu = Menu::where('transNo', $transNo)->first();
        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Menu not found']);
        }
        Submenu::where('transNo', $transNo)->delete();
        $menu->delete();
        // Commit the transaction
        DB::commit();
        return response()->json([
            'success' => true,
            'message' => 'Menu (module) and its submenus (submodules) deleted successfully',
        ]);
    } catch (\Throwable $th) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $th->getMessage()]);
    }
}

public function getSubmenuByMenuTransNo($transNo)
{
    // Retrieve the menu by transNo
    $menu = Menu::where('transNo', $transNo)->first();

    if (!$menu) {
        return response()->json(['success' => false, 'message' => 'Menu not found']);
    }

    // Retrieve the associated submenus for the given transNo
    $submenus = Submenu::where('transNo', $transNo)->orderBy('sort', 'asc')->get();

    // Format the submenu data
    $submenuData = $submenus->map(function($submenu) {
        return [
            'id' => $submenu->id,
            'transNo' => $submenu->transNo,
            'desccode' => $submenu->desc_code,
            'description' => $submenu->description,
            'icon' => $submenu->icon,
            'route' => $submenu->routes,
            'sort' => $submenu->sort,
            'status' => $submenu->status,
            'updated_by' => $submenu->updated_by,
        ];
    });

    return response()->json([
        'success' => true,
        'submenus' => $submenuData
    ]);
}


}