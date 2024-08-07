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

Route::post('/registerxxxxxxxx', [AuthController::class, 'register']);
Route::post('/loginxxxxxxxxxx', [AuthController::class, 'login']);
Route::put('/change_passwordxxxxxxxxxx', [AuthController::class, 'newPassword']);
Route::post('/mexxxxxxxxxxxxx', [AuthController::class, 'me']);

Route::group(['middleware' => ['basic.auth']], function() {

    Route::post('/usage', [usageController::class, 'usage']);
    Route::post('/activate', [activateController::class, 'activate']);
    Route::post('/purchase', [planPurchaseController::class, 'purchase']);
    Route::post('/change_plan', [changePlanController::class, 'changePlan']);
});