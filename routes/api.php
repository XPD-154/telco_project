<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    authController,
    usageController,
    activateController,
    planPurchaseController,
    changePlanController,
};

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::put('/change_password', [AuthController::class, 'newPassword']);
Route::post('/me', [AuthController::class, 'me']);

Route::group(['middleware' => ['jwt.verify']], function() {

    Route::get('/usage', [usageController::class, 'usage']);
    Route::get('/activate', [activateController::class, 'activate']);
    Route::get('/purchase', [planPurchaseController::class, 'purchase']);
    Route::get('/change_plan', [changePlanController::class, 'changePlan']);
});