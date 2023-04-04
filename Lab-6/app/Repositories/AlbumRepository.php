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

    public function getAlbum(int $album_id): ?Album
    {
        return Album::query()->find($album_id);
    }

    public function deleteAlbum(int $album_id): bool
    {
        return (bool)Album::query()->where('id', '=', $album_id)->delete();
    }

    public function putAlbum(int $artist_id, string $title): int
    {
        return DB::table('albums')->insertGetId(
            ['artist_id' => $artist_id, 'title' => $title, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        );
    }

    public function updateAlbum(int $album_id, string $title): bool
    {
        return (bool)DB::table('albums')->where('id', '=', $album_id)->update([
            'title' => $title,
            'updated_at' => Carbon::now()
        ]);
    }

    public function getArtistId(int $album_id): ?int
    {
        $albumObject = DB::table('albums')->where('id', '=', $album_id)->select('artist_id')->first();
        return $albumObject?->artist_id;
    }


}
