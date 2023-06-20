<?php

declare(strict_types=1);

namespace MusicService\Domain;


use Carbon\Carbon;

class Artist extends User
{
    /**
     * @var int[] $songs_id
     */
    public array $songs_id = [];
    /**
     * @var int[] $albums_id
     */
    public array $albums_id = [];


    /**
     * @param int $id
     * @param string $name
     * @param string $surname
     * @param string $lastname
     * @param Carbon $birth_date
     * @param string $email
     * @param string $artist_name
     * @param int[] $songs_id
     * @param int[] $albums_id
     * @return Artist
     */
    public static function createArtist(
        int    $id,
        string $name,
        string $surname,
        string $lastname,
        Carbon $birth_date,
        string $email,
        string $artist_name,
        array  $songs_id,
        array  $albums_id
    ): Artist
    {
         $artist = new self();
         $artist->id = $id;
         $artist->name = $name;
         $artist->surname = $surname;
         $artist->lastname = $lastname;
         $artist->birth_date = $birth_date;
         $artist->email = $email;
         $artist->user_name = $artist_name;
         $artist->songs_id = $songs_id;
         $artist->albums_id = $albums_id;

         return $artist;
    }

}