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
use App\ViewModels\PlaylistModel;
use App\ViewModels\SongModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlaylistController extends BaseController
{
    public function __construct(
        private readonly PlaylistRepository $repository,
        private readonly SongRepository     $songRepository,
        private readonly CacheService       $cacheService,
        private readonly PlaylistMapper     $mapper,
    )
    {
    }

    public function getPlaylists(): JsonResponse
    {
        Log::info('All playlists information requested');
        $playlist = $this->repository->getPlaylists();
        $models = $playlist->map(fn(Playlist $playlist): PlaylistModel => $this->mapper->mapPlaylist($playlist));
        return response()->json($models);
    }


    public function putPlaylist(PlaylistStoreRequest $request): JsonResponse
    {

        $playlist = $request->body();
        Log::info(
            'Storing new playlist information requested',
            [
                'user_id' => $playlist->user_id,
                'title' => $playlist->title,
            ]
        );
        $id = $this->repository->putPlaylist(
            $playlist->user_id,
            $playlist->title
        );

        return response()->json(['id' => $id]);
    }


    public function getPlaylist(int $playlistId): JsonResponse
    {
        Log::info('Playlist information requested', ['id' => $playlistId]);
        $playlist = $this->cacheService->getOrAdd(
            "playlists:{$playlistId}",
            function () use ($playlistId) {
                $playlist = $this->repository->getPlaylist($playlistId);
                if ($playlist === null) {
                    return null;
                }
                $songs = $this->songRepository->getSongsFromPlaylist($playlistId);
                return $this->mapper->mapFullPlaylist($playlist, $songs);

            }, 120);
        return ($playlist === null) ? response()->json('Playlist not found', 404) : response()->json($playlist);
    }


    public function updatePlaylist(PlaylistUpdateRequest $request, int $playlistId): JsonResponse
    {
        $validated = $request->validated();
        $success = $this->repository->updatePlaylist(
            $playlistId,
            $validated['title'],
        );

        if (!$success) {
            Log::notice('Updating nonexistent playlist information requested', ['id' => $playlistId,]);
        }

        Log::info(
            'Updating playlist information requested',
            [
                'id' => $playlistId,
                'title' => $validated['title'],
            ]
        );


        return response()->json();
    }


    public function deletePlaylist(int $playlistId): JsonResponse
    {
        Log::info('Deleting playlist information requested', ['id' => $playlistId]);
        $this->repository->deletePlaylist($playlistId);
        $this->cacheService->delete("playlists:{$playlistId}");
        return response()->json();
    }


    public function getUserPlaylists(int $userId): JsonResponse
    {
        Log::info('All user playlists information requested', ['id' => $userId]);
        $playlists = $this->repository->getPlaylistsByUserId($userId);
        $models = $playlists->map(fn(Playlist $playlist): PlaylistModel => $this->mapper->mapPlaylist($playlist));
        return response()->json($models);
    }


    public function putSongToMainPlaylist(Request $request, int $songId): JsonResponse
    {
        $userId = $this->getCurrentUserId($request);
        $mainPlaylist = $this->repository->getMainUserPlaylist($userId);
        $this->repository->putSongToPlaylist($mainPlaylist->id, $songId);
        return response()->json();
    }

    public function putSongToPlaylist(int $playlistId, int $songId): JsonResponse
    {
        Log::info(
            'Adding song to playlist requested',
            [
                'playlist_id' => $playlistId,
                'song_id' => $songId
            ]
        );
        $id = $this->repository->putSongToPlaylist($playlistId, $songId);
        $this->cacheService->delete("playlists:{$playlistId}");
        return response()->json(['id' => $id]);
    }

    public function deleteSongFromPlaylist(int $playlistId, int $songId): JsonResponse
    {
        $success = $this->repository->deleteSongFromPlaylist($playlistId, $songId);

        if (!$success) {
            Log::notice(
                'Deleting nonexistent song from playlist requested', ['playlist_id' => $playlistId,]);
            return response()->json('Playlist not found', 404);
        }


        Log::info(
            'Deleting song from playlist requested',
            [
                'playlist_id' => $playlistId,
                'song_id' => $songId,
            ]
        );

        return response()->json();
    }

    public function getCurrentUserSongs(Request $request): JsonResponse
    {
        $user_id = $this->getCurrentUserId($request);
        $mainPlaylist = $this->repository->getMainUserPlaylist($user_id);
        $songs = $this->songRepository->getSongsFromPlaylist($mainPlaylist->id);
        $mapper = new SongMapper();
        $models = $songs->map(fn(Song $song): SongModel => $mapper->mapSong($song))->toArray();
        return response()->json($models);
    }

    public function getMainUserPlaylist(int $userId): JsonResponse
    {
        $mainPlaylist = $this->repository->getMainUserPlaylist($userId);
        if ($mainPlaylist === null) {
            return response()->json('User not found', 404);
        }
        $model = $this->mapper->mapPlaylist($mainPlaylist);
        return response()->json($model);
    }

}
