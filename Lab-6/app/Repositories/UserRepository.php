<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class UserRepository
{



    public function getUsers(): Collection
    {
        return User::query()->get();
    }

    public function getUser(int $id): ?User
    {
        return User::query()->find($id);
    }

    public function deleteUser(int $id): void
    {
        User::query()->where('id', '=', $id)->delete();
    }

    public function putUser(
        string $name,
        string $surname,
        string $lastname,
        Carbon $birth_date,
        string $email,
        string $user_name
    ): void
    {
        DB::table('users')->insert([
            [
                'name' => $name,
                'surname' => $surname,
                'lastname' => $lastname,
                'birth_date' => $birth_date,
                'email' => $email,
                'user_name' => $user_name,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }

    public function updateUser(
        int    $id,
        string $name,
        string $surname,
        string $lastname,
        Carbon $birth_date,
        string $email,
        string $user_name
    ): void
    {
        DB::table('users')
            ->where('id', $id)
            ->update([
                'name' => $name,
                'surname' => $surname,
                'lastname' => $lastname,
                'birth_date' => $birth_date,
                'email' => $email,
                'user_name' => $user_name,
                'updated_at' => Carbon::now()
            ]);
    }
}
