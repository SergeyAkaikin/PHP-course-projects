<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SongStoreRequest;
use App\Http\Requests\SongUpdateRequest;
use App\Repositories\SongRepository;
use App\Services\CacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SongController extends Controller
{
    public function __construct(
        private readonly SongRepository $repository,
        private readonly CacheService $cacheService
    )
    {
    }


    public function index(): Response
    {
        Log::info('All songs information requested');
        return response($this->repository->getSongs(), 200);
    }


    public function store(SongStoreRequest $request): JsonResponse
    {

        $song = $request->body();
        Log::info(
            'Storing new song information requested',
            [
                'artist_id' => $song->artist_id,
                'title' => $song->title,
                'genre' => $song->genre,
            ]
        );
        $id = $this->repository->putSong(
            $song->artist_id,
            $song->title,
            $song->genre
        );
        return response()->json(['id' => $id]);
    }


    public function show(int $song_id): Response|JsonResponse
    {
        Log::info('Song information requested', ['id' => $song_id]);
        $song = $this->cacheService->getOrAdd("songs:{$song_id}", fn() => $this->repository->getSong($song_id), 120);
        return ($song === null) ? response()->json('Song not found', 404) : response($song, 200);
    }


    public function update(SongUpdateRequest $request, int $song_id): Response|JsonResponse
    {
        $song = $request->body();
        $success = $this->repository->updateSong(
            $song_id,
            $song->title,
            $song->genre
        );

        if (!$success) {
            Log::notice('Updating nonexistent song information requested', ['id' => $song_id]);
            return response()->json('Song not found', 404);
        }

        Log::info(
            'Updating song information requested',
            [
                'id' => $song_id,
                'artist_id' => $song->artist_id,
                'title' => $song->title,
                'genre' => $song->genre,
            ]
        );

        $this->cacheService->delete("songs:{$song_id}");
        return response()->noContent();
    }


    public function destroy(int $song_id): Response|JsonResponse
    {
        $success = $this->repository->deleteSong($song_id);
        if (!$success) {
            Log::notice('Destroying nonexistent song information requested', ['id' => $song_id]);
            return \response()->json('Song not found', 404);
        }

        Log::info('Destroying song information requested', ['id' => $song_id]);
        $this->cacheService->delete("songs:{$song_id}");
        return response()->noContent();
    }


    public function showAlbumSongs(int $album_id): Response
    {
        Log::info('All album songs information requested', ['id' => $album_id]);
        return response($this->repository->getSongsByAlbum($album_id), 200);
    }


    public function showArtistSongs(int $artist_id): JsonResponse
    {
        Log::info('All artist songs information requested', ['id' => $artist_id]);
        return response()->json($this->repository->getSongsByArtist($artist_id));
    }


    public function storeAlbumSong(Request $request, int $album_id, int $song_id): JsonResponse
    {
        Log::info(
            'Adding song to album requested',
            [
                'album_id' => $album_id,
                'song_id' => $song_id
            ]
        );
        $id = $this->repository->putSongToAlbum(
            $album_id,
            $song_id
        );
        return response()->json($id);
    }


    public function showPlaylistSongs(int $playlist_id): JsonResponse
    {
        Log::info('All playlist songs information requested', ['id' => $playlist_id]);
        return response()->json($this->repository->getSongsFromPlaylist($playlist_id));
    }
}
