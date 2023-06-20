<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\PlaylistStoreRequest;
use App\Http\Requests\PlaylistUpdateRequest;
use App\Models\Playlist;
use App\Models\Song;
use App\Repositories\PlaylistRepository;
use App\Repositories\SongRepository;
use App\Services\CacheService;
use App\Utils\Mappers\PlaylistMapper;
use App\Utils\Mappers\SongMapper;
use App\ApiModels\PlaylistModel;
use App\ApiModels\SongModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaylistController extends BaseController
{
    public function __construct(
        private readonly PlaylistRepository $playlistRepository,
        private readonly SongRepository     $songRepository,
        private readonly CacheService       $cacheService,
        private readonly PlaylistMapper     $mapper,
    )
    {
    }



    public function createPlaylist(PlaylistStoreRequest $request): JsonResponse
    {

        $playlist = $request->body();
        $id = $this->playlistRepository->createPlaylist(
            $playlist->user_id,
            $playlist->title
        );

        return response()->json(['id' => $id]);
    }


    public function getPlaylist(int $playlistId): JsonResponse
    {
        $playlist = $this->cacheService->getOrAdd(
            "playlists:{$playlistId}",
            function () use ($playlistId) {
                $playlist = $this->playlistRepository->getPlaylist($playlistId);
                if ($playlist === null) {
                    return null;
                }
                $songs = $this->songRepository->getPlaylistSongs($playlistId);
                return $this->mapper->mapFullPlaylist($playlist, $songs);

            }, 120);
        return ($playlist === null) ? $this->failResponse('Playlist not found') : $this->successResponse($playlist);
    }


    public function updatePlaylist(PlaylistUpdateRequest $request, int $playlistId): JsonResponse
    {
        $validated = $request->validated();
        $success = $this->playlistRepository->updatePlaylist(
            $playlistId,
            $validated['title'],
        );

        if (!$success) {
            return $this->failResponse();
        }

        return $this->successResponse();
    }


    public function deletePlaylist(int $playlistId): JsonResponse
    {
        $this->playlistRepository->deletePlaylist($playlistId);
        $this->cacheService->delete("playlists:{$playlistId}");
        return $this->successResponse();
    }


    public function getUserPlaylists(int $userId): JsonResponse
    {
        $playlists = $this->playlistRepository->getPlaylistsByUserId($userId);
        $models = $playlists->map(fn(Playlist $playlist): PlaylistModel => $this->mapper->mapPlaylist($playlist));
        return $this->successResponse($models);
    }



    public function addSongToPlaylist(int $playlistId, int $songId): JsonResponse
    {

        $id = $this->playlistRepository->putSongToPlaylist($playlistId, $songId);
        $this->cacheService->delete("playlists:{$playlistId}");
        return $this->successResponse(['id' => $id]);
    }

    public function deleteSongFromPlaylist(int $playlistId, int $songId): JsonResponse
    {
        $success = $this->playlistRepository->deleteSongFromPlaylist($playlistId, $songId);

        if (!$success) {
            return $this->failResponse();
        }


        return $this->successResponse();
    }

    public function getUserSongs(int $userId): JsonResponse
    {
        $mainPlaylist = $this->playlistRepository->getMainUserPlaylist($userId);
        if ($mainPlaylist === null) {
            return $this->failResponse();
        }
        $songs = $this->songRepository->getPlaylistSongs($mainPlaylist->id);
        $mapper = new SongMapper();
        $models = $songs->map(fn(Song $song): SongModel => $mapper->mapSong($song))->toArray();
        return $this->successResponse($models);
    }

    public function getCurrentUserMainPlaylist(Request $request): JsonResponse
    {
        $userId = $this->getCurrentUserId($request);
        $mainPlaylist = $this->playlistRepository->getMainUserPlaylist($userId);
        if ($mainPlaylist === null) {
            return response()->json('User not found', 404);
        }
        $model = $this->mapper->mapPlaylist($mainPlaylist);
        return response()->json($model);
    }

}
