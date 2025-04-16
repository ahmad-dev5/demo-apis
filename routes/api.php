<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Diary\DiaryController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\API\Reminders\ReminderController;
use App\Http\Controllers\API\Reminders\ReminderListController;

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

Route::prefix('auth')->group(function () {
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('diaries', DiaryController::class);
    Route::apiResource('reminder-lists', ReminderListController::class);
    Route::apiResource('reminders', ReminderController::class);

});