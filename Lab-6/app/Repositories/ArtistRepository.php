<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class ArtistRepository
{


    public function getArtists(): Collection
    {
        return User::query()->where('is_artist', '=', true)->get();
    }

    public function getArtist(int $artist_id): ?User
    {
        return User::query()->where('is_artist', '=', true)->find($artist_id);
    }

    public function deleteArtist(int $artist_id): bool
    {
        return (bool)User::query()->where('is_artist', '=', true)->where('id', '=', $artist_id)->delete();
    }

    public function putArtist(
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
                'is_artist' => true
            ]
        );
    }

    public function updateArtist(
        int    $artist_id,
        string $name,
        string $surname,
        string $lastname,
        Carbon $birth_date,
        string $email,
        string $user_name,
    ): bool
    {
        return (bool)DB::table('users')
            ->where('is_artist', '=', true)
            ->where('id', '=', $artist_id)
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
