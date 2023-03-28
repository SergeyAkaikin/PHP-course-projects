<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlbumStoreRequest;
use App\Http\Requests\AlbumUpdateRequest;
use App\Models\Album;
use App\Repositories\AlbumRepository;
use App\Services\CacheService;
use App\Utils\Mappers\AlbumMapper;
use App\ViewModels\AlbumModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class AlbumController extends Controller
{
    public function __construct(
        private readonly AlbumRepository $repository,
        private readonly CacheService    $cacheService,
        private readonly AlbumMapper $albumMapper
    )
    {
    }

    public function index(): JsonResponse
    {

        $albums = $this->repository->getAlbums();
        $albumModels = $albums->map(fn (Album $album): AlbumModel => $this->albumMapper->mapAlbum($album))
            ->toArray();

        return response()->json($albumModels);
    }


    public function store(AlbumStoreRequest $request): JsonResponse
    {

        $album = $request->body();
        Log::info(
            'Storing new album information requested',
            [
                'artist_id' => $album->artist_id,
                'title' => $album->title,
            ]
        );
        $id = $this->repository->putAlbum($album->artist_id, $album->title);
        return response()->json(['id' => $id]);
    }


    public function show(int $album_id): Response|JsonResponse
    {
        Log::info('Album information requested', ['id' => $album_id]);
        $album = $this->cacheService->getOrAdd("albums:{$album_id}", fn() => $this->repository->getAlbum($album_id), 120);
        return ($album === null) ? response()->json('Album not found', 404) : response()->json($album);
    }


    public function update(AlbumUpdateRequest $request, int $id): Response
    {
        $album = $request->body();
        Log::info(
            'Updating album information requested',
            [
                'id' => $id,
                'title' => $album->title,
            ]
        );
        $this->repository->updateAlbum($id, $album->title);
        $this->cacheService->delete("albums:{$id}");
        return response()->noContent();
    }


    public function destroy(int $album_id): Response|JsonResponse
    {
        $success = $this->repository->deleteAlbum($album_id);

        if (!$success) {
            Log::notice('Destroying nonexistent album requested', ['id' => $album_id]);
            return \response()->json('Album not found', 404);
        }

        Log::info('Destroying album requested', ['id' => $album_id]);
        $this->cacheService->delete("albums:{$album_id}");
        return response()->noContent();
    }
}
