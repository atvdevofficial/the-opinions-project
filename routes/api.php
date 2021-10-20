<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\OpinionController;
use App\Http\Controllers\CritiqueController;
use App\Http\Controllers\CritiqueOpinionController;
use App\Http\Controllers\CritiqueTopicController;
use App\Http\Controllers\FollowCritiqueController;
use App\Http\Controllers\TopicController;
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

Route::group(['middleware' => 'throttle:120'], function () {
    // Registration
    // Reset Password
    // Forgot / Change Password
    Route::post('/login', [AuthenticationController::class, 'login'])
        ->name('login');

    /**
     * Authenticated Routes
     */
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/logout', [AuthenticationController::class, 'logout'])
            ->name('logout');

        Route::apiResource('critiques', CritiqueController::class);

        Route::apiResource('critiques.opinions', CritiqueOpinionController::class)
            ->shallow()->only(['index', 'store']);

        Route::apiResource('opinions', OpinionController::class)
            ->only(['show', 'update', 'destroy']);

        Route::apiResource('topics', TopicController::class);

        Route::get('critiques/follows/critiques', [FollowCritiqueController::class, 'index'])
            ->name('follows.critiques.index');

        Route::put('critiques/follows/critiques/{critique}/follow', [FollowCritiqueController::class, 'follow'])
            ->name('follows.critiques.follow');

        Route::put('critiques/follows/critiques/{critique}/unfollow', [FollowCritiqueController::class, 'unfollow'])
            ->name('follows.critiques.unfollow');

        Route::get('critiques/follows/topics', [CritiqueTopicController::class, 'index'])
            ->name('follows.topics.index');

        Route::put('critiques/follows/topics/{topic}/follow', [CritiqueTopicController::class, 'follow'])
            ->name('follows.topics.follow');

        Route::put('critiques/follows/topics/{topic}/unfollow', [CritiqueTopicController::class, 'unfollow'])
            ->name('follows.topics.unfollow');
    });
});
