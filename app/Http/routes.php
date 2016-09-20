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

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index');
    Route::get('user-archive', 'UserArchiveController@showArchiveForm');
    Route::post('user-archive', 'UserArchiveController@addOrUpdate');
});

Route::group(['middleware' => ['auth', 'role:ADMIN|OT']], function () {
    Route::get('users', 'UserController@userManage');
    Route::get('user/{id}', 'UserController@showUserManageForm');
    Route::post('user/{id}', 'UserController@editUserInformation');
    Route::delete('user/{id}', "UserController@deleteUser");
});

Route::group(['middleware' => ['auth', 'role:AT|OT']], function () {
    Route::get('committees', 'CommitteeController@index');
    Route::get('create-committee', 'CommitteeController@showCreateForm');
    Route::post('create-committee', 'CommitteeController@create');
    Route::get('committee/{id}/note', "CommitteeController@getNote");
    Route::get('committee/{id}/edit', "CommitteeController@showUpdateForm");
    Route::delete('committee/{id}', "CommitteeController@delete");
    Route::put('committee/{id}', "CommitteeController@update");
});

Route::group(['middleware' => ['auth', 'role:OT']], function () {
    Route::get("delegations", "DelegationController@delegations");
    Route::get('create-delegation', 'DelegationController@showCreateForm');
    Route::post("create-delegation", "DelegationController@create");
    Route::get("delegations", "DelegationController@showDelegations");
    Route::delete('delegation/{id}', "DelegationController@delete");
    Route::put('delegation/{id}', "DelegationController@edit");
    Route::get('delegation/{id}/edit', "DelegationController@showUpdateForm");
    Route::get('committees/limit', "DelegationController@showCommitteesLimitForm");
    Route::post('committees/limit', "DelegationController@updateCommitteeLimit");
});

Route::group(['middleware' => ['auth', 'role:HEADDEL']], function () {
    Route::get('delegation/{id}', "DelegationController@showDelegationInformation");


    Route::get('committee/{id}/seats', "CommitteeController@getSeats");
});

