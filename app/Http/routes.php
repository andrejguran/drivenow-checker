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

Route::get('auth/login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', ['as' => 'register', 'uses' => 'Auth\AuthController@getRegister']);
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::get('settings', ['as' => 'settings', 'uses' => 'HomeController@settings']);
Route::post('settings', 'HomeController@postSettings');

Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);
Route::get('/check/{id}', ['as' => 'check', 'uses' => 'HomeController@check']);

Route::post('/watcher', 'HomeController@createWatcher');

Route::get('/toggle/{id}', 'HomeController@toggle');
Route::get('/delete/{id}', 'HomeController@delete');