<?php

declare(strict_types=1);

namespace MusicService\Objects;


/**
 * @property string $name
 * @property string $author
 * @property string $genre
 */
final class Song
{
    private string $name = 'no name';
    private string $author = 'no author';
    private string $genre = 'no genre';

    /**
     * @param string $name
     * @param string $author
     * @param string $genre
     */
    public function __construct(string $name, string $author, string $genre)
    {
        if ($author !== '') {
            $this->author = $author;
        }
        if ($name !== '') {
            $this->name = $name;
        }
        if ($genre !== '') {
            $this->genre = $genre;
        }

    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->author . ' - ' . $this->name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getGenre(): string
    {
        return $this->genre;
    }

    /**
     * @param string $newName
     * @return void
     */
    public function setName(string $newName): void
    {
        if ($newName !== '') {
            $this->name = $newName;
        }
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        if ($author !== '') {
            $this->author = $author;
        }
    }

}