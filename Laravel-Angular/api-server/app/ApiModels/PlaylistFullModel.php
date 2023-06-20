<?php
declare(strict_types=1);

namespace App\ApiModels;

class PlaylistFullModel extends PlaylistModel
{
    /** @var SongModel[] * */
    public array $songs;

    public int $songCount;
}
