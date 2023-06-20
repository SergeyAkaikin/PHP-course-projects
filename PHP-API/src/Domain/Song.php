<?php
declare(strict_types=1);

namespace MusicService\Domain;

class Song
{
    public int $id;
    public int $artist_id;
    public string $title;
    public string $genre;


    public static function createSong(
        int    $id,
        int    $artist_id,
        string $title,
        string $genre
    ): Song
    {
        $song = new self();
        $song->id = $id;
        $song->artist_id = $artist_id;
        $song->title = $title;
        $song->genre = $genre;

        return $song;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->title;
    }

}