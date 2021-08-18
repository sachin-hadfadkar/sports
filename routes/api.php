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

Route::group(['middleware' => ['auth:api', 'admin']], function() {
    Route::post('create_team', 'Api\SportsController@createTeam')->name('create_team');
    Route::post('create_player', 'Api\SportsController@createPlayer')->name('create_player');
    Route::post('update_player', 'Api\SportsController@updatePlayer')->name('update_player');
    Route::post('update_team', 'Api\SportsController@updateTeam')->name('update_team');
    Route::post('delete_player', 'Api\SportsController@deletePlayer')->name('delete_player');
    Route::post('disable_player', 'Api\SportsController@disablePlayer')->name('disable_player');
    Route::post('delete_team', 'Api\SportsController@deleteTeam')->name('delete_team');
    Route::post('disable_team', 'Api\SportsController@disableTeam')->name('disable_team');
 });

Route::post('login', 'Api\AuthController@login')->name('login');
Route::get('team_list', 'Api\SportsController@index')->name('team_list');
Route::get('team_players/{param}', 'Api\SportsController@teamPlayersList')->name('team_players');
Route::get('player/{param}', 'Api\SportsController@playerList')->name('player');

Route::post('register', 'Api\AuthController@register')->name('register');
Route::post('logout', 'Api\AuthController@register')->name('logout');