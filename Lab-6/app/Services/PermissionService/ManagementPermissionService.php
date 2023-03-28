<?php
declare(strict_types=1);

namespace App\Services\PermissionService;

use App\Models\Permissions\PermissionCode;

class ManagementPermissionService
{
    private static array $regularPermissions = [
        PermissionCode::ManageOwnUserInfo,
        PermissionCode::ManageOwnSongs,
        PermissionCode::ManageOwnAlbums,
        PermissionCode::ManageOwnLibrary,
    ];

    public function isRegularPermission(PermissionCode $permissionCode): bool
    {
        return in_array($permissionCode, self::$regularPermissions);
    }
}
