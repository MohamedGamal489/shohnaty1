<?php

use App\Http\Controllers\Api\User\{
    AuthController,OrderController,ProfileController
};

use App\Http\Controllers\Api\Driver\{
    AuthDriverController,OrderDriverController,ProfileDriverController
};
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

// routes which don't need to be auth
Route::prefix('user')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('reset', [AuthController::class, 'resetPassword']);

    Route::group(['middleware' => 'auth:sanctum'], function () {

        Route::post('logout', [AuthController::class, 'logout']);
        // profile
        Route::get('show_profile', [ProfileController::class, 'show']);
        Route::post('update_profile', [ProfileController::class, 'update']);
        Route::post('update_profile_picture', [ProfileController::class, 'updatePicture']);
        Route::post('change_password', [ProfileController::class, 'changePassword']);
        Route::get('delete_account', [ProfileController::class, 'deleteAccount']);

        // create orders
        Route::get('list_orders', [OrderController::class, 'index']);
        Route::post('store_order', [OrderController::class, 'store']);
        Route::get('show_order/{id}', [OrderController::class, 'show']);
        Route::get('delete_order/{id}', [OrderController::class, 'delete']);
    });
});

Route::prefix('driver')->group(function () {
    Route::post('register', [AuthDriverController::class, 'register']);
    Route::post('complete_register', [AuthDriverController::class, 'completeRegistration']);
    Route::post('login', [AuthDriverController::class, 'login']);
    Route::post('reset', [AuthDriverController::class, 'resetPassword']);

    Route::group(['middleware' => 'auth:sanctum'], function () {

        Route::post('logout', [AuthDriverController::class, 'logout']);
        // profile
        Route::get('show_profile', [ProfileDriverController::class, 'show']);
        Route::post('update_profile', [ProfileDriverController::class, 'update']);
        Route::post('update_profile_picture', [ProfileDriverController::class, 'updatePicture']);
        Route::post('change_password', [ProfileDriverController::class, 'changePassword']);
        Route::get('delete_account', [ProfileDriverController::class, 'deleteAccount']);

        // create orders
        Route::get('new_orders', [OrderDriverController::class, 'index']);
        Route::get('get_orders', [OrderDriverController::class, 'getOrders']);
        Route::get('assign/{order_id}', [OrderDriverController::class, 'assignOrder']);
        Route::get('complete/{order_id}', [OrderDriverController::class, 'completeOrder']);
        Route::get('wallet', [OrderDriverController::class, 'getWalletBalance']);
    });
});

