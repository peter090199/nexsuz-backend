<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\Auth\ProfilepictureController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/uploads/{path}', function ($path) {
    return response()->file(storage_path('app/uploads/' . $path));
})->where('path', '.*');

// Route::resource('profiles',ProfileController::class)->names('profiles');

Route::resource('profiles',ProfilepictureController::class)->names('profiles');