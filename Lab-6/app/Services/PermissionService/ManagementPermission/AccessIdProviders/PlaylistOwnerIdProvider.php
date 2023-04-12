<?php
declare(strict_types=1);

namespace App\Services\PermissionService\ManagementPermission\AccessIdProviders;

use App\Repositories\PlaylistRepository;

readonly class PlaylistOwnerIdProvider implements IOwnerIdProvider
{
    private PlaylistRepository $playlistRepository;

    public function __construct()
    {
        $this->playlistRepository = new PlaylistRepository();
    }

    public function getOwnerId(int $contentId): ?int
    {
        return $this->playlistRepository->getUserId($contentId);
    }
}
