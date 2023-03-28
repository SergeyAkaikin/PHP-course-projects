<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginFormRequest;
use App\Services\AuthService;

class LoginController
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    public function login(LoginFormRequest $request)
    {
        $data = $request->body();

        $authToken = $this->authService->authorizeUser($data->user_name, $data->password);

        if ($authToken === null) {
            return response('User name or password incorrect', 400);
        }

        return response()->redirectTo('/')->cookie(cookie('auth-token', $authToken, 1440));

    }
}
