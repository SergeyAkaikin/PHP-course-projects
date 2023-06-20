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

    public function saveAudio(int $artistId, string $title, string $genre, UploadedFile $file, ?int $albumId): int
    {

        $album = $this->albumRepository
            ->getAlbum($albumId);
        $filePath = $this->storageService->storeAudio($file, $album->folder_id);

        return $this->songRepository->createSong($artistId, $title, $genre, $filePath);
    }


    public function deleteAudio(int $songId): void
    {
        $song = $this->songRepository->getSong($songId);
        $this->storageService->removeAudio($song->path);
        $this->songRepository->deleteSong($songId);
    }

}
