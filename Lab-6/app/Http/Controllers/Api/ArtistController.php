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


    public function getArtist(int $artist_id): JsonResponse
    {
        Log::info('Artist information requested', ['id' => $artist_id]);
        $artist = $this->cacheService->getOrAdd("users:{$artist_id}", function () use ($artist_id) {
            $artist = $this->repository->getArtist($artist_id);
            return ($artist === null) ? null : ($this->artistMapper->mapArtist($artist));
        }, 120);
        return ($artist === null) ? response()->json('Artist not found', 404) : response()->json($artist);
    }


    public function updateArtist(UserStoreRequest $request, int $artist_id): JsonResponse
    {
        $artist = $request->body();

        $success = $this->repository->updateArtist(
            $artist_id,
            $artist->name,
            $artist->surname,
            $artist->lastname,
            $artist->birth_date,
            $artist->email,
            $artist->user_name
        );

        if (!$success) {
            Log::notice('Updating nonexistent artist information', ['id' => $artist_id]);
            return response()->json('Artist not found', 404);
        }

        Log::info(
            'Updating artist information requested',
            [
                'id' => $artist_id,
                'name' => $artist->name,
                'surname' => $artist->surname,
                'lastname' => $artist->lastname,
                'birth_date' => $artist->birth_date,
                'email' => $artist->email,
                'user_name' => $artist->user_name
            ]
        );
        $this->cacheService->delete("users:{$artist_id}");
        return response()->json();
    }


    public function deleteUser(int $artist_id): JsonResponse
    {
        $success = $this->repository->deleteArtist($artist_id);

        if (!$success) {
            Log::notice('Destroying nonexistent artist information requested', ['id' => $artist_id]);
            return response()->json('Artist not found', 404);
        }

        Log::info('Destroying artist information requested', ['id' => $artist_id]);
        $this->cacheService->delete("users:{$artist_id}");
        return response()->json();
    }
}
