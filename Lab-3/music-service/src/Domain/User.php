<?php

declare(strict_types=1);

namespace MusicService\Domain;


use MusicService\Exceptions\InvalidDateFormatException;
use MusicService\Exceptions\InvalidEmailFormatException;
use MusicService\Exceptions\InvalidPasswordFormatException;
use MusicService\Exceptions\UnableDateException;
use MusicService\Utils\UserUtils;


/**
 * @property-read string $name
 * @property-read string $surname
 * @property-read string $patronymic
 * @property-read string $birthDate
 * @property-read  string $email
 * @property-read string $userName
 * @property string $password
 */
class User
{
    public int $userId;
    public string $name;
    public string $surname;
    public string $patronymic;

    public string $birthDate;
    public string $email;
    public string $userName;
    private string $password;


    /**
     * @param int $userId
     * @param string $name
     * @param string $surname
     * @param string $patronymic
     * @param string $birthDate
     * @param string $email
     * @param string $userName
     * @param string $password
     * @throws InvalidDateFormatException
     * @throws InvalidEmailFormatException
     * @throws UnableDateException;
     * @throws InvalidPasswordFormatException
     */
    public function __construct(int $userId, string $name, string $surname, string $patronymic, string $birthDate, string $email, string $userName, string $password)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->surname = $surname;
        $this->patronymic = $patronymic;

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
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @param string $patronymic
     */
    public function setPatronymic(string $patronymic): void
    {
        $this->patronymic = $patronymic;
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