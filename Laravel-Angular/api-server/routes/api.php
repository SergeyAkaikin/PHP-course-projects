<?php

use App\Http\Controllers\Api\AlbumController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PlaylistController;
use App\Http\Controllers\Api\SongController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\Have;
use App\Http\Middleware\WithManagement;
use App\Models\Permissions\ContentType;
use App\Models\Permissions\PermissionCode;
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

Route::post('login', [LoginController::class, 'login'])->withoutMiddleware(Authenticate::class);
Route::get('logout', [LoginController::class, 'logout']);

Route::put('register', [UserController::class, 'registerUser'])->withoutMiddleware(Authenticate::class);


Route::middleware([Have::permissions(PermissionCode::AccessUserInformation)])->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('me/mainPlaylist', [PlaylistController::class, 'getCurrentUserMainPlaylist']);
        Route::get('', [UserController::class, 'getUsers']);
        Route::get('me', [UserController::class, 'getCurrentUser']);
        Route::prefix('{userId}')->whereNumber('userId')->group(function () {
            Route::get('', [UserController::class, 'getUser']);
            Route::get('albums', [AlbumController::class, 'getUserAlbums']);
            Route::middleware([
                Have::permissions(PermissionCode::ManageOwnUserInfo),
                WithManagement::accessTo(ContentType::User, 'userId'),
            ])->group(function () {
                Route::post('', [UserController::class, 'updateUser']);
                Route::delete('', [UserController::class, 'deleteUser']);
                Route::middleware([Have::permissions(PermissionCode::AccessMusicCollection)])->group(function () {
                Route::get('songs', [PlaylistController::class, 'getUserSongs']);
                    Route::get('playlists', [PlaylistController::class, 'getUserPlaylists']);
                });
            });
        });
    });

    Route::prefix('artists')->group(function () {
        Route::get('', [UserController::class, 'getArtists']);
    });
});


Route::middleware([Have::permissions(PermissionCode::AccessMusicCollection)])->group(function () {
    Route::prefix('albums')->group(function () {
        Route::get('', [AlbumController::class, 'getAlbums']);
        Route::put('', [AlbumController::class, 'createAlbum'])->middleware([Have::permissions(PermissionCode::UploadMusicContent)]);
        Route::prefix('{albumId}')->whereNumber('albumId')->group(function () {
            Route::get('', [AlbumController::class, 'getAlbum']);
            Route::get('songs', [SongController::class, 'getAlbumSongs']);
            Route::middleware([
                Have::permissions(PermissionCode::ManageOwnAlbums),
                WithManagement::accessTo(ContentType::Album, 'albumId'),
            ])->group(function () {
                Route::delete('', [AlbumController::class, 'deleteAlbum']);
                Route::post('', [AlbumController::class, 'updateAlbum']);
                Route::prefix('songs/{songId}')->whereNumber('songId')->group(function () {
                    Route::delete('', [SongController::class, 'deleteSongFromAlbum']);
                });
                Route::post('songs', [SongController::class, 'uploadAlbumSong'])
                    ->middleware([
                        Have::permissions(PermissionCode::UploadMusicContent),
                    ]);
            });
        });
    });


    Route::prefix('playlists')->group(function () {
        Route::put('', [PlaylistController::class, 'createPlaylist'])->middleware([Have::permissions(PermissionCode::CreatePlaylist)]);
        Route::prefix('{playlistId}')->whereNumber('playlistId')->group(function () {
            Route::get('', [PlaylistController::class, 'getPlaylist']);
            Route::get('songs', [SongController::class, 'getPlaylistSongs']);

            Route::middleware([
                Have::permissions(PermissionCode::ManageOwnLibrary),
                WithManagement::accessTo(ContentType::Playlist, 'playlistId'),
            ])->group(function () {
                Route::post('update', [PlaylistController::class, 'updatePlaylist']);
                Route::delete('', [PlaylistController::class, 'deletePlaylist']);
            });
            Route::prefix('songs/{songId}')->whereNumber('songId')
                ->middleware([
                    Have::permissions(PermissionCode::ManageOwnLibrary),
                    WithManagement::accessTo(ContentType::Playlist, 'playlistId'),
                ])->group(function () {
                    Route::delete('', [PlaylistController::class, 'deleteSongFromPlaylist']);
                    Route::post('', [PlaylistController::class, 'addSongToPlaylist']);
                });

        });
    });
});

