<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Song;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JsonMapper;

class SongRepository
{


    public function getSongs(): Collection
    {
        return Song::query()->get();
    }

    public function getSong(int $id): ?Song
    {

        return Song::query()->find($id);

    }

    public function getSongsByAlbum(int $album_id): Collection
    {
        return DB::table('album_songs')
            ->join('songs', 'album_songs.song_id', '=', 'songs.id')
            ->where('album_songs.album_id', '=', $album_id)
            ->select('songs.id as id', 'songs.artist_id as artist_id', 'songs.title as title', 'songs.genre as genre')
            ->get();
    }

    public function getSongsByArtist(int $artist_id): Collection
    {
        return DB::table('songs')
            ->where('artist_id', '=', $artist_id)
            ->select()
            ->get();
    }

    public function deleteSong(int $id): void
    {
        DB::table('songs')->delete($id);
    }

    public function putSong(int $artist_id, string $title, string $genre): void
    {
        DB::table('songs')->insert([
            [
                'artist_id' => $artist_id,
                'title' => $title,
                'genre' => $genre,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }

    public function putSongToAlbum(int $album_id, int $song_id): void
    {
        DB::table('album_songs')->insert([
            [
                'album_id' => $album_id,
                'song_id' => $song_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }

    public function updateSong(int $id, int $artist_id, string $title, string $genre): void
    {
        DB::table('songs')
            ->where('id', '=', $id)
            ->update(
                [
                    'artist_id' => $artist_id,
                    'title' => $title,
                    'genre' => $genre,
                    'updated_at' => Carbon::now()
                ]
            );
    }
    public function getSongsFromPlaylist(int $id): Collection
    {
        return DB::table('playlist_songs')
            ->where('playlist_id', '=',$id)
            ->join('songs', 'song_id', '=', 'songs.id')
            ->select('songs.id as id', 'artist_id', 'songs.title as title', 'genre')
            ->get();
    }
}
