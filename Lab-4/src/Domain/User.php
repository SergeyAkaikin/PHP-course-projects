<?php

declare(strict_types=1);

namespace MusicService\Domain;


use Carbon\Carbon;

class User
{

    public int $id;
    public string $name;
    public string $surname;
    public string $lastname;
    public Carbon $birth_date;
    public string $email;
    public string $user_name;


    public static function createUser(
        int    $id,
        string $name,
        string $surname,
        string $lastname,
        Carbon $birth_date,
        string $email,
        string $user_name
    ): User
    {
        $user = new self();
        $user->id = $id;
        $user->name = $name;
        $user->surname = $surname;
        $user->lastname = $lastname;
        $user->birth_date = $birth_date;
        $user->email = $email;
        $user->user_name = $user_name;
        return $user;
    }




    public function getAge(): int
    {
        return Carbon::now()->diffInYears($this->birth_date);
    }


}