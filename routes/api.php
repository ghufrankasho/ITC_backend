<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SubcategoryController;
use App\Models\Setting;
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
    Route::apiResource('subcategories', SubcategoryController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('news', NewsController::class);
    Route::apiResource('contacts', ContactController::class);
    Route::apiResource('settings', SettingController::class);
    
    Route::get('categories/restore/{id}',[ CategoryController::class,'restore']);
    Route::get('subcategories/restore/{id}',[ SubcategoryController::class,'restore']);  
    Route::get('categories/deletePermanent/{id}',[ CategoryController::class,'deletePermanent']);
    Route::get('subcategories/deletePermanent/{id}',[ SubcategoryController::class,'deletePermanent']);    
    
});

//user api's

Route::post('contacts',[ContactController::class,'store']);