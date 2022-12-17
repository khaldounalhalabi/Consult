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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::namespace('App\Http\Controllers')->group(function () {

    Route::post('/user/register', 'UserController@register');
    Route::post('/user/login', 'UserController@login');
    Route::get('/user/categories', 'UserController@indexCategory');
    Route::get('/user/experts/{id}', 'UserController@indexExperts');
    Route::post('/user/search', 'UserController@search');
    Route::get('user/expert_deatails/{id}', 'UserController@expertDetails');


    Route::post('/expert/register', 'ExpertController@register');
    Route::post('/expert/login', 'ExpertController@login');
    Route::get('/expert/appointments', 'ExpertController@getAppointments');
    Route::get('/expert/appointment_details/{id}', 'ExpertController@getAppointmentDetails');

});
