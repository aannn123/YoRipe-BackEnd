<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {    
    Route::prefix("blog")->group(function () {
        Route::get('/', [BlogController::class, 'index']);
        Route::post('/', [BlogController::class, 'create']);
        Route::put('/{id}', [BlogController::class, 'update']);
        Route::delete('/{id}', [BlogController::class, 'delete']);
        Route::get('/{id}', [BlogController::class, 'detail']);
    });
    
    Route::middleware('admin:sanctum')->prefix("admin")->group(function () {
        Route::post('/create/user', [AdminController::class, 'createUser']);
    });
    
});
