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


    public function putAlbum(AlbumStoreRequest $request): JsonResponse
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


    public function getAlbum(int $albumId): JsonResponse
    {
        Log::info('Album information requested', ['id' => $albumId]);

        $album = $this->cacheService->getOrAdd("albums:{$albumId}", function () use ($albumId) {
            $album = $this->albumRepository->getAlbum($albumId);
            return ($album === null) ? null : $this->albumMapper->mapAlbum($album);
        }, 120);

        return ($album === null) ? response()->json('Album not found', 404) : response()->json($album);
    }


    public function updateAlbum(AlbumUpdateRequest $request, int $albumId): JsonResponse
    {
        $album = $request->body();
        Log::info(
            'Updating album information requested',
            [
                'id' => $albumId,
                'title' => $album->title,
            ]
        );
        $this->albumRepository->updateAlbum($albumId, $album->title);
        $this->cacheService->delete("albums:{$albumId}");
        $this->cacheService->delete("full_albums:{$albumId}");
        return response()->json();
    }


    public function deleteAlbum(int $albumId): JsonResponse
    {
        $success = $this->albumRepository->deleteAlbum($albumId);

        if (!$success) {
            Log::notice('Destroying nonexistent album requested', ['id' => $albumId]);
            return \response()->json('Album not found', 404);
        }

        Log::info('Destroying album requested', ['id' => $albumId]);
        $this->cacheService->delete("albums:{$albumId}");
        return response()->json();
    }


}
