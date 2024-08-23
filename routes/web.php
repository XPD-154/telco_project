<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\emailController;
use App\Http\Controllers\uploadController;
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

Route::get('/upload', [uploadController::class, 'index']);

Route::post('/upload_csv', [uploadController::class, 'import']);





