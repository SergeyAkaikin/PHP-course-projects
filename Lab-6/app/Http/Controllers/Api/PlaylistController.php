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


    public function storePlaylist(PlaylistStoreRequest $request): JsonResponse
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


    public function getPlaylist(int $playlist_id): JsonResponse
    {
        Log::info('Playlist information requested', ['id' => $playlist_id]);
        $playlist = $this->cacheService->getOrAdd(
            "playlists:{$playlist_id}",
            function () use ($playlist_id) {
                $playlist = $this->repository->getPlaylist($playlist_id);
                if ($playlist === null) {
                    return null;
                }
                $songs = $this->songRepository->getSongsFromPlaylist($playlist_id);
                return $this->mapper->mapFullPlaylist($playlist, $songs);

            }, 120);
        return ($playlist === null) ? response()->json('Playlist not found', 404) : response()->json($playlist);
    }


    public function updatePlaylist(PlaylistUpdateRequest $request, int $playlist_id): JsonResponse
    {
        $validated = $request->validated();
        $success = $this->repository->updatePlaylist(
            $playlist_id,
            $validated['title'],
        );

        if (!$success) {
            Log::notice('Updating nonexistent playlist information requested', ['id' => $playlist_id,]);
        }

        Log::info(
            'Updating playlist information requested',
            [
                'id' => $playlist_id,
                'title' => $validated['title'],
            ]
        );


        return response()->json();
    }


    public function deletePlaylist(int $playlist_id): JsonResponse
    {
        Log::info('Deleting playlist information requested', ['id' => $playlist_id]);
        $this->repository->deletePlaylist($playlist_id);
        $this->cacheService->delete("playlists:{$playlist_id}");
        return response()->json();
    }


    public function getUserPlaylists(int $user_id): JsonResponse
    {
        Log::info('All user playlists information requested', ['id' => $user_id]);
        $playlists = $this->repository->getPlaylistsByUserId($user_id);
        $models = $playlists->map(fn(Playlist $playlist): PlaylistModel => $this->mapper->mapPlaylist($playlist));
        return response()->json($models);
    }


    public function putSongToMainPlaylist(Request $request, int $song_id): JsonResponse
    {
        $user_id = $this->getCurrentUserId($request);
        $mainPlaylist = $this->repository->getMainUserPlaylist($user_id);
        $this->repository->putSongToPlaylist($mainPlaylist->id, $song_id);
        return response()->json();
    }

    public function putSongToPlaylist(int $playlist_id, int $song_id): JsonResponse
    {
        Log::info(
            'Adding song to playlist requested',
            [
                'playlist_id' => $playlist_id,
                'song_id' => $song_id
            ]
        );
        $id = $this->repository->putSongToPlaylist($playlist_id, $song_id);
        $this->cacheService->delete("playlists:{$playlist_id}");
        return response()->json(['id' => $id]);
    }

    public function deleteSongFromPlaylist(int $playlist_id, int $song_id): JsonResponse
    {
        $success = $this->repository->deleteSongFromPlaylist($playlist_id, $song_id);

        if (!$success) {
            Log::notice(
                'Deleting nonexistent song from playlist requested', ['playlist_id' => $playlist_id,]);
            return response()->json('Playlist not found', 404);
        }


        Log::info(
            'Deleting song from playlist requested',
            [
                'playlist_id' => $playlist_id,
                'song_id' => $song_id,
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

    public function getMainUserPlaylist(int $user_id): JsonResponse
    {
        $mainPlaylist = $this->repository->getMainUserPlaylist($user_id);
        if ($mainPlaylist === null) {
            return response()->json('User not found', 404);
        }
        $model = $this->mapper->mapPlaylist($mainPlaylist);
        return response()->json($model);
    }

}
