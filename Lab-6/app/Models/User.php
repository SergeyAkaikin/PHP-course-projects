<?php

declare(strict_types=1);

namespace App\Models;


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


}
