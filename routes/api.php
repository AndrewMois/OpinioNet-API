<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\MicropostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
//The restriction applies to the GET request for the /user endpoint. It allows authenticated users to retrieve their own information.
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/users', [UserController::class, 'index']);

//upproteted routes
Route::apiResource('/users', 'App\Http\Controllers\UserController');
Route::put('/microposts/{id}/addLikes', [MicropostController::class, 'addLikes']);
Route::apiResource('/microposts', 'App\Http\Controllers\MicropostController');

//protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Route::apiResource('/users', 'App\Http\Controllers\UserController');
    // Route::put('/microposts/{id}/addLikes', [MicropostController::class, 'addLikes']);
    //It depends on http action and intended function in the controller. Need to discuss.
    // Route::apiResource('/microposts', 'App\Http\Controllers\MicropostController');
});
