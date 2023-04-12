<?php

namespace App\Http\Controllers;

use App\Models\Auth\AuthInfo;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function getCurrentUserId(Request $request): int
    {
        /** @var AuthInfo $userInfo */
        $userInfo = $request->attributes->get('authInfo');
        return $userInfo->user_id;
    }
}
