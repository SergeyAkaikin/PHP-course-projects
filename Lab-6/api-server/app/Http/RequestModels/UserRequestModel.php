<?php
declare(strict_types=1);

namespace App\Http\RequestModels;

use Carbon\Carbon;

class UserRequestModel
{
    public string $name;
    public string $surname;
    public string $lastname;
    public string $email;
    public Carbon $birth_date;
    public string $user_name;
}
