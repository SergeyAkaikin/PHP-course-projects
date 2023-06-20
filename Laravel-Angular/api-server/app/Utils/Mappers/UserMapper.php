<?php
declare(strict_types=1);

namespace App\Utils\Mappers;

use App\Models\User;
use App\ApiModels\UserModel;

class UserMapper
{

    public function mapUser(User $user): UserModel {
        $userModel = new UserModel();
        $userModel->id = $user->id;
        $userModel->name = $user->name;
        $userModel->email = $user->email;
        $userModel->lastname = $user->lastname;
        $userModel->surname = $user->surname;
        $userModel->userName = $user->user_name;

        return $userModel;
    }
}
