<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\tokenVerificationMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/registration',[UserController::class,'registration']);
Route::post('/login',[UserController::class,'login']);
Route::post('/sendOpt',[UserController::class,'sendOpt']);
Route::post('/verifyOtp',[UserController::class,'verifyOtp']);
Route::post('/resetPassword',[UserController::class,'resetPassword'])
->middleware([tokenVerificationMiddleware::class]);