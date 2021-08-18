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
    Route::post('register', 'Api\AuthController@register')->name('register');
    Route::post('team/create', 'Api\SportsController@createTeam');
    Route::post('team/update', 'Api\SportsController@updateTeam')->name('update_team');
    Route::post('team/delete', 'Api\SportsController@deleteTeam')->name('delete_team');
    Route::post('team/disable', 'Api\SportsController@disableTeam')->name('disable_team');
    Route::post('player/create', 'Api\SportsController@createPlayer')->name('create_player');
    Route::post('player/update', 'Api\SportsController@updatePlayer')->name('update_player');
    Route::post('player/delete', 'Api\SportsController@deletePlayer')->name('delete_player');
    Route::post('player/disable', 'Api\SportsController@disablePlayer')->name('disable_player');
 });

Route::post('login', 'Api\AuthController@login')->name('login');
Route::post('logout', 'Api\AuthController@register')->name('logout');

Route::get('team/list', 'Api\SportsController@index')->name('team_list');
Route::get('team/players/{param}', 'Api\SportsController@teamPlayersList')->name('team_players');
Route::get('player/{param}', 'Api\SportsController@playerList')->name('player');
