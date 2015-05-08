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

Route::get('/', 'UserController@index');

Route::get('/home', 'UserController@home');

Route::get('/login', 'UserController@displayLogin');

Route::post('/login', 'UserController@authorize');

Route::get('logout', 'UserController@logout');

Route::post('/join', 'UserController@createUser');

Route::get('/results', 'YelpResultsController@displaySearchResults');

Route::get('/business/{business_id}', 'BusinessResultsController@displayBusinessDetails');

Route::post('/username-suggestion/{business_id}', 'BusinessResultsController@addUsernameSuggestion');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
