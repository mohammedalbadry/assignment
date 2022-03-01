<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//site settings
Route::get('settings',"SettingController@index");

//user auth
Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('forgotpassword', 'AuthController@ForgotPassword');
    Route::post('reset_password/{token}', 'AuthController@ResetPassword');

    Route::post('logout', 'AuthController@logout');
    Route::post('me', 'AuthController@me');
});
//get enduser data
Route::get('gettimetables',"EnduserController@gettimetable");
Route::post('profile',"EnduserController@update");

//admin auth
Route::group(['middleware' => 'api','prefix' => 'admin'], function ($router) {
    Route::post('login', 'AdminAuthController@login');
    Route::post('forgotpassword', 'AdminAuthController@ForgotPassword');
    Route::post('reset_password/{token}', 'AdminAuthController@ResetPassword');

    Route::post('logout', 'AdminAuthController@logout');
    Route::post('me', 'AdminAuthController@me');
});
 
//admin panal
Route::group(['middleware' =>  ['api', 'auth:admin']], function(){
    Route::get('home',"HomeController@index");
    Route::post('settings',"SettingController@update");

    Route::post('adminprofile',"AdminProfileController@update");

    Route::get('permission',"PermissionController@index");
    Route::get('allroles', "RoleController@allroles");    

    Route::apiResource('roles', RoleController::class);    
    Route::apiResource('timetables', TimetableController::class);    
    Route::apiResource('admins', AdminController::class);    
    Route::apiResource('users', UserController::class);    
    Route::apiResource('pharmacies', PharmacyController::class);   
});
