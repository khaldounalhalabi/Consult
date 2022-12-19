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
    Route::post('/user/logout', 'UserController@logout');
    Route::post('/user/details', 'UserController@details');
    Route::get('/user/categories', 'UserController@indexCategory');
    Route::get('/user/experts/{id}', 'UserController@indexExperts');
    Route::post('/user/search', 'UserController@search');
    Route::get('user/expert_deatails/{id}', 'UserController@expertDetails');
    Route::get('/user/messages/{expert_id}', 'UserController@indexMessages');
    Route::post('user/messages/send/{expert_id}', 'UserController@sendMessage');
    Route::get ('user/comments_reviews/{expert_id}', 'UserController@getCommentsAndReviews');
    Route::post('user/comments_reviews/add/{expert_id}', 'UserController@comment');
    Route::post('user/comments_reviews/avg/{expert_id}', 'UserController@totalRate');
    Route::post('user/favorite', 'UserController@indexFavorite');
    Route::post('user/favorite/add/{expert_id}', 'UserController@addToFavorite');


    Route::post('/expert/register', 'ExpertController@register');
    Route::post('/expert/login', 'ExpertController@login');
    Route::post('/expert/logout', 'ExpertController@logout');
    Route::post('/expert/details', 'ExpertController@details');
    Route::get('/expert/appointments', 'ExpertController@getAppointments');
    Route::get('/expert/appointment_details/{id}', 'ExpertController@getAppointmentDetails');
    Route::get('/expert/messages/{user_id}', 'ExpertController@indexMessages');
    Route::post('expert/messages/send/{user_id}', 'ExpertController@sendMessage');
    Route::get('expert/comments_reviews', 'ExpertController@getCommentsAndReviews');
    Route::post('expert/comments_reviews/avg', 'ExpertController@totalRate');
    Route::get('expert/appointment/status/{appointment_id}', 'ExpertController@changeAppointmentStatus');


});
