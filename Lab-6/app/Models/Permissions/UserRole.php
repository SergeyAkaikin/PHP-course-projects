<?php
declare(strict_types=1);

namespace App\Models\Permissions;
enum UserRole: int
{
    case RegularUser = 1;

    case Artist = 2;
    case Moderator = 4;
    case Admin = 8;
}
