<?php
declare(strict_types=1);

namespace App\Utils\Mappers;

use App\Models\Song;
use App\ApiModels\SongModel;

class SongMapper
{
    public function mapSong(Song $song): SongModel
    {
        $mapped = new SongModel();
        $mapped->id = $song->id;
        $mapped->artistId = $song->artist_id;
        $mapped->title = $song->title;
        $mapped->genre = $song->genre;
        $mapped->path = config('app.static_prefix') . $song->path;
        return $mapped;
    }

}
