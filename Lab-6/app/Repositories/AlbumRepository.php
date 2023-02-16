<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Album;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AlbumRepository
{


    public function getAlbums(): Collection
    {
        return Album::query()->get();
    }

    public function getAlbum(int $id): ?Album
    {
        return Album::query()->find($id);
    }

    public function deleteAlbum(int $id): void
    {
        Album::query()->where('id', '=', $id)->delete();
    }

    public function putAlbum(int $artist_id, string $title): void
    {
        DB::table('albums')->insert([
            ['artist_id' => $artist_id, 'title' => $title, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }

    public function updateAlbum(int $id, int $artist_id, string $title): void
    {
        DB::table('albums')->where('id', '=', $id)->update([
            'artist_id' => $artist_id,
            'title' => $title,
            'updated_at' => Carbon::now()
        ]);
    }

}
