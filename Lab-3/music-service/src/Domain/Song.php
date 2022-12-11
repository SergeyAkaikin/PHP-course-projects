<?php

declare(strict_types=1);

namespace MusicService\Domain;


/**
 * @property-read int $songId
 * @property int authorId
 * @property string $name
 * @property string $genre
 */
class Song
{
    public int $songId;
    public int $authorId;
    public ?string $name;
    public ?string $genre;
    public ?string $textSong;

    /**
     * @param int $songId
     * @param int $authorId
     * @param string|null $name
     * @param string|null $genre
     * @param string|null $textSong
     */
    public function __construct(int $songId, int $authorId, string $name = null, string $genre = null, string $textSong = null)
    {
        $this->songId = $songId;
        $this->authorId = $authorId;

        if ($name !== '') {
            $this->name = $name;
        }
        if ($genre !== '') {
            $this->genre = $genre;
        }

        if ($textSong !== '') {
            $this->textSong = $textSong;
        }

    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

}