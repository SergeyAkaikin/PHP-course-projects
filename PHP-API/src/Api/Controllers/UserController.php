<?php
declare(strict_types=1);

namespace MusicService\Api\Controllers;

use MusicService\Api\Requests\GetRequests\GetByIdRequest;
use MusicService\Api\Requests\PutRequests\PutUserRequest;
use MusicService\Api\Status;
use MusicService\DataAccess\DataRepository\UserRepository;
use MusicService\Http\Response;

class UserController extends BaseController
{

    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function getUsers(): Response
    {

        return $this->successResponse($this->userRepository->getUsers());
    }

    public function getUser(GetByIdRequest $request): Response
    {
        $body = $request->getModel();
        $user = $this->userRepository->getUser($body->id);
        if ($user === null) {
            return $this->failResponse(Status::NotFound, 'User not found');
        }

        return $this->successResponse($user);
    }

    public function deleteUser(GetByIdRequest $request): Response
    {
        $body = $request->getModel();
        $this->userRepository->deleteUser($body->id);
        return $this->successResponse(null);
    }
    public function putUser(PutUserRequest $request): Response
    {
        $body = $request->getModel();
        $this->userRepository->putUser(
            $body->name,
            $body->surname,
            $body->lastname,
            $body->birth_date,
            $body->email,
            $body->user_name
        );

        return $this->successResponse(null);
    }
}