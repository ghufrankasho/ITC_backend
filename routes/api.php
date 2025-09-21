<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

 
 

Route::controller(AuthController::class)->prefix('admin')->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('me', 'me');
    Route::post('reset_password', 'resetPassword');
});
Route::group(['middleware'=>'auth:api','prefix'=>'admin'],function($router){

    Route::apiResource('categories', CategoryController::class);

    
});