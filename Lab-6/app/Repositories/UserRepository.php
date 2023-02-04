<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JsonMapper;


class UserRepository
{



    public function getUsers(): Collection
    {
        return DB::table('user')->get();
    }

    public function getUser(int $id): ?User
    {
        $user = DB::table('user')->find($id);
        return ($user === null) ? null : (new JsonMapper())->map($user, new User());
    }

    public function deleteUser(int $id): void
    {
        DB::table('user')->delete($id);
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
        DB::table('user')->insert([
            [
                'name' => $name,
                'surname' => $surname,
                'lastname' => $lastname,
                'birth_date' => $birth_date,
                'email' => $email,
                'user_name' => $user_name
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
        DB::table('user')
            ->where('id', $id)
            ->update([
                'name' => $name,
                'surname' => $surname,
                'lastname' => $lastname,
                'birth_date' => $birth_date,
                'email' => $email,
                'user_name' => $user_name
            ]);
    }
}
