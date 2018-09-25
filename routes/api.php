<?php

use Illuminate\Http\Request;

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

Route::get('/podcasts', 'PodcastController@index')->name('podcast');

Route::get('/podcasts/{slug}', 'EpisodeController@index')->name('episode');
Route::post('/podcasts/{slug}/episodes/new', 'EpisodeController@create')->name('episodeCreate');