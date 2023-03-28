<?php
declare(strict_types=1);

namespace App\Services\PermissionService\ManagementPermission\AccessIdProviders;

use App\Repositories\AlbumRepository;

readonly class AlbumAccessIdProviderImpl implements AccessIdProviderInterface
{
    private AlbumRepository $albumRepository;

    public function __construct()
    {
        $this->albumRepository = new AlbumRepository();
    }

    public function requestedResourceAccessId(int $contentId): ?int
    {
        return $this->albumRepository->getArtistId($contentId);
    }
}
