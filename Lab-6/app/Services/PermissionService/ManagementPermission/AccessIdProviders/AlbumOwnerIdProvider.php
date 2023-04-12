<?php
declare(strict_types=1);

namespace App\Services\PermissionService\ManagementPermission\AccessIdProviders;

use App\Repositories\AlbumRepository;

readonly class AlbumOwnerIdProvider implements IOwnerIdProvider
{
    private AlbumRepository $albumRepository;

    public function __construct()
    {
        $this->albumRepository = new AlbumRepository();
    }

    public function getOwnerId(int $contentId): ?int
    {
        return $this->albumRepository->getArtistId($contentId);
    }
}
