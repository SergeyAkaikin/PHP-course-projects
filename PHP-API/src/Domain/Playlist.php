<?php
declare(strict_types=1);
namespace MusicService\Domain;


class Playlist
{
    public int $id;
    public int $user_id;
    /**
     * @var int[] $songs_id
     */
    public array $songs_id = [];

}