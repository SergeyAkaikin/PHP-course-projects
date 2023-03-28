<?php
declare(strict_types=1);

namespace App\Services\PermissionService\ManagementPermission\AccessIdProviders;

readonly class UserAccessIdProviderImpl implements AccessIdProviderInterface
{

    public function requestedResourceAccessId(int $contentId): ?int
    {
        return $contentId;
    }
}
