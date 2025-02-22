<?php
namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MenuItem;
use App\Models\MenuItemPrice;
use App\Models\MenuItemImage;
use App\Models\MenuItemDescImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 

class MenuItemController extends Controller
{
    public function SaveModule(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'module' => 'required|string|max:255',
            'transNo' => 'required|string|max:255',  // Ensure transNo is validated
            'description' => 'nullable|string|max:1000',
            'price' => 'required|array',
            'price.*' => 'required|numeric',
            'image' => 'nullable|array',
            'image.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Validate image files
            'desc_images' => 'nullable|array',
            'desc_images.*' => 'required|string',  // Make sure desc_images contains strings
        ]);
    
        try {
            // Create the menu item
            $menuItem = MenuItem::create([
                'transNo' => $validated['transNo'],  // Use transNo from the validated data
                'module' => $validated['module'],
                'description' => $validated['description'] ?? null, // Allow null if description is not provided
                'price' => json_encode($validated['price']), // Ensure price is stored as a JSON
                'image' => [], // Initially empty, to be updated after image upload
                'desc_images' => isset($validated['desc_images']) ? json_encode($validated['desc_images']) : [], // Ensure desc_images is an empty array if not provided
                'created_by' => Auth::user()->fullname,
                'updated_by' => Auth::user()->fullname
            ]);
    
            // Handle image uploads
            $imagePaths = [];
            if ($request->has('image')) {
                foreach ($validated['image'] as $image) {
                    // Store the image in the 'public/images' directory and get the path
                    $imagePath = $image->store('images', 'public');
                    $imagePaths[] = Storage::url($imagePath); // Store the image URL
                }
                // Update the 'image' field of the menu item
                $menuItem->update(['image' => json_encode($imagePaths)]);
            }
    
            // Save the prices
            foreach ($validated['price'] as $price) {
                MenuItemPrice::create([
                    'menu_item_id' => $menuItem->transNo,
                    'price' => $price,
                ]);
            }
    
            // Save the images (menu_item_images)
            foreach ($imagePaths as $imagePath) {
                MenuItemImage::create([
                    'menu_item_id' => $menuItem->transNo,
                    'image_url' => $imagePath,
                ]);
            }
    
            // Save the description images (menu_item_desc_images)
            if (isset($validated['desc_images'])) {
                foreach ($validated['desc_images'] as $descImage) {
                    MenuItemDescImage::create([
                        'menu_item_id' => $menuItem->transNo,
                        'desc_text' => $descImage,
                    ]);
                }
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Menu item saved successfully!',
            ]);
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json([
                'success' => false,
                'message' => 'Failed to save menu item. ' . $e->getMessage(),
            ]);
        }
    }
    

    public function getAllModules()
    {
        try {
            // Retrieve all menu items with only the required fields
            $menuItems = MenuItem::select('transNo', 'module', 'description', 'created_at', 'updated_at')
                ->get()
                ->map(function ($item) {
                    return [
                        'transNo' => $item->transNo,
                        'module' => $item->module,
                        'description' => $item->description,
                        'created_at' => Carbon::parse($item->created_at)->format('m/d/Y H:i:s'), // Format datetime
                        'updated_at' => Carbon::parse($item->updated_at)->format('m/d/Y H:i:s'),
                    ];
                });
    
            if ($menuItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No menu items found.',
                ], 404);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Menu items retrieved successfully.',
                'data' => $menuItems,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve menu items. ' . $e->getMessage(),
            ], 500);
        }
    }
    
}
