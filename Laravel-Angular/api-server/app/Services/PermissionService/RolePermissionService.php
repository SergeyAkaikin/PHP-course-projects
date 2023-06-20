<?php
declare(strict_types=1);

namespace App\Services\PermissionService;

use App\Models\Permissions\PermissionCode;
use App\Models\Permissions\UserRole;
use Illuminate\Support\Collection;


class RolePermissionService
{
    private static array $rolePermissions = [
        UserRole::RegularUser->value => [
            PermissionCode::AccessMusicCollection,
            PermissionCode::ManageOwnLibrary,
            PermissionCode::ManageOwnUserInfo,
            PermissionCode::AccessUserInformation,
            PermissionCode::CreatePlaylist,
        ],
        UserRole::Artist->value => [
            PermissionCode::ManageOwnAlbums,
            PermissionCode::ManageOwnSongs,
            PermissionCode::UploadMusicContent,
        ],

    ];


    /**
     * @param UserRole $role
     * @return Collection<int, PermissionCode>
     */
    public function getPermissionsForRole(UserRole $role): Collection
    {
        return new Collection(self::$rolePermissions[$role->value]);
    }

}
