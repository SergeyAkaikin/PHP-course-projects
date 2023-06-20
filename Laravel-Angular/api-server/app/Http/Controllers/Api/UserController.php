<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\ApiModels\UserModel;
use App\Http\Controllers\BaseController;
use App\Http\Requests\UserCreateRequest;
use App\Models\User;
use App\Repositories\PlaylistRepository;
use App\Repositories\UserRepository;
use App\Services\CacheService;
use App\Utils\Mappers\UserMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController
{

    public function __construct(
        private readonly UserRepository     $userRepository,
        private readonly CacheService       $cacheService,
        private readonly PlaylistRepository $playlistRepository,
        private readonly UserMapper         $userMapper,
    )
    {
    }


    public function getUsers(): JsonResponse
    {
        $users = $this->userRepository->getUsers();
        return $this->successResponse($users);

    }

    public function getArtists(): JsonResponse
    {
        $artists = $this->userRepository->getArtists()->map(fn(User $artist): UserModel => $this->userMapper->mapUser($artist));
        return $this->successResponse($artists);

    }

    public function  getCurrentUser(Request $request): JsonResponse
    {
        $userId = $this->getCurrentUserId($request);
        $user = $this->userRepository->getUser($userId);
        $userModel = $this->userMapper->mapUser($user);
        return $this->successResponse($userModel);
    }


    public function registerUser(UserCreateRequest $request): JsonResponse
    {

        $data = $request->body();
        $id = $this->userRepository->createUser(
            $data->name,
            $data->surname,
            $data->lastname,
            $data->birth_date,
            $data->email,
            $data->user_name
        );

        $this->playlistRepository->createMainPlaylist($id);
        return $this->successResponse(['id' => $id]);
    }


    public function getUser(int $userId): JsonResponse
    {
        $user = $this->cacheService->getOrAdd("users:{$userId}", function () use ($userId) {
            $user = $this->userRepository->getUser($userId);
            if ($user === null) {
                return null;
            }
            return $this->userMapper->mapUser($user);
}, 120);
        return ($user === null) ? $this->failResponse('User not found') : $this->successResponse($user);
    }


    public function updateUser(UserCreateRequest $request, int $userId): JsonResponse
    {
        $data = $request->body();

        $success = $this->userRepository->updateUser(
            $userId,
            $data->name,
            $data->surname,
            $data->lastname,
            $data->birth_date,
            $data->email,
            $data->user_name,
        );
        if (!$success) {
            return $this->failResponse();
        }
        $this->cacheService->delete("users:{$userId}");
        return $this->successResponse();
    }


    public function deleteUser(int $userId): JsonResponse
    {
        $success = $this->userRepository->deleteUser($userId);

        if (!$success) {
            return $this->failResponse();
        }
        $this->cacheService->delete("users:{$userId}");
        return $this->successResponse();

    }
}
