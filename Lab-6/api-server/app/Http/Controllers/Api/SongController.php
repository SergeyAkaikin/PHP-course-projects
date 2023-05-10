<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\SongStoreRequest;
use App\Models\Song;
use App\Repositories\SongRepository;
use App\Services\AudioService;
use App\Services\CacheService;
use App\Utils\Mappers\SongMapper;
use App\ApiModels\SongModel;
use Illuminate\Http\JsonResponse;

class SongController extends BaseController
{
    public function __construct(
        private readonly SongRepository $songRepository,
        private readonly CacheService   $cacheService,
        private readonly AudioService   $audioService,
        private readonly SongMapper     $songMapper
    )
    {
    }






    public function getAlbumSongs(int $albumId): JsonResponse
    {
        return $this->successResponse($this->songRepository->getSongsByAlbum($albumId)
            ->map(fn(Song $song): SongModel => $this->songMapper->mapSong($song))
        );
    }


    public function uploadAlbumSong(SongStoreRequest $request, int $albumId): JsonResponse
    {

        $song = $request->body();
        $id = $this->audioService->saveAudio($song->artistId, $song->title, $song->genre, $song->file, $albumId);
        $this->songRepository->putSongToAlbum($albumId, $id);
        $this->cacheService->delete("full_albums:{$albumId}");
        return $this->successResponse(['id' => $id]);
    }


    public function getPlaylistSongs(int $playlistId): JsonResponse
    {
        return $this->successResponse($this->songRepository->getPlaylistSongs($playlistId)
            ->map(fn(Song $song): SongModel => $this->songMapper->mapSong($song)));
    }

    public function deleteSongFromAlbum(int $albumId, int $songId): JsonResponse
    {

        $this->audioService->deleteAudio($songId);
        $this->cacheService->delete("full_albums:{$albumId}");
        return  $this->successResponse();
    }


}
