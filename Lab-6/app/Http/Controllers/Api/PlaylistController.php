<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlaylistStoreRequest;
use App\Http\Requests\PlaylistUpdateRequest;
use App\Repositories\PlaylistRepository;
use App\Services\CacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PlaylistController extends Controller
{
    public function __construct(
        private PlaylistRepository $repository,
        private CacheService       $cacheService
    )
    {
    }

    public function index(): Response
    {
        Log::info('All playlists information requested');
        return response($this->repository->getPlaylists());
    }


    public function store(PlaylistStoreRequest $request): JsonResponse
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


    public function show(int $playlist_id): Response|JsonResponse
    {
        Log::info('Playlist information requested', ['id' => $playlist_id]);
        $playlist = $this->cacheService->getOrAdd("playlists:{$playlist_id}", fn() => $this->repository->getPlaylist($playlist_id), 120);
        return ($playlist === null) ? response()->json('Playlist not found', 404) : response($playlist, 200);
    }


    public function update(PlaylistUpdateRequest $request, int $playlist_id): Response
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


        return response()->noContent();
    }


    public function destroy(int $id): Response
    {
        Log::info('Deleting playlist information requested', ['id' => $id]);
        $this->repository->deletePlaylist($id);
        $this->cacheService->delete("playlists:{$id}");
        return response()->noContent();
    }


    public function showUserPlaylists(int $id): Response
    {
        Log::info('All user playlists information requested', ['id' => $id]);
        return response($this->repository->getPlaylistsByUserId($id));
    }


    public function putSongToPlaylist(int $playlist_id, int $song_id): Response
    {
        Log::info(
            'Adding song to playlist requested',
            [
                'playlist_id' => $playlist_id,
                'song_id' => $song_id
            ]
        );
        $this->repository->putSongToPlaylist($playlist_id, $song_id);
        return response()->noContent();
    }

    public function deleteSongFromPlaylist(int $playlist_id, int $song_id): Response|JsonResponse
    {
        $success = $this->repository->deleteSongFromPlaylist($playlist_id, $song_id);

        if (!$success) {
            Log::notice(
                'Deleting nonexistent song from playlist requested', ['playlist_id' => $playlist_id,]);
            return \response()->json('Playlist not found', 404);
        }


        Log::info(
            'Deleting song from playlist requested',
            [
                'playlist_id' => $playlist_id,
                'song_id' => $song_id,
            ]
        );

        return response()->noContent();
    }
}
