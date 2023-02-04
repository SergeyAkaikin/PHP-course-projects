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
        return DB::table('artist')->get();
    }

    public function getArtist(int $id): ?Artist
    {
        $artist = DB::table('artist')->find($id);
        return ($artist === null) ? null : (new JsonMapper())->map($artist, new Artist());
    }

    public function deleteArtist(int $id): void
    {
        DB::table('artist')->delete($id);
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
        DB::table('artist')->insert([
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
        DB::table('artist')
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
