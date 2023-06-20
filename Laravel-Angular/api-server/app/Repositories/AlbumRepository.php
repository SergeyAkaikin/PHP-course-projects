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
        return Album::query()->orderByDesc('rating')->get();
    }

    public function getAlbum(int $album_id): ?Album
    {
        return Album::query()->find($album_id);
    }

    public function deleteAlbum(int $album_id): bool
    {
        return (bool)Album::query()->where('id', '=', $album_id)->delete();
    }

    public function createAlbum(int $artist_id, string $title): int
    {
        $album = new Album();
        $album->artist_id = $artist_id;
        $album->title = $title;
        $album->save();
        return $album->id;
    }

    public function updateAlbum(int $album_id, string $title): bool
    {
        return (bool)Album::query()->where('id', '=', $album_id)->update([
            'title' => $title,
            'updated_at' => Carbon::now()
        ]);
    }

    public function getArtistId(int $album_id): ?int
    {

        $album = Album::query()->where('id', '=', $album_id)->select()->first();;
        return $album?->artist_id;
    }

    public function getUserAlbums(int $user_id): Collection
    {
        return Album::query()->where('artist_id', '=', $user_id)->get();
    }


}
