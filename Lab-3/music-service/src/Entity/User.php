<?php

declare(strict_types=1);

namespace MusicService\Entity;


use Generator;
use MusicService\Entity\InvalidIFormatExceptions\InvalidDateFormatException;
use MusicService\Entity\InvalidIFormatExceptions\InvalidEmailFormatException;
use MusicService\Entity\InvalidIFormatExceptions\InvalidPasswordFormatException;
use MusicService\Entity\InvalidIFormatExceptions\UnableDateException;
use MusicService\NotificationsService\Notification;
use MusicService\NotificationsService\NotificationListener;
use MusicService\Utils\UserUtils;


/**
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property string $birthDate
 * @property string $email
 * @property-read string $userName
 * @property string $password
 * @property Notification[] $notifications
 */
class User implements NotificationListener
{
    private string $name;
    private string $surname;
    private string $patronymic;

    private string $birthDate;
    private string $email;
    public readonly string $userName;
    private string $password;

    private array $notificationsList;

    /**
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
    public function __construct(string $name, string $surname, string $patronymic, string $birthDate, string $email, string $userName, string $password)
    {
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
     * @return string
     */
    public function getBirthDate(): string
    {
        return $this->birthDate;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
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
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getPatronymic(): string
    {
        return $this->patronymic;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @return string
     */
    public function getUserName(): string
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

    /**
     * @param Notification $notificationObject
     * @return void
     */
    public function update(Notification $notificationObject): void
    {
        // TODO: Implement update() method.
        $this->notificationsList[] = $notificationObject;
    }

    public function getUpdates(): Generator
    {
        while (!empty($this->notificationsList)) {
            yield array_pop($this->notificationsList);
        }
    }
}