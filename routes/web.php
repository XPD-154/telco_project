<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\emailController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::get('/', function () {
    return view('index');
});
*/

//homepage route
Route::get('/', [emailController::class, 'email']);

//mail route
Route::get('send_mail', function (Request $request) {
   
    if($request['header']){
        $header = $request['header'];
    }else{
        $header = "Welcome to UNIVASA";
    }

    if($request['message']){
        $message = $request['message'];
    }else{
        $message = "Thank you for signing up with Univasa, We hope you enjoy your time with us. Check out some of our newest products below or click on the button below to visit us";
    }

    if($request['toEmail']){
        $email = $request['toEmail'];
    }else{
        $email = 'ambassadorj.boy@gmail.com';
    }

    if($request['subject']){
        $subject = $request['subject'];
    }else{
        $subject = 'Univasa LCC';
    }

    $details = [
        'title' => $header,
        'body' => $message
    ];
   
    \Mail::to($email)->send(new \App\Mail\sendMail($details, $subject));
    dd("Email is Sent.");
});





