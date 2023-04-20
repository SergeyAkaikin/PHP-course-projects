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


    public function getSong(int $songId): JsonResponse
    {
        Log::info('Song information requested', ['id' => $songId]);
        $song = $this->cacheService->getOrAdd("songs:{$songId}", function () use ($songId) {
            $song = $this->repository->getSong($songId);
            return ($song === null) ? null : $this->songMapper->mapSong($song);
        }, 120);
        return ($song === null) ? response()->json('Song not found', 404) : response()->json($song);
    }


    public function updateSong(SongUpdateRequest $request, int $songId): JsonResponse
    {
        $song = $request->body();
        $success = $this->repository->updateSong(
            $songId,
            $song->title,
            $song->genre
        );

        if (!$success) {
            Log::notice('Updating nonexistent song information requested', ['id' => $songId]);
            return response()->json('Song not found', 404);
        }

        Log::info(
            'Updating song information requested',
            [
                'id' => $songId,
                'artist_id' => $song->artist_id,
                'title' => $song->title,
                'genre' => $song->genre,
            ]
        );

        $this->cacheService->delete("songs:{$songId}");
        return response()->json();
    }


    public function deleteSong(int $songId): JsonResponse
    {
        $song = $this->repository->getSong($songId);
        $this->repository->deleteSong($songId);
        Log::info('Destroying song information requested', ['id' => $songId]);
        $this->cacheService->delete("songs:{$songId}");
        $this->audioService->deleteAudio($song->path);
        return response()->json();
    }


    public function getAlbumSongs(int $albumId): JsonResponse
    {
        Log::info('All album songs information requested', ['id' => $albumId]);
        return response()->json($this->repository->getSongsByAlbum($albumId)
            ->map(fn(Song $song): SongModel => $this->songMapper->mapSong($song))
        );
    }


    public function getArtistSongs(int $artistId): JsonResponse
    {
        Log::info('All artist songs information requested', ['id' => $artistId]);
        return response()->json($this->repository->getSongsByArtist($artistId)
            ->map(fn(Song $song): SongModel => $this->songMapper->mapSong($song))
        );
    }


    public function addSongToAlbum(int $albumId, int $songId): JsonResponse
    {
        Log::info(
            'Adding song to album requested',
            [
                'album_id' => $albumId,
                'song_id' => $songId
            ]
        );
        $id = $this->repository->putSongToAlbum(
            $albumId,
            $songId
        );
        return response()->json($id);
    }

    public function putAlbumSong(SongStoreRequest $request, int $albumId): JsonResponse
    {
        Log::info(
            'Adding song to album requested',
            [
                'album_id' => $albumId,
            ]
        );
        $song = $request->body();
        $id = $this->audioService->saveAudio($song->artist_id, $song->title, $song->genre, $song->file, $albumId);
        $this->repository->putSongToAlbum($albumId, $id);
        $this->cacheService->delete("full_albums:{$albumId}");
        return response()->json($id);
    }


    public function getPlaylistSongs(int $playlistId): JsonResponse
    {
        Log::info('All playlist songs information requested', ['id' => $playlistId]);
        return response()->json($this->repository->getSongsFromPlaylist($playlistId)
            ->map(fn(Song $song): SongModel => $this->songMapper->mapSong($song)));
    }

    public function deleteSongFromAlbum(int $albumId, int $songId): JsonResponse
    {
        $song = $this->repository->getSong($songId);
        $this->cacheService->delete("full_albums:{$albumId}");
        $this->audioService->deleteAudio($song->path);
        $this->repository->deleteSongFromAlbum($albumId, $songId);
        return  response()->json();
    }


}
