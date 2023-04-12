<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Auth\AuthInfo;
use App\Models\Permissions\ContentType;
use App\Models\Permissions\PermissionCode;
use App\Services\PermissionService\ManagementPermission\OwnerIdProviderResolver;
use App\ViewModels\AlbumFullModel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AlbumPageService
{

    public function canManage(Request $request, int $album_id): bool
    {
        /** @var AuthInfo $authInfo */
        $authInfo = $request->attributes->get('authInfo');
        $idProvider = OwnerIdProviderResolver::getOwnerIdProvider(ContentType::Album->value);
        $accessId = $idProvider->getOwnerId($album_id);
        return $accessId === $authInfo->user_id;
    }
}
