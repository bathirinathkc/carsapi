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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');
Route::post('checkemail', 'UserController@checkEmail');
Route::get('cars', 'CarController@index');

Route::group(['middleware' => ['jwt.verify']], function () {
    
    Route::get('user/cars', 'CarController@userCars');
    Route::get('car/{id}/edit',        'CarController@edit');
    Route::delete('car/destroy/{id}',      'CarController@destroy');
    Route::post('car/store', 'CarController@store');
    Route::post('car/update', 'CarController@update');

    Route::get('user', 'UserController@getAuthenticatedUser');
});
