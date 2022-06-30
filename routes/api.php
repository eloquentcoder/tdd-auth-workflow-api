<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Dashboard\HomeController;
use App\Http\Controllers\Api\Customer\Auth\LoginController;
use App\Http\Controllers\Api\Customer\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;

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

Route::get('home', [HomeController::class, 'index']);
Route::post('forgot-password', ForgotPasswordController::class);
Route::post('reset-password', ResetPasswordController::class);

Route::prefix('customer')->group(function () {
    Route::post('login', LoginController::class);
    Route::post('register', RegisterController::class);
});


// Route::prefix('customer', function() {
//     Route::post('login', [LoginController::class, 'index']);
// });


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
