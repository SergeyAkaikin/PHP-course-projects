<?php

declare(strict_types=1);

namespace MusicService\Utils;

use MusicService\Objects\Album;
use MusicService\Objects\Song;

final class SongUtils
{
    /**
     * @param Song $song
     * @return Song
     */
    static public function getSongCopy(Song $song): Song
    {
        return new Song($song->getName(), $song->getAuthor(), $song->getGenre());
    }

    /**
     * @param Album $album
     * @return Album
     */
    static public function getAlbumCopy(Album $album): Album
    {
        $songs = [];
        foreach ($album->getSongs() as $song) {
            $songs[] = self::getSongCopy($song);
        }
        $albumCopy = null;
        try {
            $albumCopy = new Album($album->getName(), $songs);
        } catch (\Exception $e) {
        }
        return $albumCopy;
    }
}