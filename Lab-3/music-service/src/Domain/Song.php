<?php

declare(strict_types=1);

namespace MusicService\Domain;

/**
 * @property int $id
 * @property int $authorId
 * @property string|null $name
 * @property string|null $genre
 * @property string|null $textSong;
 */

class Song
{
    public int $id;
    public int $authorId;
    public ?string $name;
    public ?string $genre;
    public ?string $textSong;

    /**
     * @param int $id
     * @param int $authorId
     * @param string|null $name
     * @param string|null $genre
     * @param string|null $textSong
     */
    public function __construct(int $id, int $authorId, string $name = null, string $genre = null, string $textSong = null)
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->name = $name;
        $this->genre = $genre;
        $this->textSong = $textSong;

    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

}