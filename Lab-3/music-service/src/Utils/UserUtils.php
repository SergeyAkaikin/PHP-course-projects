<?php

declare(strict_types=1);

namespace MusicService\Utils;


final class UserUtils
{
    private const MIN_PASSWORD_LENGTH = 8;
    private const MAX_PASSWORD_LENGTH = 255;

    /**
     * @param string $dateString
     * @return bool
     */
    public static function isAbleDate(string $dateString): bool
    {

        $checkingDate = date_create($dateString);
        $now = date_create();

        $difference = date_timestamp_get($now) - date_timestamp_get($checkingDate);

        if ($difference < 0) {
            return false;
        }

        return true;
    }

    /**
     * @param string $dateString
     * @return bool
     */
    public static function isValidDateFormat(string $dateString): bool
    {

        $dateInfo = date_parse_from_format('Y-m-d', $dateString);
        if ($dateInfo['error_count'] !== 0 || $dateInfo['warning_count'] !== 0) {
            return false;
        }

        return true;
    }

    /**
     * @param string $password
     * @return bool
     */
    public static function isValidPasswordFormat(string $password): bool
    {
        if (strlen($password) < self::MIN_PASSWORD_LENGTH || strlen($password) > self::MAX_PASSWORD_LENGTH) {
            return false;
        }

        return true;
    }

    /**
     * @param string $email
     * @return bool
     */
    public static function isValidEmailFormat(string $email): bool
    {

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }
        return true;
    }
}