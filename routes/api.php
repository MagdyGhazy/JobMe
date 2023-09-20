<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientOrderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\WorkerAuthController;
use App\Models\Client;
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

    Route::prefix('admin')->group(function () {

        Route::controller(AdminAuthController::class)->group(function () {
            Route::post('/login', 'login');
            Route::post('/register',  'register');
            Route::post('/logout', 'logout');
            Route::post('/refresh','refresh');
            Route::get('/user-profile', 'userProfile');
            Route::post('/edit-profile', 'edit');
        });
    });

    Route::prefix('workers')->group(function () {

        Route::controller(WorkerAuthController::class)->group(function () {
            Route::post('/login', 'login');
            Route::post('/register',  'register');
            Route::post('/logout', 'logout');
            Route::post('/refresh','refresh');
            Route::get('/user-profile', 'userProfile');
            Route::post('/edit-profile', 'edit');
        });

        Route::controller(ClientOrderController::class)->prefix('order')->middleware('auth:worker')->group(function () {
            Route::get('/pending', 'workerOrder');
            Route::put('/update-order-status', 'updateStatus');

        });

    });

    Route::prefix('clients')->group(function () {

        Route::controller(ClientAuthController::class)->group(function () {
            Route::post('/login', 'login');
            Route::post('/register',  'register');
            Route::post('/logout', 'logout');
            Route::post('/refresh','refresh');
            Route::get('/user-profile', 'userProfile');
            Route::post('/edit-profile', 'edit');
        });

        Route::controller(ClientOrderController::class)->prefix('order')->middleware('auth:client')->group(function () {
            Route::post('/request', 'order');

        });
    });

    Route::prefix('posts')->group(function () {

        Route::controller(PostController::class)->group(function () {

            Route::middleware('auth:worker')->group(function () {
                Route::post('/add_post', 'store');
            });

            Route::middleware('auth:admin')->group(function () {
                Route::get('/all_posts', 'allPosts');
                Route::post('/one_post/{id}', 'showPost');
                Route::post('/approve_post', 'postStatus');
            });

        });
    });

    Route::prefix('notification')->group(function () {

        Route::controller(NotificationController::class)->group(function () {
            Route::post('/all_notification', 'allNotifications');
            Route::post('/unread_notifications', 'unreadNotifications');
            Route::post('/read_all_notifications', 'markAllAsRead');
            Route::post('/read_notification/{id}', 'markAsRead');
            Route::post('/delete_all_notifications', 'deleteAll');
            Route::post('/delete_notification/{id}', 'delete');
        });
    });
});



