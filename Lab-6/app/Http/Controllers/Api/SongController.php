<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\SongStoreRequest;
use App\Http\Requests\SongUpdateRequest;
use App\Models\Song;
use App\Repositories\SongRepository;
use App\Services\AudioService;
use App\Services\CacheService;
use App\Utils\Mappers\SongMapper;
use App\ViewModels\SongModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SongController extends BaseController
{
    public function __construct(
        private readonly SongRepository $repository,
        private readonly CacheService   $cacheService,
        private readonly AudioService   $audioService,
        private readonly SongMapper     $songMapper
    )
    {
    }


    public function getSongs(): JsonResponse
    {
        Log::info('All songs information requested');
        return response()->json($this->repository->getSongs()->map(fn(Song $song): SongModel => $this->songMapper->mapSong($song)));
    }


    public function storeSong(SongStoreRequest $request): JsonResponse
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
        $id = $this->audioService->saveAudio($song->artist_id, $song->title, $song->genre, $song->file);

        return response()->json(['id' => $id]);
    }


    public function getSong(int $song_id): JsonResponse
    {
        Log::info('Song information requested', ['id' => $song_id]);
        $song = $this->cacheService->getOrAdd("songs:{$song_id}", function () use ($song_id) {
            $song = $this->repository->getSong($song_id);
            return ($song === null) ? null : $this->songMapper->mapSong($song);
        }, 120);
        return ($song === null) ? response()->json('Song not found', 404) : response()->json($song);
    }


    public function updateSong(SongUpdateRequest $request, int $song_id): JsonResponse
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
        return response()->json();
    }


    public function deleteSong(int $song_id): JsonResponse
    {
        $song = $this->repository->getSong($song_id);
        $success = $this->repository->deleteSong($song_id);
        if (!$success) {
            Log::notice('Destroying nonexistent song information requested', ['id' => $song_id]);
            return \response()->json('Song not found', 404);
        }

        Log::info('Destroying song information requested', ['id' => $song_id]);
        $this->cacheService->delete("songs:{$song_id}");
        $this->audioService->deleteAudio($song->path);
        return response()->json();
    }


    public function getAlbumSongs(int $album_id): JsonResponse
    {
        Log::info('All album songs information requested', ['id' => $album_id]);
        return response()->json($this->repository->getSongsByAlbum($album_id)
            ->map(fn(Song $song): SongModel => $this->songMapper->mapSong($song))
        );
    }


    public function getArtistSongs(int $artist_id): JsonResponse
    {
        Log::info('All artist songs information requested', ['id' => $artist_id]);
        return response()->json($this->repository->getSongsByArtist($artist_id)
            ->map(fn(Song $song): SongModel => $this->songMapper->mapSong($song))
        );
    }


    public function addSongToAlbum(int $album_id, int $song_id): JsonResponse
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

    public function storeAlbumSong(SongStoreRequest $request, int $album_id): JsonResponse
    {
        Log::info(
            'Adding song to album requested',
            [
                'album_id' => $album_id,
            ]
        );
        $song = $request->body();
        $id = $this->audioService->saveAudio($song->artist_id, $song->title, $song->genre, $song->file, $album_id);
        $this->repository->putSongToAlbum($album_id, $id);
        $this->cacheService->delete("full_albums:{$album_id}");
        return response()->json($id);
    }


    public function getPlaylistSongs(int $playlist_id): JsonResponse
    {
        Log::info('All playlist songs information requested', ['id' => $playlist_id]);
        return response()->json($this->repository->getSongsFromPlaylist($playlist_id)
            ->map(fn(Song $song): SongModel => $this->songMapper->mapSong($song)));
    }

    public function deleteSongFromAlbum(int $album_id, int $song_id): JsonResponse
    {
        $song = $this->repository->getSong($song_id);
        $this->cacheService->delete("full_albums:{$album_id}");
        $this->audioService->deleteAudio($song->path);
        $this->repository->deleteSongFromAlbum($album_id, $song_id);
        return  response()->json();
    }


}
