<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\AlbumRepository;
use App\Repositories\SongRepository;
use Illuminate\Http\UploadedFile;

class AudioService
{
    public function __construct(
        private readonly AlbumRepository $albumRepository,
        private readonly SongRepository  $songRepository,
        private readonly StorageService  $storageService
    )
    {
    }

    public function saveAudio(int $artist_id, string $title, string $genre, UploadedFile $file, ?int $albumId = null): int
    {
        if ($albumId !== null) {
            $album = $this->albumRepository->getAlbum($albumId);
            $filePath = $this->storageService->storeAudio($file, $album->folder_id);
        } else {
            $filePath = $this->storageService->storeAudio($file);
        }

        return $this->songRepository->putSong($artist_id, $title, $genre, $filePath);
    }

    public function deleteAudio(string $filePath): bool
    {
        return $this->storageService->removeAudio($filePath);
    }

}
