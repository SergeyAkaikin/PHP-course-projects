<?php
declare(strict_types=1);

namespace App\Models\Auth;

class AuthInfo
{
    public int $userId;

    /** @var string[] */
    public array $permissions;
}
