<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//user
Route::post('login', 'Api\AuthController@login');
Route::post('register', 'Api\AuthController@register');
Route::get('logout', 'Api\AuthController@logout');
Route::post('save_user_info', 'Api\AuthController@saveUserInfo');
Route::get('prodi', 'Api\AuthController@prodi');
Route::get('fakultas', 'Api\AuthController@fakultas');

//post SKP
Route::post('posts/create', 'Api\PostsController@create')->middleware('jwtAuth');
Route::post('posts/delete', 'Api\PostsController@delete')->middleware('jwtAuth');
Route::post('posts/update', 'Api\PostsController@update')->middleware('jwtAuth');
Route::get('posts', 'Api\PostsController@posts')->middleware('jwtAuth');