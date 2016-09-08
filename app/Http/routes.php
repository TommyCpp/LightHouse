<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::group(['middleware'=>'auth'],function(){
    Route::get('/home', 'HomeController@index');
    Route::get('user-archive','UserArchiveController@showArchiveForm');
    Route::post('user-archive','UserArchiveController@addOrUpdate');
});

Route::group(['middleware'=>['auth','role:ADMIN']],function(){
    Route::get('user-management','UserController@userManage');
    Route::get('user-management/{id}','UserController@showUserManageForm');
    Route::post('user-management/{id}','UserController@editUserInformation');
});

Route::group(['middleware'=>['auth','role:AT|OT']],function(){
    Route::get('committees','CommitteeController@index');
    Route::get('create-committee','CommitteeController@showCreateForm');
    
});

