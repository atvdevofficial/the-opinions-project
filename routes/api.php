<?php

use App\Http\Controllers\OpinionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileOpinionController;
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

Route::apiResource('profiles', ProfileController::class);

Route::apiResource('profiles.opinions', ProfileOpinionController::class)->shallow()->only(['index', 'store']);
Route::apiResource('opinions', OpinionController::class)->only(['show', 'update', 'destroy']);
