<?php
declare(strict_types=1);

namespace App\Http\RequestModels;

class LoginRequestModel
{
    public string $userName;
    public string $password;
}
