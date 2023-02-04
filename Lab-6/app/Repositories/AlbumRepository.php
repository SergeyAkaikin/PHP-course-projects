<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Album;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JsonMapper;

class AlbumRepository
{


    public function getAlbums(): Collection
    {
        return DB::table('album')->get();
    }

    public function getAlbum(int $id): ?Album
    {
        $album = DB::table('album')->find($id);
        return ($album === null) ? null : (new JsonMapper())->map($album, new Album());
    }

    public function deleteAlbum(int $id): void
    {
        DB::table('album')->delete($id);
    }

    public function putAlbum(int $artist_id, string $title): void
    {
        DB::table('album')->insert([
            ['artist_id' => $artist_id, 'title' => $title],
        ]);
    }

    public function updateAlbum(int $id, int $artist_id, string $title): void
    {
        DB::table('album')->where('id', '=', $id)->update([
            'artist_id' => $artist_id,
            'title' => $title,
        ]);
    }

}
