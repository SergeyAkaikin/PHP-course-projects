<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Repositories\ArtistRepository;
use App\Services\CacheService;
use App\Utils\Mappers\ArtistMapper;
use App\ViewModels\ArtistModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ArtistController extends BaseController
{
    public function __construct(
        private readonly ArtistRepository $repository,
        private readonly CacheService     $cacheService,
        private readonly ArtistMapper     $artistMapper,
    )
    {
    }


    public function getArtists(): JsonResponse
    {
        Log::info('All artist information requested');
        $artists = $this->repository->getArtists()->map(fn(User $artist): ArtistModel => $this->artistMapper->mapArtist($artist));
        return response()->json($artists);

    }


    public function storeArtist(UserStoreRequest $request): JsonResponse
    {

        $data = $request->body();
        Log::info(
            'Storing new artist information requested',
            [
                'name' => $data->name,
                'surname' => $data->surname,
                'lastname' => $data->lastname,
                'birth_date' => $data->birth_date,
                'email' => $data->email,
                'user_name' => $data->user_name
            ]
        );
        $id = $this->repository->putArtist(
            $data->name,
            $data->surname,
            $data->lastname,
            $data->birth_date,
            $data->email,
            $data->user_name
        );

        return response()->json(['id' => $id]);
    }


    public function getArtist(int $artistId): JsonResponse
    {
        Log::info('Artist information requested', ['id' => $artistId]);
        $artist = $this->cacheService->getOrAdd("users:{$artistId}", function () use ($artistId) {
            $artist = $this->repository->getArtist($artistId);
            return ($artist === null) ? null : ($this->artistMapper->mapArtist($artist));
        }, 120);
        return ($artist === null) ? response()->json('Artist not found', 404) : response()->json($artist);
    }


    public function updateArtist(UserStoreRequest $request, int $artistId): JsonResponse
    {
        $artist = $request->body();

        $success = $this->repository->updateArtist(
            $artistId,
            $artist->name,
            $artist->surname,
            $artist->lastname,
            $artist->birth_date,
            $artist->email,
            $artist->user_name
        );

        if (!$success) {
            Log::notice('Updating nonexistent artist information', ['id' => $artistId]);
            return response()->json('Artist not found', 404);
        }

        Log::info(
            'Updating artist information requested',
            [
                'id' => $artistId,
                'name' => $artist->name,
                'surname' => $artist->surname,
                'lastname' => $artist->lastname,
                'birth_date' => $artist->birth_date,
                'email' => $artist->email,
                'user_name' => $artist->user_name
            ]
        );
        $this->cacheService->delete("users:{$artistId}");
        return response()->json();
    }


    public function deleteUser(int $artistId): JsonResponse
    {
        $success = $this->repository->deleteArtist($artistId);

        if (!$success) {
            Log::notice('Destroying nonexistent artist information requested', ['id' => $artistId]);
            return response()->json('Artist not found', 404);
        }

        Log::info('Destroying artist information requested', ['id' => $artistId]);
        $this->cacheService->delete("users:{$artistId}");
        return response()->json();
    }
}
