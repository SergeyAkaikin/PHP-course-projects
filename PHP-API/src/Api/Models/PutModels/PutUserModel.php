<?php

namespace MusicService\Api\Models\PutModels;

use Carbon\Carbon;

class PutUserModel
{
    public string $name;
    public string $surname;
    public string $lastname;
    public Carbon $birth_date;
    public string $email;
    public string $user_name;

}