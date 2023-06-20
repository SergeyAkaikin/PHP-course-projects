<?php

declare(strict_types=1);

namespace MusicService\Utils;


use Carbon\Carbon;

final class  UserUtils
{

    public static function isAbleDate(Carbon $date): bool
    {
        return Carbon::now()->diffInDays($date) > 0;
    }


    public static function isValidDateFormat(string $dateString): bool
    {

        $dateInfo = date_parse_from_format('Y-m-d', $dateString);
        if ($dateInfo['error_count'] !== 0 || $dateInfo['warning_count'] !== 0) {
            return false;
        }

        return true;
    }


    public static function isValidEmailFormat(string $email): bool
    {

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }
        return true;
    }

    public static function isValidPassword(string $password, string $userPassword): bool
    {
        return password_verify($password, $userPassword);
    }
}