<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Permissions\UserRole;
use App\Models\User;
use App\Utils\EnumBitmapEncoder;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class UserRepository
{


    public function getUsers(): Collection
    {
        return User::query()->get();
    }

    public function getUser(int $user_id): ?User
    {
        return User::query()->find($user_id);
    }

    public function deleteUser(int $user_id): bool
    {
        return (bool)User::query()->where('id', '=', $user_id)->delete();
    }

    public function putUser(
        string $name,
        string $surname,
        string $lastname,
        Carbon $birth_date,
        string $email,
        string $user_name
    ): int
    {
        return DB::table('users')->insertGetId(
            [
                'name' => $name,
                'surname' => $surname,
                'lastname' => $lastname,
                'birth_date' => $birth_date,
                'email' => $email,
                'user_name' => $user_name,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }

    public function updateUser(
        int    $user_id,
        string $name,
        string $surname,
        string $lastname,
        Carbon $birth_date,
        string $email,
        string $user_name
    ): bool
    {
        return (bool)DB::table('users')
            ->where('id', $user_id)
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

    /**
     * @return ?User
     */
    public function getByUserName(string $user_name): ?User
    {
        return User::query()->where('user_name', '=', $user_name)->first();
    }

    /**
     * @param int $user_id
     * @return Collection<int, int>
     */
    public function getUserRoles(int $user_id): Collection
    {
        $rolesObj = DB::table('users')->where('id', '=', $user_id)->select('roles')->first();
        return EnumBitmapEncoder::decode(UserRole::cases(), $rolesObj?->roles);
    }
}
