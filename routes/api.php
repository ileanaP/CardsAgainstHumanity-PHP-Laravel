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
Route::post('games/enter', 'Game\GameController@enterGame');
Route::resource('games', 'Game\GameController', ['only' => ['index', 'show', 'destroy', 'store']]);

Route::resource('games.rounds', 'Game\GameRoundController', ['only' => ['index', 'show', 'store']]);
Route::resource('games.users', 'Game\GameUserController', ['only' => ['index', 'show']]);
Route::post('games/{gameId}/users/{userId}/remove', 'Game\GameUserController@remove');
Route::post('games/{gameId}/users/{userId}/confirm', 'Game\GameUserController@confirm');
Route::post('games/{gameId}/pingPlayersToConfirm', 'Game\GameController@pingPlayersToConfirm');

Route::resource('users.rounds', 'User\UserRoundController', ['only' => ['index', 'show']]);

Route::get('cards/{cardIds}', 'CardController@showCards');

Route::resource('rounds', 'RoundController', ['only' => ['index', 'store', 'show', 'destroy']]);
Route::resource('cardsets', 'CardsetController', ['only' => ['index']]);

Route::post('register', 'User\UserController@register');
Route::post('login', 'User\UserController@login');

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('details', 'User\UserController@details');
});