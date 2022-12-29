<?php

use App\Http\Controllers\expert\AppointmentController;
use App\Http\Controllers\expert\AuthController;
use App\Http\Controllers\expert\MessageController;
use App\Http\Controllers\expert\RateController;
use App\Http\Controllers\user\AppointmentController as UserAppointmentController;
use App\Http\Controllers\user\AuthController as UserAuthController;
use App\Http\Controllers\user\CommentController;
use App\Http\Controllers\user\FavoriteController;
use App\Http\Controllers\user\IndexController;
use App\Http\Controllers\user\MessageController as UserMessageController;
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



Route::post('/user/register', [UserAuthController::class , 'register']);
Route::post('/user/login', [UserAuthController::class , 'login']);

Route::middleware('role:user')->group(function () {
    Route::post('/user/logout', [UserAuthController::class , 'logout']);
    Route::post('/user/details', [UserAuthController::class, 'details']);
    Route::post('user/edit', [UserAuthController::class, 'editDetails']);
    Route::get('/user/categories', [IndexController::class , 'indexCategory']);
    Route::get('/user/experts/{id}', [IndexController::class , 'indexExperts']);
    Route::post('/user/search', [IndexController::class , 'indexExperts']);
    Route::get('user/expert_deatails/{id}',[IndexController::class , 'expertDetails']);
    Route::get('/user/messages/{expert_id}', [UserMessageController::class , 'indexMessages']);
    Route::post('user/messages/send/{expert_id}', [UserMessageController::class , 'sendMessage']);
    Route::get('user/comments_reviews/{expert_id}', [CommentController::class , 'getCommentsAndReviews']);
    Route::post('user/comments_reviews/add/{expert_id}', [CommentController::class , 'comment']);
    Route::post('user/comments_reviews/avg/{expert_id}', [CommentController::class , 'totalRate']);
    Route::delete('user/comment_reviews/delete/{comment_id}',[CommentController::class , 'deleteComment']);
    Route::post('user/favorite', [FavoriteController::class , 'indexFavorite']);
    Route::post('user/favorite/add/{expert_id}',[FavoriteController::class , 'addToFavorite']);
    Route::delete('user/favorite/delete/{expert_id}', [FavoriteController::class , 'removeFavorite']);
    Route::get('user/appointments/get/{expert_id}', [UserAppointmentController::class , 'getAppointments']);
    Route::post('user/appointment/add/{expert_id}',[UserAppointmentController::class , 'setAppointment']);
});


Route::post('/expert/register', [AuthController::class, 'register']);
Route::post('/expert/login', [AuthController::class, 'login']);

Route::middleware('role:expert')->group(function () {
    Route::post('/expert/logout', [AuthController::class, 'logout']);
    Route::post('/expert/details', [AuthController::class, 'details']);
    Route::post('expert/edit', [AuthController::class, 'editDetails']);
    Route::get('/expert/appointments', [AppointmentController::class, 'getAppointments']);
    Route::get('/expert/appointment_details/{id}', [AppointmentController::class, 'getAppointmentDetails']);
    Route::get('expert/appointment/status/{appointment_id}', [AppointmentController::class, 'changeAppointmentStatus']);
    Route::get('/expert/messages/{user_id}', [MessageController::class, 'indexMessages']);
    Route::post('expert/messages/send/{user_id}', [MessageController::class, 'sendMessage']);
    Route::get('expert/comments_reviews', [RateController::class, 'getCommentsAndReviews']);
    Route::post('expert/comments_reviews/avg', [RateController::class, 'totalRate']);
});
