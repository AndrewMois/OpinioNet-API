<?php

use App\Http\Controllers\VoteController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MicropostController;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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

// Force HTTPS on production
if (App::environment('production')) {
    URL::forceScheme('https');
}

//The restriction applies to the GET request for the /user endpoint. It allows authenticated users to retrieve their own information.
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




// Route::get('/users', [UserController::class, 'index']);

//unproteted routes
//To show microposts for a specifc user
// Route::get('/users/{id}/microposts', [UserController::class, 'userShowMicroposts']);
Route::apiResource('/users', 'App\Http\Controllers\UserController');
Route::put('/microposts/{id}/addLikes', [MicropostController::class, 'addLikes']);
Route::get('/microposts/{id}/likes', [LikeController::class, 'index']);
Route::post('/microposts/{id}/likes', [LikeController::class, 'store']);
Route::delete('/microposts/{id}/likes', [LikeController::class, 'destroy']);
Route::get('/microposts/{id}/votes', [VoteController::class, 'index']);
Route::post('/microposts/{id}/votes', [VoteController::class, 'store']);
Route::apiResource('/microposts', 'App\Http\Controllers\MicropostController');


//Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users/{id}/microposts', [UserController::class, 'userShowMicroposts']);
    Route::delete('/microposts/{id}', [MicropostController::class, 'destroy']);
    //This works if user have a token. But this should work for a specific toke(user) not for all token.

    //    Route::apiResource('/users', 'App\Http\Controllers\UserController');
    // Route::put('/microposts/{id}/addLikes', [MicropostController::class, 'addLikes']);
    //It depends on http action and intended function in the controller. Need to discuss.
});
