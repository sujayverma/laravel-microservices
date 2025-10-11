<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api')->get('/test', function (Request $request) {
    return response()->json(['message' => 'API route working']);
});

// Route::get('/users', [UserController::class, 'index']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => 'auth:api'], function() {
   Route::apiResource('users', UserController::class);
   Route::post('/logout', [AuthController::class, 'logout']);
   Route::get('/profile', [UserController::class, 'profile']);
   Route::post('/change-password', [UserController::class, 'changePassword']);
   Route::post('/update-profile', [UserController::class, 'updateProfile']);
   Route::apiResource('roles', RoleController::class);
   Route::apiResource('products', ProductController::class);
});