<?php
declare(strict_types=1);

namespace App\ViewModels;

class AlbumFullModel extends AlbumModel
{
    /** @var SongModel[] */
    public array $songs;

    public int $songCount;
}
