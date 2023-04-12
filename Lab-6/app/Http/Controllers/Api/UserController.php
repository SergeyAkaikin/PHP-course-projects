<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\UserStoreRequest;
use App\Repositories\PlaylistRepository;
use App\Repositories\UserRepository;
use App\Services\CacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserController extends BaseController
{

    public function __construct(
        private readonly UserRepository $repository,
        private readonly CacheService   $cacheService,
        private readonly PlaylistRepository $playlistRepository,
    )
    {
    }


    public function getUsers(): JsonResponse
    {
        Log::info("All users info requested");
        $users = $this->repository->getUsers();
        return response()->json($users);

    }


    public function storeUser(UserStoreRequest $request): JsonResponse
    {

        $data = $request->body();
        Log::info(
            "Storing new user information requested",
            [
                'name' => $data->name,
                'surname' => $data->surname,
                'lastname' => $data->lastname,
                'birth_date' => $data->birth_date,
                'email' => $data->email,
                'user_name' => $data->user_name,
            ]
        );
        $id = $this->repository->putUser(
            $data->name,
            $data->surname,
            $data->lastname,
            $data->birth_date,
            $data->email,
            $data->user_name
        );

        $this->playlistRepository->createMainPlaylist($id);
        return response()->json(['id' => $id]);
    }


    public function getUser(int $user_id): JsonResponse
    {
        Log::info("User info requested", ['id' => $user_id]);
        $user = $this->cacheService->getOrAdd("users:{$user_id}", fn() => $this->repository->getUser($user_id), 120);
        return ($user === null) ? response()->json('User not found', 404) : response()->json($user);
    }


    public function updateUser(UserStoreRequest $request, int $user_id): JsonResponse
    {
        $data = $request->body();

        $success = $this->repository->updateUser(
            $user_id,
            $data->name,
            $data->surname,
            $data->lastname,
            $data->birth_date,
            $data->email,
            $data->user_name,
        );
        if (!$success) {
            Log::notice('Updating nonexistent user information requested', ['id' => $user_id]);
            return response()->json('User not found', 404);
        }
        Log::info('Updating user information requested',
            [
                'id' => $user_id,
                'name' => $data->name,
                'surname' => $data->surname,
                'lastname' => $data->lastname,
                'birth_date' => $data->birth_date,
                'email' => $data->email,
                'user_name' => $data->user_name,
            ]
        );
        $this->cacheService->delete("users:{$user_id}");
        return response()->json();
    }


    public function deleteUser(int $user_id): JsonResponse
    {
        $success = $this->repository->deleteUser($user_id);

        if (!$success) {
            Log::notice('Destroying nonexistent user information requested', ['id' => $user_id]);
            return response()->json('User not found', 404);
        }

        Log::info('Destroying user information requested', ['id' => $user_id]);
        $this->cacheService->delete("users:{$user_id}");
        return response()->json();

    }
}
