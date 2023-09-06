<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
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

Route::middleware('DbBackup')->prefix('auth')->group(function () {

    Route::controller(AdminAuthController::class)->prefix('admin')->group(function () {
        Route::post('/login', 'login');
        Route::post('/register',  'register');
        Route::post('/logout', 'logout');
        Route::post('/refresh','refresh');
        Route::get('/user-profile', 'userProfile');
        Route::post('/edit-profile', 'edit');
    });

    Route::controller(WorkerAuthController::class)->prefix('workers')->group(function () {
        Route::post('/login', 'login');
        Route::post('/register',  'register');
        Route::post('/logout', 'logout');
        Route::post('/refresh','refresh');
        Route::get('/user-profile', 'userProfile');
        Route::post('/edit-profile', 'edit');
    });

    Route::controller(ClientAuthController::class)->prefix('clients')->group(function () {
        Route::post('/login', 'login');
        Route::post('/register',  'register');
        Route::post('/logout', 'logout');
        Route::post('/refresh','refresh');
        Route::get('/user-profile', 'userProfile');
        Route::post('/edit-profile', 'edit');
    });

    Route::controller(PostController::class)->prefix('posts')->group(function () {
        Route::post('/add_post', 'store')->middleware('auth:worker');

        Route::middleware('auth:admin')->group(function () {
            Route::get('/all_posts', 'allPosts');
            Route::post('/one_post/{id}', 'showPost');
            Route::post('/approve_post', 'postStatus');
        });

    });

    Route::controller(NotificationController::class)->prefix('notification')->group(function () {
        Route::post('/all_notification', 'allNotifications');
        Route::post('/unread_notifications', 'unreadNotifications');
        Route::post('/read_all_notifications', 'markAllAsRead');
        Route::post('/read_notification/{id}', 'markAsRead');
        Route::post('/delete_all_notifications', 'deleteAll');
        Route::post('/delete_notification/{id}', 'delete');
    });
});



