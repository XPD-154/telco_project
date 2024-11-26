<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendMailv3;
use App\Http\Controllers\{
    authController,
    usageController,
    activateController,
    planPurchaseController,
    changePlanController,
    uploadController,
    serviceInfoController,
    linkedlnLoginController,
    googleLoginController,
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

/*Authenticate url*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::put('/change_password', [AuthController::class, 'newPassword']);
Route::post('/me', [AuthController::class, 'me']);

/*mvno url*/
Route::group(['middleware' => ['basic.auth']], function() {

    Route::post('/usage', [usageController::class, 'usage']);
    Route::post('/activate', [activateController::class, 'activate']);
    Route::post('/purchase', [planPurchaseController::class, 'purchase']);
    Route::post('/change_plan', [changePlanController::class, 'changePlan']);
    Route::post('/service_info', [serviceInfoController::class, 'info']);
});

/*upload url*/
Route::post('/upload', [uploadController::class, 'import']);

/*social auth url*/
Route::get('/linkedin/redirect', [linkedlnLoginController::class, 'redirectToLinkedln']);

Route::get('/linkedin/callback', [linkedlnLoginController::class, 'linkedlnSubmit']);

/*Univasa mail route*/
Route::get('/send_mail', function (Request $request) {
    
    if($request['header']){
        $header = $request['header'];
    }else{
        return response()->json(["status"=>"error", "message"=>"header missing"], 400);
    }

    if($request['message']){
        $message = $request['message'];
    }else{
        return response()->json(["status"=>"error", "message"=>"message missing"], 400);
    }

    if($request['toEmail']){
        $email = $request['toEmail'];
    }else{
        return response()->json(["status"=>"error", "message"=>"email missing"], 400);
    }

    if($request['subject']){
        $subject = $request['subject'];
    }else{
        return response()->json(["status"=>"error", "message"=>"subject missing"], 400);
    }

    $details = [
        'title' => $header,
        'body' => $message
    ];
   
    \Mail::to($email)->send(new \App\Mail\sendMail($details, $subject));
    return response()->json(["status"=>"success", "message"=>"Email is sent"], 200);
});

/*Crosstee mail route*/
Route::get('/cross_send_mail', function (Request $request) {
    
    if($request['header']){
        $header = "";
    }else{
        $header = "";
    }

    if($request['message']){
        $message = $request['message'];
    }else{
        return response()->json(["status"=>"error", "message"=>"message missing"], 400);
    }

    if($request['toEmail']){
        $email = $request['toEmail'];
    }else{
        return response()->json(["status"=>"error", "message"=>"email missing"], 400);
    }

    if($request['subject']){
        $subject = "Transaction Notification Email";
    }else{
        $subject = "Transaction Notification Email";
    }

    $details = [
        'title' => $header,
        'body' => $message
    ];
   
    \Mail::to($email)->send(new \App\Mail\sendMailV2($details, $subject));
    return response()->json(["status"=>"success", "message"=>"Email is sent"], 200);
    
});

/*Extra mail route*/
Route::get('/send_email_v2', function (Request $request) {

    if($request['header']){
        $header = "";
    }else{
        $header = "";
    }

    if($request['message']){
        $message = $request['message'];
    }else{
        return response()->json(["status"=>"error", "message"=>"message missing"], 400);
    }

    if($request['toEmail']){
        $email = $request['toEmail'];
    }else{
        return response()->json(["status"=>"error", "message"=>"email missing"], 400);
    }

    if($request['subject']){
        $subject = "Notification Email";
    }else{
        $subject = "Notification Email";
    }

    $details = [
        'title' => $header,
        'body' => $message
    ];

    Mail::mailer('smtp')
    ->to($email)
    ->send(new sendMailv3($details, $subject));

    return response()->json(["status"=>"success", "message"=>"Email is sent"], 200);

});