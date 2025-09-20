<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'auth'],function($router){
    
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/profile',[AuthController::class,'profile']);
    Route::post('/refresh',[AuthController::class,'refresh']);
    Route::post('/reset', [AuthController::class,'reset_password']);
 });
 