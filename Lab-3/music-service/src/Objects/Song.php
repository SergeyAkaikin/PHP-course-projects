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
    private string $textSong;

    /**
     * @param string $name
     * @param string $author
     * @param string $genre
     * @param string $textSong = ''
     */
    public function __construct(string $name, string $author, string $genre, string $textSong = '')
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

        $this->textSong = $textSong;

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

    /**
     * @return string
     */
    public function getTextSong(): string
    {
        return $this->textSong;
    }

    /**
     * @param string $textSong
     */
    public function setTextSong(string $textSong): void
    {
        $this->textSong = $textSong;
    }
}