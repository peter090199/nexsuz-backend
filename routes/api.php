<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lookup\LookupController;

use App\Http\Controllers\Auth\LoginController;
use  App\Http\Controllers\Auth\RegisterController;
use  App\Http\Controllers\Auth\ForgetpasswordController;
use  App\Http\Controllers\Auth\ProfileController;
use  App\Http\Controllers\Auth\ProfilepictureController;

use App\Http\Controllers\Accessrolemenu\AccessrolemenuController;

use App\Http\Controllers\System\Menus\MenuController;
use App\Http\Controllers\System\Menus\WebsiteModule;
use App\Http\Controllers\System\Securityroles\SecurityroleController;

use App\Http\Controllers\System\Roles\RoleController;
use App\Http\Controllers\Website\MenuItemController;
use App\Http\Controllers\Website\ModuleTask;
use App\Http\Controllers\Website\ContactController;
use App\Http\Controllers\Website\BlogController;
use App\Http\Controllers\Website\BlogImageController;


use App\Http\Controllers\Select2\SelectController;
use App\Http\Controllers\ImageController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/  

// PUBLIC
Route::post('login',[LoginController::class,'login'])->name('login');

Route::post('loginUsername',[LoginController::class,'loginUsername'])->name('loginUsername');

Route::post('resetpassword',[ForgetpasswordController::class,'resetpassword'])->name('resetpassword');

Route::post('forgetpassword',[ForgetpasswordController::class,'forgetpassword'])->name('forgetpassword');

Route::post('register',[RegisterController::class,'register'])->name('register');

Route::post('accountactivation',[RegisterController::class,'accountactivation'])->name('accountactivation');


//website module public
  Route::prefix('websitemodule')->group(function () {

    // Route to create a new module
    Route::post('create', [WebsiteModule::class, 'createModule']);
    // Route to get all modules
    Route::get('all', [WebsiteModule::class, 'getAllModules']);
    // Route to edit a module by transNo
    Route::put('edit/{transNo}', [WebsiteModule::class, 'editModule']);
    // Route to delete a module by transNo
    Route::delete('delete/{transNo}', [WebsiteModule::class, 'deleteModule']);
    Route::get('allModules', [MenuItemController::class, 'getAllModules']);
    Route::get('getAbout', [ModuleTask::class, 'getAbout']);
    Route::get('get_contact', [ContactController::class, 'get_contact']);
    Route::get('getImagesPublic', [BlogImageController::class, 'getImagesPublic']);
});


Route::middleware(['auth:sanctum','checkstatus'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user(); // Return authenticated user information
    });
    //logout
    Route::post('logout',[LoginController::class,'logout'])->name('logout');

    // PROFILE resource
    Route::resource('profile',ProfileController::class)->names('profile');
    Route::get('user/profile',[ProfileController::class,'userAuth'])->name('user/profile');
    Route::resource('profile_pic',ProfilepictureController::class)->names('profile_pic');

    Route::get('userAccount', [ProfileController::class, 'getUserData']);

    // Accessrolemenu
    // User access to the menu depends on their role. GET 
    Route::Resource('accessmenu',AccessrolemenuController::class)->names('accessmenu');

    //user accounts menu
    Route::Resource('menu',MenuController::class)->names('menu');
    Route::delete('menu/delete/{transNo}', [MenuController::class, 'destroyByTransNo'])->name('menu/delete');
    Route::get('menu/submenus/{transNo}', [MenuController::class, 'getSubmenuByMenuTransNo']);
    Route::post('menu/createMenu', [MenuController::class, 'createMenu']);
    Route::post('menu/submenus', [MenuController::class, 'storeSubmenus']);

    // Security roles
    // security GET , STORE 
    Route::Resource('security',SecurityroleController::class)->names('security');

    //Role
    // role GET,STORE,UPDATE,SHOW
    Route::Resource('role',RoleController::class)->names('role');

    // SELECT2 ALL REQUEST
    Route::post('rolecode',[SelectController::class,'rolecode'])->name('rolecode');

    // lookup information
    Route::get('userlists',[LookupController::class,'userlists'])->name('userlists');

    //website
    Route::post('module', [MenuItemController::class, 'SaveModule']);
    Route::get('getAllModules', [MenuItemController::class, 'getAllModules']);
    Route::post('upload-images', [ImageController::class, 'uploadImages']);

    //about
    Route::post('post', [ModuleTask::class, 'post']);
    Route::get('get', [ModuleTask::class, 'get']);
    Route::delete('delete/{transNo}', [ModuleTask::class, 'delete']);
    Route::put('update_about/{transNo}', [ModuleTask::class, 'update_about']);

    //contacts
    Route::post('post_contact', [ContactController::class, 'post_contact']);
    Route::get('get_contactByRole', [ContactController::class, 'get_contactByRole']);
    Route::put('update_contact/{transNo}', [ContactController::class, 'update_contact']);
    Route::delete('delete_contact/{transNo}', [ContactController::class, 'delete_contact']);

    //blog 
    Route::post('post_blog', [BlogController::class, 'post_blog']);
    Route::get('get_blogByRole', [BlogController::class, 'get_blogByRole']);
    Route::put('update_blog/{transNo}', [BlogController::class, 'update_blog']);
    Route::delete('delete_blog/{transNo}', [BlogController::class, 'delete_blog']);
    //blog image
    Route::post('upload_images', [BlogImageController::class, 'uploadImages']);
    Route::get('get_images', [BlogImageController::class, 'getImages']);
    Route::delete('delete_blogImage/{id}', [BlogImageController::class, 'delete_blogImage']);
    Route::delete('deleteByTransCode/{transCode}', [BlogImageController::class, 'deleteByTransCode']);

    
});

