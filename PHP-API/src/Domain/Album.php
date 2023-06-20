<?php

declare(strict_types=1);

namespace MusicService\Domain;


class Album
{
    public int $id;
    public int $artist_id;
    public string $title;

    public static function createAlbum(int $id, int $artist_id, string $title): Album
    {
        $album = new self();
        $album->id = $id;
        $album->artist_id = $artist_id;
        $album->title = $title;
        return $album;
    }


}