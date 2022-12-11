<?php

declare(strict_types=1);

namespace MusicService\Domain;

use Generator;

/**
 * @property int $albumId
 * @property int $authorId
 * @property string $name
 * @property int[] $songs
 */
class Album
{
    public int $albumId;
    public int $authorId;
    public string $name;
    public array $songs;


    /**
     * @param int $albumId
     * @param int $authorId
     * @param string $name
     * @param array $songs
     */
    public function __construct(int $albumId, int $authorId, string $name, array &$songs)
    {
        $this->albumId = $albumId;
        $this->authorId = $authorId;
        $this->name = $name;
        $this->songs = $songs;
    }


    /**
     * @return Generator
     */
    public function getSongs(): Generator
    {
        foreach ($this->songs as $song) {
            yield $song;
        }

    }


}