<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Auth\AuthInfo;
use App\Models\Permissions\ContentType;
use App\Models\Permissions\PermissionCode;
use App\Services\PermissionService\ManagementPermission\IdProviderManager;
use App\ViewModels\AlbumFullModel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AlbumPageService
{
    public function canManage(Request $request, int $album_id): bool
    {
        /** @var AuthInfo $authInfo */
        $authInfo = $request->attributes->get('authInfo');
        if (in_array((string)PermissionCode::ManageAllMusicContent->value, $authInfo->permissions, true)) {
            return true;
        }


        $idProvider = IdProviderManager::getAccessIdProvider(ContentType::Album->value);
        $accessId = $idProvider->requestedResourceAccessId($album_id);
        if ($accessId === $authInfo->user_id) {
            return true;
        }

        return false;
    }

    public function canDelete(Request $request, int $album_id): bool
    {
        /** @var AuthInfo $authInfo */
        $authInfo = $request->attributes->get('authInfo');
        if (in_array((string)PermissionCode::DeleteMusicContent->value, $authInfo->permissions, true)) {
            return true;
        }

        return $this->canManage($request, $album_id);
    }

    /**
     * @param Collection<int, AlbumFullModel> $albums
     * @return Collection<int, AlbumFullModel>
     */
    public function sortedByRate(Collection $albums): Collection
    {
        return $albums->sortByDesc(fn (AlbumFullModel $model, int $key): int => $model->rating);
    }
}
