<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Repositories\UserRepository;
use App\Services\CacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function __construct(
        private readonly UserRepository $repository,
        private readonly CacheService   $cacheService
    )
    {
    }


    public function index(): Response
    {
        Log::info("All users info requested");
        $users = $this->repository->getUsers();
        return response($users, 200);

    }


    public function store(UserStoreRequest $request): JsonResponse
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

        return response()->json(['id' => $id]);
    }


    public function show(int $user_id): Response|JsonResponse
    {
        Log::info("User info requested", ['id' => $user_id]);
        $user = $this->cacheService->getOrAdd("users:{$user_id}", fn() => $this->repository->getUser($user_id), 120);
        return ($user === null) ? response()->json('User not found', 404) : response()->json($user);
    }


    public function update(UserStoreRequest $request, int $user_id): Response|JsonResponse
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
        return response()->noContent();
    }


    public function destroy(int $user_id): Response|JsonResponse
    {
        $success = $this->repository->deleteUser($user_id);

        if (!$success) {
            Log::notice('Destroying nonexistent user information requested', ['id' => $user_id]);
            return response()->json('User not found', 404);
        }

        Log::info('Destroying user information requested', ['id' => $user_id]);
        $this->cacheService->delete("users:{$user_id}");
        return response()->noContent();

    }
}
