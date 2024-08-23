<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    authController,
    usageController,
    activateController,
    planPurchaseController,
    changePlanController,
    uploadController,
    serviceInfoController,
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

Route::group(['middleware' => ['basic.auth']], function() {

    Route::post('/usage', [usageController::class, 'usage']);
    Route::post('/activate', [activateController::class, 'activate']);
    Route::post('/purchase', [planPurchaseController::class, 'purchase']);
    Route::post('/change_plan', [changePlanController::class, 'changePlan']);
    Route::post('/service_info', [serviceInfoController::class, 'info']);
});

Route::post('/upload', [uploadController::class, 'import']);

//mail route
Route::get('/send_mail', function (Request $request) {
    
    if($request['header']){
        $header = $request['header'];
    }else{
        return ["status"=>"error", "message"=>"header missing"];
    }

    if($request['message']){
        $message = $request['message'];
    }else{
        return ["status"=>"error", "message"=>"message missing"];
    }

    if($request['toEmail']){
        $email = $request['toEmail'];
    }else{
        return ["status"=>"error", "message"=>"email missing"];
    }

    if($request['subject']){
        $subject = $request['subject'];
    }else{
        return ["status"=>"error", "message"=>"subject missing"];
    }

    $details = [
        'title' => $header,
        'body' => $message
    ];
   
    \Mail::to($email)->send(new \App\Mail\sendMail($details, $subject));
    //dd("Email is Sent.");
    return ["status"=>"success", "message"=>"Email is sent"];
});