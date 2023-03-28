<?php
declare(strict_types=1);

namespace App\Services\PermissionService\ManagementPermission\AccessIdProviders;

use App\Repositories\SongRepository;

readonly class SongAccessIdProviderImpl implements AccessIdProviderInterface
{
    private SongRepository $songRepository;

    public function __construct()
    {
        $this->songRepository = new SongRepository();
    }

    public function requestedResourceAccessId(int $contentId): ?int
    {
        return $this->songRepository->getArtistId($contentId);
    }
}
