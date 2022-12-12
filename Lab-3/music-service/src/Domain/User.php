<?php

declare(strict_types=1);

namespace MusicService\Domain;


use MusicService\Exceptions\InvalidDateFormatException;
use MusicService\Exceptions\InvalidEmailFormatException;
use MusicService\Exceptions\InvalidPasswordFormatException;
use MusicService\Exceptions\UnableDateException;
use MusicService\Utils\UserUtils;

/**
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $lastname
 * @property string $birthDate
 * @property string $email
 * @property string $userName
 * @property string $password
 */

class User
{

    public int $id;
    public string $name;
    public string $surname;
    public string $lastname;

    public string $birthDate;
    public string $email;
    public string $userName;
    private string $password;


    /**
     * @param int $id
     * @param string $name
     * @param string $surname
     * @param string $lastname
     * @param string $birthDate
     * @param string $email
     * @param string $userName
     * @param string $password
     * @throws InvalidDateFormatException
     * @throws InvalidEmailFormatException
     * @throws UnableDateException;
     * @throws InvalidPasswordFormatException
     */
    public function __construct(int $id, string $name, string $surname, string $lastname, string $birthDate, string $email, string $userName, string $password)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->lastname = $lastname;

        if (!UserUtils::isValidDateFormat($birthDate)) {
            throw new InvalidDateFormatException('Invalid format of date', 1);
        }

        if (!UserUtils::isAbleDate($birthDate)) {
            throw new UnableDateException('Birthday can\'t be later than today', 2);
        }

        $this->birthDate = $birthDate;

        if (!UserUtils::isValidEmailFormat($email)) {
            throw new InvalidEmailFormatException();
        }

        $this->email = $email;

        $this->userName = trim($userName);

        if (!UserUtils::isValidPasswordFormat($password)) {
            throw new InvalidPasswordFormatException('Too short or too long password length');
        }
        $this->password = password_hash(trim($password), PASSWORD_BCRYPT);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->userName;
    }



    /**
     * @param string $birthDate
     * @throws InvalidDateFormatException
     * @throws UnableDateException
     */
    public function setBirthDate(string $birthDate): void
    {
        if ($this->birthDate === $birthDate) {
            return;
        }

        if (!UserUtils::isValidDateFormat($birthDate)) {
            throw new InvalidDateFormatException('Invalid format of date', 1);
        }

        if (!UserUtils::isAbleDate($birthDate)) {
            throw new UnableDateException('Birthday can\'t be later than today', 2);
        }

        $this->birthDate = $birthDate;
    }

    /**
     * @param string $email
     * @throws InvalidEmailFormatException
     */
    public function setEmail(string $email): void
    {
        if ($this->email === $email) {
            return;
        }

        if (!UserUtils::isValidEmailFormat($email)) {
            throw new InvalidEmailFormatException();
        }
        $this->email = $email;
    }


    /**
     * @return int
     */
    public function getAge(): int
    {
        $userBirth = date_create($this->birthDate);
        $now = date_create();
        $difference = date_diff($userBirth, $now);
        return $difference->y;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function isValidPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }


}