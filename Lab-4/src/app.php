<?php
declare(strict_types=1);

use MusicService\Api\Controllers\AlbumController;
use MusicService\Api\Controllers\ArtistController;
use MusicService\Api\Controllers\SongController;
use MusicService\Api\Controllers\UserController;
use MusicService\Api\Controllers\PlaylistController;
use MusicService\DataAccess\DataRepository\AlbumRepository;
use MusicService\DataAccess\DataRepository\ArtistRepository;
use MusicService\DataAccess\DataRepository\SongRepository;
use MusicService\DataAccess\DataRepository\PlaylistRepository;
use MusicService\DataAccess\DataRepository\UserRepository;
use MusicService\DataAccess\PdoFactory;
use MusicService\Http\Middleware\Pipeline;
use MusicService\Http\Middleware\ValidationMiddleware;
use MusicService\Http\Routing\Route;
use MusicService\Http\Routing\Router;
use MusicService\Utils\RequestUtils;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$userController = new UserController(new UserRepository(PdoFactory::get()));
$songController = new SongController(new SongRepository(PdoFactory::get()));
$albumController = new AlbumController(new AlbumRepository(PdoFactory::get()));
$artistController = new ArtistController(new ArtistRepository(PdoFactory::get()));
$playListController = new PlaylistController(new PlaylistRepository(PdoFactory::get()));

$routes = [
    Route::createGet('/api/users/all', [$userController, 'getUsers']),
    Route::createGet('/api/users', [$userController, 'getUser']),
    Route::createGet('/api/users/{id}/delete', [$userController, 'deleteUser']),
    Route::createPost('/api/users/put', [$userController, 'putUser']),
    Route::createGet('/api/songs/all', [$songController, 'getSongs']),
    Route::createGet('/api/songs', [$songController, 'getSong']),
    Route::createGet('/api/songs/delete', [$songController, 'deleteSong']),
    Route::createPost('/api/songs/put', [$songController, 'putSong']),
    Route::createGet('/api/albums/all', [$albumController, 'getAlbums']),
    Route::createGet('/api/albums', [$albumController, 'getAlbum']),
    Route::createGet('/api/albums/delete', [$albumController, 'deleteAlbum']),
    Route::createPost('/api/albums/put', [$albumController, 'putAlbum']),
    Route::createPost('api/albums/put/song', [$albumController, 'putSongToAlbum']),
    Route::createGet('/api/artists/all', [$artistController, 'getArtists']),
    Route::createGet('/api/artists', [$artistController, 'getArtist']),
    Route::createPost('/api/artists/put', [$artistController, 'putArtist']),
    Route::createGet('/api/playlists/all', [$playListController, 'getPlayLists']),
    Route::createGet('/api/playlists', [$playListController, 'getPlaylist']),
    Route::createGet('/api/playlists/users', [$playListController, 'getUserPlaylist']),
    Route::createGet('/api/playlists/delete', [$playListController, 'deletePlaylist']),
    Route::createGet('/api/playlists/delete/songs', [$playListController, 'deleteSongFromPlaylist']),
    Route::createPost('/api/playlists/put', [$playListController, 'putPlaylist']),
    Route::createPost('/api/playlists/put/songs', [$playListController, 'putSongToPlaylist']),
    Route::createGet('/api/albums/songs', [$songController, 'getAlbumSongs']),


];

$router = new Router($routes);
$handler = $router->findRequestHandler(RequestUtils::getRequestMethod(), RequestUtils::getRequestPath());

if ($handler === null) {
    echo "<h1>Page not found</h1>";

    return;
}

$requestType = RequestUtils::getRequestType($handler);
$request = new $requestType(
    RequestUtils::getRequestUri(),
    RequestUtils::getRequestMethod(),
    RequestUtils::getRequestPath(),
    RequestUtils::getRequestData()
);

$pipeline = new Pipeline(
    $handler,
    [
        new ValidationMiddleware(),
    ]
);


$response = $pipeline->handle($request);
echo json_encode($response);

