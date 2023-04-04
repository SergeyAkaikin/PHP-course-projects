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
        Route::get('', [UserController::class, 'index']);
        Route::prefix('{user_id}')->whereNumber('user_id')->group(function () {
            Route::get('', [UserController::class, 'show']);
            Route::middleware([
                WithManagement::accessTo(ContentType::User, 'user_id'),
                Have::permissions(PermissionCode::ManageAllUsersInfo, PermissionCode::ManageOwnUserInfo),
            ])->group(function () {
                Route::post('update', [UserController::class, 'update']);
                Route::delete('', [UserController::class, 'destroy']);
            });

        });
    });

    Route::prefix('artists')->group(function () {
        Route::get('', [ArtistController::class, 'index']);
        Route::prefix('{artist_id}')->whereNumber('artist_id')->group(function () {
            Route::get('', [ArtistController::class, 'show']);
            Route::middleware([
                WithManagement::accessTo(ContentType::User, 'artist_id'),
                Have::permissions(PermissionCode::ManageAllUsersInfo, PermissionCode::ManageOwnUserInfo),
            ])->group(function () {
                Route::post('update', [ArtistController::class, 'update']);
                Route::delete('', [ArtistController::class, 'destroy']);
            });
        });
    });
});

Route::post('users', [UserController::class, 'store'])->withoutMiddleware(Authenticate::class);
Route::post('artists', [ArtistController::class, 'store'])->withoutMiddleware(Authenticate::class);


Route::middleware([Have::permissions(PermissionCode::AccessMusicCollection)])->group(function () {
    Route::prefix('songs')->group(function () {
        Route::get('', [SongController::class, 'index']);
        Route::post('', [SongController::class, 'store'])->middleware([Have::permissions(PermissionCode::UploadMusicContent)]);
        Route::prefix('{song_id}')->whereNumber('song_id')->group(function () {
            Route::get('', [SongController::class, 'show']);
            Route::post('update', [SongController::class, 'update'])->middleware([
                WithManagement::accessTo(ContentType::Song, 'song_id'),
                Have::permissions(PermissionCode::ManageAllMusicContent, PermissionCode::ManageOwnSongs)
            ]);
            Route::delete('', [SongController::class, 'destroy'])->middleware([
                WithManagement::accessTo(ContentType::Song, 'song_id'),
                Have::permissions(PermissionCode::ManageAllMusicContent, PermissionCode::DeleteMusicContent, PermissionCode::ManageOwnSongs),
            ]);
        });
    });

    Route::get('/albums/{album_id}/songs', [SongController::class, 'showAlbumSongs'])->whereNumber('album_id');
    Route::get('/artists/{artist_id}/songs', [SongController::class, 'showArtistSongs'])->whereNumber('artist_id');

    Route::get('/albums/{album_id}/songs/{song_id}', [SongController::class, 'addAlbumSong'])->whereNumber(['album_id', 'song_id'])
    ->middleware([Have::permissions(PermissionCode::UploadMusicContent)]);
    Route::get('/playlists/{playlist_id}/songs', [SongController::class,'showPlaylistSongs'])->whereNumber('playlist_id');
    Route::post('/albums/{album_id}/songs', [SongController::class, 'storeAlbumSong'])->whereNumber('album_id')
        ->middleware([
            WithManagement::accessTo(ContentType::Album, 'album_id'),
            Have::permissions(PermissionCode::UploadMusicContent, PermissionCode::ManageOwnAlbums)
        ]);
    Route::delete('/albums/{album_id}/songs/{song_id}', [SongController::class, 'deleteSongFromAlbum'])->whereNumber(['album_id', 'song_id'])
        ->middleware([
           WithManagement::accessTo(ContentType::Album, 'album_id'),
           Have::permissions(PermissionCode::ManageOwnAlbums, PermissionCode::ManageAllMusicContent)
        ]);


    Route::prefix('albums')->group(function () {
       Route::get('', [AlbumController::class, 'index']);
       Route::post('', [AlbumController::class, 'store'])->middleware([Have::permissions(PermissionCode::UploadMusicContent)]);
       Route::prefix('{album_id}')->whereNumber('album_id')->group(function () {
          Route::get('', [AlbumController::class, 'show']);
          Route::delete('', [AlbumController::class, 'destroy'])->middleware([
              WithManagement::accessTo(ContentType::Album, 'album_id'),
              Have::permissions(PermissionCode::ManageAllMusicContent, PermissionCode::DeleteMusicContent, PermissionCode::ManageOwnAlbums)
          ]);
          Route::post('update', [AlbumController::class, 'update'])->middleware(
              WithManagement::accessTo(ContentType::Album, 'album_id'),
              Have::permissions(PermissionCode::ManageAllMusicContent, PermissionCode::ManageOwnAlbums),
          );
       });
    });


    Route::prefix('playlists')->group(function () {
        Route::get('', [PlaylistController::class, 'index']);
        Route::post('', [PlaylistController::class, 'store'])->middleware([Have::permissions(PermissionCode::CreatePlaylist)]);
        Route::prefix('{playlist_id}')->whereNumber('playlist_id')->group(function () {
            Route::get('', [PlaylistController::class, 'show']);
            Route::middleware([
                WithManagement::accessTo(ContentType::Playlist, 'playlist_id'),
                Have::permissions(PermissionCode::ManageAllMusicContent, PermissionCode::ManageOwnLibrary),
            ])->group( function () {
                Route::post('update', [PlaylistController::class, 'update']);
                Route::delete('', [ArtistController::class, 'destroy']);
            });
            Route::prefix('songs/{song_id}')->whereNumber('song_id')
                ->middleware([
                    WithManagement::accessTo(ContentType::Playlist, 'playlist_id'),
                    Have::permissions(PermissionCode::ManageAllUsersInfo, PermissionCode::ManageOwnLibrary),
                ])->group(function () {
                    Route::delete('', [PlaylistController::class, 'deleteSongFromPlaylist']);
                    Route::post('', [PlaylistController::class, 'putSongToPlaylist']);
                });
        });
    });

    Route::get('/users/{user_id}/playlists', [PlaylistController::class, 'showUserPlaylists'])->whereNumber('user_id');
});

