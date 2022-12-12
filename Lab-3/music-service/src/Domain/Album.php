<?php

declare(strict_types=1);

namespace MusicService\Domain;

use Generator;

/**
 * @property int $id
 * @property int $authorId
 * @property string $name
 * @property int[] $songs
 */
class Album
{
    public int $id;
    public int $authorId;
    public string $name;
    public array $songs;


    /**
     * @param int $id
     * @param int $authorId
     * @param string $name
     * @param array $songs
     */
    public function __construct(int $id, int $authorId, string $name, array &$songs)
    {
        $this->id = $id;
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