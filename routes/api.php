<?php

use App\Http\Controllers\OpinionController;
use App\Http\Controllers\CritiqueController;
use App\Http\Controllers\CritiqueOpinionController;
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

Route::apiResource('critiques', CritiqueController::class);

Route::apiResource('critiques.opinions', CritiqueOpinionController::class)->shallow()->only(['index', 'store']);
Route::apiResource('opinions', OpinionController::class)->only(['show', 'update', 'destroy']);

Route::apiResource('topics', TopicController::class);
