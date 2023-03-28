<?php

namespace App\Services\PermissionService\ManagementPermission\AccessIdProviders;

interface AccessIdProviderInterface
{
    public function requestedResourceAccessId(int $contentId): ?int;
}
