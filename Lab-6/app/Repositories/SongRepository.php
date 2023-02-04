<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Song;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JsonMapper;

class SongRepository
{


    public function getSongs(): Collection
    {
        return DB::table('song')->get();
    }

    public function getSong(int $id): ?Song
    {
        $song = DB::table('song')->find($id);
        return ($song === null) ? null : (new JsonMapper())->map($song, new Song());

    }

    public function getSongsByAlbum(int $album_id): Collection
    {
        return DB::table('album_songs')
            ->join('song', 'album_songs.song_id', '=', 'song.id')
            ->where('album_songs.album_id', '=', $album_id)
            ->select('song.id as id', 'song.artist_id as artist_id', 'song.title as title', 'song.genre as genre')
            ->get();
    }

    public function getSongsByArtist(int $artist_id): Collection
    {
        return DB::table('song')
            ->where('artist_id', '=', $artist_id)
            ->select()
            ->get();
    }

    public function deleteSong(int $id): void
    {
        DB::table('song')->delete($id);
    }

    public function putSong(int $artist_id, string $title, string $genre): void
    {
        DB::table('song')->insert([
            [
                'artist_id' => $artist_id,
                'title' => $title,
                'genre' => $genre
            ]
        ]);
    }

    public function putSongToAlbum(int $album_id, int $song_id): void
    {
        DB::table('album_songs')->insert([
            ['album_id' => $album_id, 'song_id' => $song_id]
        ]);
    }

    public function updateSong(int $id, int $artist_id, string $title, string $genre): void
    {
        DB::table('song')
            ->where('id', '=', $id)
            ->update(
                [
                    'artist_id' => $artist_id,
                    'title' => $title,
                    'genre' => $genre
                ]
            );
    }
    public function getSongsFromPlaylist(int $id): Collection
    {
        return DB::table('playlist_songs')
            ->where('playlist_id', '=',$id)
            ->join('song', 'song_id', '=', 'song.id')
            ->select('song.id as id', 'artist_id', 'song.title as title', 'genre')
            ->get();
    }
}
