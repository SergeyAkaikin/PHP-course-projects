<?php

namespace App\Http\Controllers;

use App\Models\Auth\AuthInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BaseController extends Controller
{
    public function getCurrentUserId(Request $request): int
    {
        /** @var AuthInfo $userInfo */
        $userInfo = $request->attributes->get('authInfo');
        Log::info('', ['authInfo' => $userInfo]);
        return $userInfo->userId;
    }

    public function successResponse(mixed $data = null): JsonResponse
    {
        return $data !== null ? response()->json($data) : response()->json();
    }

    public function failResponse(?string $message = null): JsonResponse {
        return  response()->json($message, 400);
    }
}
