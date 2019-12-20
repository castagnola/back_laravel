<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
/**
 * OAuth Routes
 */
Route::post('/login','OAuth\AuthController@login');
Route::post('/register','OAuth\AuthController@login');
Route::get('/redirecTo','OAuth\AuthController@redirecTo');
Route::middleware('auth:api')->post('/logout','OAuth\AuthController@logout');

/**
 * API Routes
 */

Route::apiResources(['user' => 'API\UserController']);
Route::get('profile','API\UserController@profile');
Route::put('profile','API\UserController@updateProfile');

/**
 * Api Resource Routes
 */

Route::resource('get-roles','ResourceController\RolesController');
