<?php

use App\Http\Controllers\Api\AlbumController;
use App\Http\Controllers\Api\ArtistController;
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


Route::middleware([Have::permissions(PermissionCode::AccessUserInformation)])->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('', [UserController::class, 'getUsers']);

        Route::middleware([Have::permissions(PermissionCode::AccessMusicCollection)])->group(function () {
            Route::get('songs', [PlaylistController::class, 'getCurrentUserSongs']);
            Route::get('songs/{songId}', [PlaylistController::class, 'putSongToMainPlaylist'])->whereNumber('songId')
                ->middleware([
                    Have::permissions(PermissionCode::ManageOwnLibrary)
                ]);
        });

        Route::prefix('{userId}')->whereNumber('userId')->group(function () {
            Route::get('', [UserController::class, 'getUser']);
            Route::middleware([
                Have::permissions(PermissionCode::ManageOwnUserInfo),
                WithManagement::accessTo(ContentType::User, 'userId'),
            ])->group(function () {
                Route::post('', [UserController::class, 'updateUser']);
                Route::delete('', [UserController::class, 'deleteUser']);
            });
            Route::middleware([Have::permissions(PermissionCode::AccessMusicCollection)])->group(function () {
                Route::get('playlist', [PlaylistController::class, 'getMainUserPlaylist']);
                Route::get('playlists', [PlaylistController::class, 'getUserPlaylists']);
            });
        });
    });

    Route::prefix('artists')->group(function () {
        Route::get('', [ArtistController::class, 'getArtists']);
        Route::prefix('{artistId}')->whereNumber('artistId')->group(function () {
            Route::get('', [ArtistController::class, 'getArtist']);
            Route::middleware([
                Have::permissions(PermissionCode::ManageOwnUserInfo),
                WithManagement::accessTo(ContentType::User, 'artistId'),
            ])->group(function () {
                Route::post('', [ArtistController::class, 'updateArtist']);
                Route::delete('', [ArtistController::class, 'deleteUser']);
            });
            Route::get('songs', [SongController::class, 'getArtistSongs']);
        });
    });
});

Route::put('users', [UserController::class, 'storeUser'])->withoutMiddleware(Authenticate::class);
Route::put('artists', [ArtistController::class, 'storeArtist'])->withoutMiddleware(Authenticate::class);


Route::middleware([Have::permissions(PermissionCode::AccessMusicCollection)])->group(function () {
    Route::prefix('songs')->group(function () {
        Route::get('', [SongController::class, 'getSongs']);
        Route::post('', [SongController::class, 'storeSong'])->middleware([Have::permissions(PermissionCode::UploadMusicContent)]);
        Route::prefix('{songId}')->whereNumber('songId')->group(function () {
            Route::get('', [SongController::class, 'getSong']);
            Route::post('', [SongController::class, 'updateSong'])->middleware([
                WithManagement::accessTo(ContentType::Song, 'songId'),
                Have::permissions(PermissionCode::ManageOwnSongs)
            ]);
            Route::delete('', [SongController::class, 'deleteSong'])->middleware([
                Have::permissions(PermissionCode::ManageOwnSongs),
                WithManagement::accessTo(ContentType::Song, 'songId'),
            ]);
        });
    });


    Route::prefix('albums')->group(function () {
        Route::get('', [AlbumController::class, 'getAlbums']);
        Route::put('', [AlbumController::class, 'putAlbum'])->middleware([Have::permissions(PermissionCode::UploadMusicContent)]);
        Route::prefix('{albumId}')->whereNumber('albumId')->group(function () {
            Route::get('', [AlbumController::class, 'getAlbum']);
            Route::middleware([
                Have::permissions(PermissionCode::ManageOwnAlbums),
                WithManagement::accessTo(ContentType::Album, 'albumId'),
            ])->group(function () {
                Route::delete('', [AlbumController::class, 'deleteAlbum']);
                Route::post('', [AlbumController::class, 'updateAlbum']);
                Route::prefix('songs/{songId}')->whereNumber('songId')->group(function () {
                    Route::get('', [SongController::class, 'addSongToAlbum']);
                    Route::delete('', [SongController::class, 'deleteSongFromAlbum']);
                });
            });
            Route::post('songs', [SongController::class, 'putAlbumSong'])
                ->middleware([
                    Have::permissions(PermissionCode::UploadMusicContent, PermissionCode::ManageOwnAlbums),
                    WithManagement::accessTo(ContentType::Album, 'albumId'),
                ]);
            Route::get('songs', [SongController::class, 'getAlbumSongs']);
        });
    });


    Route::prefix('playlists')->group(function () {
        Route::get('', [PlaylistController::class, 'getPlaylists']);
        Route::post('', [PlaylistController::class, 'putPlaylist'])->middleware([Have::permissions(PermissionCode::CreatePlaylist)]);
        Route::prefix('{playlistId}')->whereNumber('playlistId')->group(function () {

            Route::get('', [PlaylistController::class, 'getPlaylist']);
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
                    Route::get('', [PlaylistController::class, 'putSongToPlaylist']);
                });
            Route::get('songs', [SongController::class, 'getPlaylistSongs']);
        });
    });
});

