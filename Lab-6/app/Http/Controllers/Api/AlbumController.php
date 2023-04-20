<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\AlbumStoreRequest;
use App\Http\Requests\AlbumUpdateRequest;
use App\Models\Album;
use App\Repositories\AlbumRepository;
use App\Services\AlbumRatingService;
use App\Services\CacheService;
use App\Utils\Mappers\AlbumMapper;
use App\ViewModels\AlbumModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AlbumController extends BaseController
{
    public function __construct(
        private readonly AlbumRepository    $albumRepository,
        private readonly CacheService       $cacheService,
        private readonly AlbumMapper        $albumMapper,
    )
    {
    }

    public function getAlbums(): JsonResponse
    {

        $albums = $this->albumRepository->getAlbums();
        $albumModels = $albums->map(fn(Album $album): AlbumModel => $this->albumMapper->mapAlbum($album));

        return response()->json($albumModels);
    }


    public function storeAlbum(AlbumStoreRequest $request): JsonResponse
    {

        $album = $request->body();
        Log::info(
            'Storing new album information requested',
            [
                'artist_id' => $album->artist_id,
                'title' => $album->title,
            ]
        );
        $id = $this->albumRepository->putAlbum($album->artist_id, $album->title);
        return response()->json(['id' => $id]);
    }


    public function getAlbum(int $album_id): JsonResponse
    {
        Log::info('Album information requested', ['id' => $album_id]);

        $album = $this->cacheService->getOrAdd("albums:{$album_id}", function () use ($album_id) {
            $album = $this->albumRepository->getAlbum($album_id);
            return ($album === null) ? null : $this->albumMapper->mapAlbum($album);
        }, 120);

        return ($album === null) ? response()->json('Album not found', 404) : response()->json($album);
    }


    public function updateAlbum(AlbumUpdateRequest $request, int $id): JsonResponse
    {
        $album = $request->body();
        Log::info(
            'Updating album information requested',
            [
                'id' => $id,
                'title' => $album->title,
            ]
        );
        $this->albumRepository->updateAlbum($id, $album->title);
        $this->cacheService->delete("albums:{$id}");
        $this->cacheService->delete("full_albums:{$id}");
        return response()->json();
    }


    public function deleteAlbum(int $album_id): JsonResponse
    {
        $success = $this->albumRepository->deleteAlbum($album_id);

        if (!$success) {
            Log::notice('Destroying nonexistent album requested', ['id' => $album_id]);
            return \response()->json('Album not found', 404);
        }

        Log::info('Destroying album requested', ['id' => $album_id]);
        $this->cacheService->delete("albums:{$album_id}");
        return response()->json();
    }


}
