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
Route::post('login', 'UserController@login');
Route::post('checkemail', 'UserController@checkEmail');
Route::get('getallcars', 'CarController@getallcars');

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::get('userdetail', 'UserController@getAuthenticatedUser');
    
    Route::get('user/cars', 'CarController@getUserCreatedCars');
    Route::get('car/{id}/detail', 'CarController@getCarDetail');
    Route::post('car/create', 'CarController@createCar');
    Route::post('car/update', 'CarController@updateCar');
    Route::delete('car/{id}/delete', 'CarController@deleteCar');
    
    
});
