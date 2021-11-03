<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\OpinionController;
use App\Http\Controllers\CritiqueController;
use App\Http\Controllers\CritiqueMessageController;
use App\Http\Controllers\CritiqueOpinionController;
use App\Http\Controllers\CritiqueTopicController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FollowCritiqueController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SearchController;
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
    // Reset Password
    // Forgot / Change Password

    // Login
    Route::post('/login', [AuthenticationController::class, 'login'])
        ->name('login');

    // Register
    Route::post('/registration', RegistrationController::class)
        ->name('register');

    /**
     * Authenticated Routes
     */
    Route::group(['middleware' => ['auth:sanctum']], function () {

        // Logout
        Route::post('/logout', [AuthenticationController::class, 'logout'])
            ->name('logout');

        // Change Password
        Route::put('/changePassword', [AuthenticationController::class, 'changePassword'])
            ->name('changePassword');

        // Feed
        Route::get('feed', FeedController::class)
            ->name('feed');

        // Search
        Route::get('search', SearchController::class)
            ->name('search');

        // Critique Statistics (Followers, Followings, etc.)
        Route::get('/critiques/{critique}/statistics', [CritiqueController::class, 'statistics'])
            ->name('critiques.statistics');

        // Critiques
        Route::apiResource('critiques', CritiqueController::class);

        // Critique Messages
        Route::apiResource('critiques.messages', CritiqueMessageController::class)
            ->shallow()->only(['index', 'store']);

        // Critiques Opinions
        Route::apiResource('critiques.opinions', CritiqueOpinionController::class)
            ->shallow()->only(['index', 'store']);
        // Critique Like Opinion
        Route::post('/opinions/{opinion}/like', [OpinionController::class, 'like'])
            ->name('opinions.like');
        // Critique Unlike Opinion
        Route::post('/opinions/{opinion}/unlike', [OpinionController::class, 'unlike'])
            ->name('opinions.unlike');
        // Opinions
        Route::apiResource('opinions', OpinionController::class)
            ->only(['show', 'update', 'destroy']);

        // Top / Trending topics
        Route::get('/topics/topTrending', [TopicController::class, 'topTrending'])
            ->name('topics.topTrending');
        // Topics
        Route::apiResource('topics', TopicController::class);

        // Critique Followers and Followings
        Route::get('critiques/follows/critiques', [FollowCritiqueController::class, 'index'])
            ->name('follows.critiques.index');
        // Follow Critique
        Route::put('critiques/follows/critiques/{critique}/follow', [FollowCritiqueController::class, 'follow'])
            ->name('follows.critiques.follow');
        // Unfollow Critique
        Route::put('critiques/follows/critiques/{critique}/unfollow', [FollowCritiqueController::class, 'unfollow'])
            ->name('follows.critiques.unfollow');

        // Critique Followed Topics
        Route::get('critiques/{critique}/follows/topics', [CritiqueTopicController::class, 'index'])
            ->name('follows.topics.index');
        // Update Followed Topics
        Route::put('critiques/{critique}/follows/topics', [CritiqueTopicController::class, 'update'])
            ->name('follows.topics.update');
        // Critique Follow Topic
        Route::put('critiques/{critique}/follows/topics/{topic}/follow', [CritiqueTopicController::class, 'follow'])
            ->name('follows.topics.follow');
        // Critique Unfollow Topic
        Route::put('critiques/{critique}/follows/topics/{topic}/unfollow', [CritiqueTopicController::class, 'unfollow'])
            ->name('follows.topics.unfollow');
    });
});
