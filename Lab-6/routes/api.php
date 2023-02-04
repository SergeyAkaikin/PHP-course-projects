<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\SongController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index');
    Route::get('/user/{id}', 'show')->whereNumber('id');
    Route::delete('/user/{id}', 'destroy')->whereNumber('id');
    Route::post('/user', 'store');
    Route::post('/user/{id}/update', 'update')->whereNumber('id');
});

Route::controller(ArtistController::class)->group(function () {
    Route::get('/artists', 'index');
    Route::get('/artist/{id}', 'show')->whereNumber('id');
    Route::delete('/artist/{id}', 'destroy')->whereNumber('id');
    Route::post('/artist', 'store');
    Route::post('/artist/{id}/update', 'update')->whereNumber('id');
});

Route::controller(SongController::class)->group(function () {
    Route::get('/album/{id}/songs', 'albumSongs')->whereNumber('id');
    Route::get('/songs', 'index');
    Route::get('/song/{id}', 'show')->whereNumber('id');
    Route::post('/song', 'store');
    Route::delete('/song/{id}', 'destroy')->whereNumber('id');
    Route::post('/song/{id}/update', 'update')->whereNumber('id');
    Route::get('/album/{id}/songs', 'showAlbumSongs')->whereNumber('id');
    Route::get('/artist/{id}/songs', 'showArtistSongs')->whereNumber('id');
    Route::post('/album/song', 'storeAlbumSong');
    Route::get('/playlist/{id}/songs', 'showPlaylistSongs')->whereNumber('id');
});

Route::controller(AlbumController::class)->group(function () {
    Route::get('/albums', 'index');
    Route::get('/album/{id}', 'show')->whereNumber('id');
    Route::delete('/album/{id}', 'destroy')->whereNumber('id');
    Route::post('/album', 'store');
    Route::post('/album/{id}/update', 'update')->whereNumber('id');

});

Route::controller(PlaylistController::class)->group(function () {
    Route::get('/playlists', 'index');
    Route::get('/playlist/{id}', 'show')->whereNumber('id');
    Route::post('/playlist', 'store');
    Route::post('/playlist/{id}/update', 'update')->whereNumber('id');
    Route::delete('/playlist/{id}', 'destroy')->whereNumber('id');
    Route::get('/user/{id}/playlists', 'showUserPlaylists')->whereNumber('id');
    Route::post('/playlist/{id}/song', 'putSongToPlaylist')->whereNumber('id');
});
