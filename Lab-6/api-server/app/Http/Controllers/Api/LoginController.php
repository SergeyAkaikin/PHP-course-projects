<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\LoginFormRequest;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Utils\Mappers\UserMapper;
use Illuminate\Http\Request;

class LoginController extends BaseController
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly UserRepository $userRepository,
    )
    {
    }

    public function login(LoginFormRequest $request)
    {
        $data = $request->body();

         $authToken = $this->authService->authorizeUser($data->userName, $data->password);
         $user = $this->userRepository->getByUserName($data->userName);

        if ($authToken === null) {
            return response('User name or password incorrect', 400);
        }

        $userInfo = (new UserMapper())->mapUser($user);
        return $this->successResponse($userInfo)->cookie(cookie('auth-token', $authToken, 1440));

    }

    public  function logout(Request $request) {
        $userId = $this->getCurrentUserId($request);
        $this->authService->deleteAuthInfo($userId);
        return $this->successResponse()->cookie(cookie('auth-token'));
    }
}
