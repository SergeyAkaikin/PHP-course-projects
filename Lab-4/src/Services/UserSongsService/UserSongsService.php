<?php

namespace MusicService\Services\UserSongsService;

use Generator;
use MusicService\Domain\Song;

/**
 * @property int $userId
 * @property int[] $playList
 */
class UserSongsService
{
    public int $userId;
    private array $playList = [];

    /**
     * @param int $userId
     * @param array $playList
     */
    public function __construct(int $userId, array $playList)
    {
        $this->userId = $userId;
        $this->playList = $playList;
    }

    /**
     * @return Generator
     */
    public function getPlayList(): Generator
    {
        foreach ($this->playList as $song) {
            yield $song;
        }
    }

    /**
     * @param Song $song
     * @return void
     */
    public function addToPlayList(Song $song): void
    {
        $this->playList[] = $song;
    }


    /**
     * @param Song $song
     * @return void
     */
    public function deleteFromPlayList(Song $song): void
    {
        if (in_array($song, $this->playList, true)) {
            $this->playList = array_diff($this->playList, [$song]);
        }
    }
}