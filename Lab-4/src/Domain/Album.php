<?php

declare(strict_types=1);

namespace MusicService\Domain;


class Album
{
    public int $id;
    public int $artist_id;
    public string $title;
    /**
     * @var int[] $songs
     */
    public array $songs;

    /**
     * @param int $id
     * @param int $artist_id
     * @param string $title
     * @param int[] $songs
     * @return Album
     */
    public static function createAlbum(int $id, int $artist_id, string $title, array &$songs): Album
    {
        $album = new self();
        $album->id = $id;
        $album->artist_id = $artist_id;
        $album->title = $title;
        $album->songs = $songs;
        return $album;
    }


}