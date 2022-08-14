<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WatchdogController;

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
Route::get('getOldPullRequests',[WatchdogController::class,'getOldPullRequests']);
Route::get('getRRPullRequests',[WatchdogController::class,'getRRPullRequests']);
Route::get('getSuccessPullRequests',[WatchdogController::class,'getSuccessPullRequests']);
Route::get('getUnassignedPullRequests',[WatchdogController::class,'getUnassignedPullRequests']);

