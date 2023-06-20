<?php

namespace App\Services\PermissionService\ManagementPermission\AccessIdProviders;

interface IOwnerIdProvider
{
    public function getOwnerId(int $contentId): ?int;
}
