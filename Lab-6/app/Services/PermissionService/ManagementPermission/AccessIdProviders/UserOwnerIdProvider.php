<?php
declare(strict_types=1);

namespace App\Services\PermissionService\ManagementPermission\AccessIdProviders;

readonly class UserOwnerIdProvider implements IOwnerIdProvider
{

    public function getOwnerId(int $contentId): ?int
    {
        return $contentId;
    }
}
