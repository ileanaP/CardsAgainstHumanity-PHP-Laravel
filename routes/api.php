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

/**
 * Games
 */
Route::resource('games', 'Game\GameController', ['only' => ['index', 'show']]);

Route::resource('games.rounds', 'Game\GameRoundController', ['only' => ['index', 'show', 'store']]);

Route::get('cards/{cardIds}', 'CardController@showCards');

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('details', 'UserController@details');
});