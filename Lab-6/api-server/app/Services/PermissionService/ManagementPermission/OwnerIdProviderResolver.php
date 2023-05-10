<?php
declare(strict_types=1);

namespace App\Services\PermissionService\ManagementPermission;

use App\Models\Permissions\ContentType;
use App\Services\PermissionService\ManagementPermission\AccessIdProviders\IOwnerIdProvider;
use App\Services\PermissionService\ManagementPermission\AccessIdProviders\AlbumOwnerIdProvider;
use App\Services\PermissionService\ManagementPermission\AccessIdProviders\PlaylistOwnerIdProvider;
use App\Services\PermissionService\ManagementPermission\AccessIdProviders\SongOwnerIdProvider;
use App\Services\PermissionService\ManagementPermission\AccessIdProviders\UserOwnerIdProvider;

class OwnerIdProviderResolver
{

    /**
     * @var array<int, string>
     */
    private static array $accessIdProviders = [
        ContentType::Album->value => AlbumOwnerIdProvider::class,
        ContentType::User->value => UserOwnerIdProvider::class,
        ContentType::Song->value => SongOwnerIdProvider::class,
        ContentType::Playlist->value => PlaylistOwnerIdProvider::class,
    ];

    public static function getOwnerIdProvider(int $contentType): ?IOwnerIdProvider
    {
        return isset(self::$accessIdProviders[$contentType]) ? new (self::$accessIdProviders[$contentType]) : null;
    }
}
