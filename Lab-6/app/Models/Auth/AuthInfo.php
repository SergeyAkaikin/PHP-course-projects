<?php
declare(strict_types=1);

namespace App\Models\Auth;

class AuthInfo
{
    public int $user_id;

    /** @var string[] */
    public array $permissions;
}
