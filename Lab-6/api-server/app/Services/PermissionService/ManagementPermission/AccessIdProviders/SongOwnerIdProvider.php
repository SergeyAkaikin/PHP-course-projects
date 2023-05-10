<?php
declare(strict_types=1);

namespace App\Services\PermissionService\ManagementPermission\AccessIdProviders;

use App\Repositories\SongRepository;

readonly class SongOwnerIdProvider implements IOwnerIdProvider
{
    private SongRepository $songRepository;

    public function __construct()
    {
        $this->songRepository = new SongRepository();
    }

    public function getOwnerId(int $contentId): ?int
    {
        return $this->songRepository->getArtistId($contentId);
    }
}
