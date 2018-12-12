<?php

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

/*
 |--------------------------------------------------------------------------
 | User Routes
 |--------------------------------------------------------------------------
 */
Route::post('user/register', 'UserController@register');
Route::post('user/login', 'UserController@login');
Route::post('user/logout', 'UserController@logout');

Route::middleware(['jwt.auth'])->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Podcast Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/podcasts', 'PodcastController@index')->name('podcast');
    Route::post('/podcasts/new', 'PodcastController@create')->name('podcastCreate');
    Route::get('/podcasts/{podcastSlug}', 'PodcastController@show')->name('podcastShow');
    Route::delete('/podcasts/{podcastSlug}/delete', 'PodcastController@delete')->name('podcastDelete');

    /*
    |--------------------------------------------------------------------------
    | Episode Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/podcasts/{podcastSlug}/episodes', 'EpisodeController@index')->name('episode');
    Route::post('/podcasts/{podcastSlug}/episodes/new', 'EpisodeController@create')->name('episodeCreate');
    Route::get('/podcasts/{podcastSlug}/episodes/{episodeNumber}', 'EpisodeController@show')->name('episodeShow');
    Route::patch('/podcasts/{podcastSlug}/episodes/{episodeNumber}/update', 'EpisodeController@update')->name('episodeUpdate');
});
