<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes - requires auth key
Route::group(['middleware' => ['auth:sanctum']], function() {
    // A test function for tokens
    Route::get('authentication_test', function() {
        return 'Authenticated';
    });

    // User routes
    Route::get('/user/accounts', [UserController::class, 'accounts']);

    // Account Routes
    Route::post('/account/create', [AccountController::class, 'create']);
    Route::get('/account/users/{account_id}', [AccountController::class, 'users']);
    Route::get('/account/{account_id}', [AccountController::class, 'show']);
    Route::post('/account/{account_id}/assign_user/{user_id}', [AccountController::class, 'assign_user_to_account']);

    // Authentication Routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});
