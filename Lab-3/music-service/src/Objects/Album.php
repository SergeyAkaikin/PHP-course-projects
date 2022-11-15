<?php

declare(strict_types=1);

namespace MusicService\Objects;

use Generator;
use MusicService\Objects\MismatchExceptions\EmptyAlbumException;
use MusicService\Utils\SongUtils;

/**
 * @property string $name
 * @property Song[] $songs
 */
final class Album
{
    private string $name;
    private array $songs;

    /**
     * @param string $name
     * @param Song[] $songs
     * @throws EmptyAlbumException
     */
    public function __construct(string $name, array &$songs)
    {
        $this->name = $name;
        if (empty($songs)) {
            throw new EmptyAlbumException();
        }
        $this->songs = $songs;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @return Generator
     */
    public function getSongs(): Generator
    {
        foreach ($this->songs as $song) {
            yield SongUtils::getSongCopy($song);
        }

    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

}