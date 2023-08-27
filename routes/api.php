<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\WorkerAuthController;
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
Route::group([
    'middleware' => ['DbBackup'],
    'prefix' => 'auth/admin'
], function ($router) {
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/register', [AdminAuthController::class, 'register']);
    Route::post('/logout', [AdminAuthController::class, 'logout']);
    Route::post('/refresh', [AdminAuthController::class, 'refresh']);
    Route::get('/user-profile', [AdminAuthController::class, 'userProfile']);
});

Route::group([
    'middleware' => ['DbBackup'],
    'prefix' => 'auth/workers'
], function ($router) {
    Route::post('/login', [WorkerAuthController::class, 'login']);
    Route::post('/register', [WorkerAuthController::class, 'register']);
    Route::post('/logout', [WorkerAuthController::class, 'logout']);
    Route::post('/refresh', [WorkerAuthController::class, 'refresh']);
    Route::get('/user-profile', [WorkerAuthController::class, 'userProfile']);
});

Route::group([
    'middleware' => ['DbBackup'],
    'prefix' => 'auth/clients'
], function ($router) {
    Route::post('/login', [ClientAuthController::class, 'login']);
    Route::post('/register', [ClientAuthController::class, 'register']);
    Route::post('/logout', [ClientAuthController::class, 'logout']);
    Route::post('/refresh', [ClientAuthController::class, 'refresh']);
    Route::get('/user-profile', [ClientAuthController::class, 'userProfile']);
});
