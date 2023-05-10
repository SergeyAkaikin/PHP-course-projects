<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\AlbumRequest;
use App\Http\Requests\AlbumUpdateRequest;
use App\Models\Album;
use App\Models\Auth\AuthInfo;
use App\Models\Permissions\ContentType;
use App\Repositories\AlbumRepository;
use App\Repositories\SongRepository;
use App\Services\CacheService;
use App\Services\PermissionService\ManagementPermission\OwnerIdProviderResolver;
use App\Utils\Mappers\AlbumMapper;
use App\ApiModels\AlbumModel;
use Illuminate\Http\JsonResponse;

class AlbumController extends BaseController
{
    public function __construct(
        private readonly AlbumRepository    $albumRepository,
        private readonly SongRepository $songRepository,
        private readonly CacheService       $cacheService,
        private readonly AlbumMapper        $albumMapper,
    )
    {
    }

    public function getAlbums(): JsonResponse
    {

        $albums = $this->albumRepository->getAlbums();
        $albumModels = $albums->map(fn(Album $album): AlbumModel => $this->albumMapper->mapAlbum($album));

        return $this->successResponse($albumModels);
    }


    public function createAlbum(AlbumRequest $request): JsonResponse
    {

        $album = $request->body();
        $id = $this->albumRepository->createAlbum($album->artistId, $album->title);
        return $this->successResponse(['id' => $id]);
    }


    public function getAlbum(int $albumId): JsonResponse
    {

        $album = $this->cacheService->getOrAdd("full_albums:{$albumId}", function () use ($albumId) {
            $album = $this->albumRepository->getAlbum($albumId);
            if ($album !== null) {
                $songs = $this->songRepository->getSongsByAlbum($album->id);
                return  $this->albumMapper->mapFullAlbum($album, $songs);
            }
            return null;
        }, 120);

        $canManage = null;
        if ($album !== null) {
            $canManage = $this->canAlbumBeManaged($albumId);
        }

        return ($album === null)
            ? $this->failResponse('Album not found')
            : $this->successResponse(['album' => $album, 'canManage' => $canManage]);
    }


    public function updateAlbum(AlbumUpdateRequest $request, int $albumId): JsonResponse
    {
        $album = $request->body();
        $this->albumRepository->updateAlbum($albumId, $album->title);
        $this->cacheService->delete("albums:{$albumId}");
        $this->cacheService->delete("full_albums:{$albumId}");
        return $this->successResponse();
    }


    public function deleteAlbum(int $albumId): JsonResponse
    {
        $success = $this->albumRepository->deleteAlbum($albumId);

        if (!$success) {
            return $this->failResponse('Album not found');
        }

        $this->cacheService->delete("albums:{$albumId}");
        return $this->successResponse();
    }

    public function getUserAlbums(int $userId): JsonResponse
    {
        $albums = $this->albumRepository->getUserAlbums($userId);
        $albumModels = $albums->map(fn(Album $album): AlbumModel => $this->albumMapper->mapAlbum($album));
        return $this->successResponse($albumModels);
    }

    public function canAlbumBeManaged(int $albumId): bool
    {
        /** @var AuthInfo $authInfo */
        $authInfo = request()->attributes->get('authInfo');
        $idProvider = OwnerIdProviderResolver::getOwnerIdProvider(ContentType::Album->value);
        $accessId = $idProvider->getOwnerId($albumId);
        return $accessId === $authInfo->userId;
    }


}
