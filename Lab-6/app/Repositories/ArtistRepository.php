<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Artist;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JsonMapper;


class ArtistRepository
{



    public function getArtists(): Collection
    {
        return Artist::query()->get();
    }

    public function getArtist(int $id): ?Artist
    {
        return Artist::query()->find($id);
    }

    public function deleteArtist(int $id): void
    {
        Artist::query()->where('id', '=', $id)->delete();
    }

    public function putArtist(
        string $name,
        string $surname,
        string $lastname,
        Carbon $birth_date,
        string $email,
        string $user_name
    ): void
    {
        DB::table('artists')->insert([
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

    public function updateArtist(
        int    $id,
        string $name,
        string $surname,
        string $lastname,
        Carbon $birth_date,
        string $email,
        string $user_name
    ): void
    {
        DB::table('artists')
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
