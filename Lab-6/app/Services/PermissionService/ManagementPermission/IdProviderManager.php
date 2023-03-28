<?php
declare(strict_types=1);

namespace App\Services\PermissionService\ManagementPermission;

use App\Models\Permissions\ContentType;
use App\Services\PermissionService\ManagementPermission\AccessIdProviders\AccessIdProviderInterface;
use App\Services\PermissionService\ManagementPermission\AccessIdProviders\AlbumAccessIdProviderImpl;
use App\Services\PermissionService\ManagementPermission\AccessIdProviders\PlaylistAccessIdProviderImpl;
use App\Services\PermissionService\ManagementPermission\AccessIdProviders\SongAccessIdProviderImpl;
use App\Services\PermissionService\ManagementPermission\AccessIdProviders\UserAccessIdProviderImpl;

class IdProviderManager
{

    /**
     * @var array<int, string>
     */
    private static array $accessIdProviders = [
        ContentType::Album->value => AlbumAccessIdProviderImpl::class,
        ContentType::User->value => UserAccessIdProviderImpl::class,
        ContentType::Song->value => SongAccessIdProviderImpl::class,
        ContentType::Playlist->value => PlaylistAccessIdProviderImpl::class,
    ];

    public static function getAccessIdProvider(int $contentType): ?AccessIdProviderInterface
    {
        return isset(self::$accessIdProviders[$contentType]) ? new (self::$accessIdProviders[$contentType]) : null;
    }
}
