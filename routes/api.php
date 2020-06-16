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

// Create new property
Route::post('property', 'PropertyController@addProperty');
// Create new analytic
Route::post('analytic', 'PropertyController@analytic');
// Update an analytic
Route::patch('analytic', 'PropertyController@analytic');
// Get all analytics for an inputted property
Route::get('analytics', 'PropertyController@getAnalytics');
