<?php

declare(strict_types=1);

namespace MusicService\Domain;


use Generator;
use MusicService\Exceptions\InvalidDateFormatException;
use MusicService\Exceptions\InvalidEmailFormatException;
use MusicService\Exceptions\InvalidPasswordFormatException;
use MusicService\Exceptions\UnableDateException;
use MusicService\Utils\SongUtils;

/**
 * @property Song[] $songsList
 * @property Album[] $albumsList
 */
class Artist extends User
{
    public array $songsList = [];
    public array $albumsList = [];


    /**
     * @param int $userId
     * @param string $name
     * @param string $surname
     * @param string $patronymic
     * @param string $birthDate
     * @param string $email
     * @param string $userName
     * @param string $password
     * @param array $songsList
     * @param array $albumsList
     * @throws InvalidDateFormatException
     * @throws InvalidEmailFormatException
     * @throws InvalidPasswordFormatException
     * @throws UnableDateException
     */
    public function __construct(
        int $userId,
        string $name,
        string $surname,
        string $patronymic,
        string $birthDate,
        string $email,
        string $userName,
        string $password,
        array $songsList,
        array $albumsList
    )
    {
        parent::__construct($userId, $name, $surname, $patronymic, $birthDate, $email, $userName, $password);
        $this->albumsList = $albumsList;
        $this->songsList = $songsList;
    }

}