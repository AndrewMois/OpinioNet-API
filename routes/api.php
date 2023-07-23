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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/users', [UserController::class, 'index']);
Route::apiResource('/users', 'App\Http\Controllers\UserController');
Route::apiResource('/microposts', 'App\Http\Controllers\MicropostController');
Route::put('/microposts/{id}/addLikes', [MicropostController::class, 'addLikes']);
