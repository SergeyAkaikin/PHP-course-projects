<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Song;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SongRepository
{


    public function getSongs(): Collection
    {
        return Song::query()->get();
    }

    public function getSong(int $song_id): ?Song
    {

        return Song::query()->find($song_id);

    }

    /**
     * @return Collection<int, Song>
     */
    public function getSongsByAlbum(int $album_id): Collection
    {
        return Song::query()
            ->join('album_songs', 'album_songs.song_id', '=', 'songs.id')
            ->where('album_songs.album_id', '=', $album_id)
            ->select(
                'songs.id as id',
                'songs.artist_id as artist_id',
                'songs.title as title',
                'songs.genre as genre',
                'songs.created_at as created_at',
                'songs.updated_at as updated_at',
                'songs.deleted_at as deleted_at',
                'songs.path as path',
            )
            ->get();
    }

    /**
     * @return Collection<int, Song>
     */
    public function getSongsByArtist(int $artist_id): Collection
    {
        return Song::query()
            ->where('artist_id', '=', $artist_id)
            ->select()
            ->get();
    }

    public function putSong(int $artist_id, string $title, string $genre, string $path): int
    {
        return DB::table('songs')->insertGetId(
            [
                'artist_id' => $artist_id,
                'title' => $title,
                'genre' => $genre,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'path' => $path,
            ]
        );
    }

    public function putSongToAlbum(int $album_id, int $song_id): int
    {
        return DB::table('album_songs')->insertGetId(
            [
                'album_id' => $album_id,
                'song_id' => $song_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );
    }

    public function updateSong(int $song_id, string $title, string $genre): bool
    {
        return (bool)DB::table('songs')
            ->where('id', '=', $song_id)
            ->update(
                [
                    'title' => $title,
                    'genre' => $genre,
                    'updated_at' => Carbon::now()
                ]
            );
    }

    /**
     * @return Collection<int, Song>
     */
    public function getSongsFromPlaylist(int $playlist_id): Collection
    {

        return Song::query()->join('playlist_songs', 'songs.id', '=', 'song_id')
            ->where('playlist_id', '=', $playlist_id)
            ->select(['songs.id as id', 'artist_id', 'songs.title as title', 'genre', 'path'])
            ->get();
    }

    public function getArtistId(int $song_id): ?int
    {
        $songIdObject = DB::table('songs')->where('id', '=', $song_id)->select('artist_id')->first();
        return $songIdObject?->artist_id;
    }

    public function deleteSongFromAlbum(int $album_id, int $song_id): bool
    {
        DB::table('album_songs')->where('album_id', '=', $album_id)
            ->where('song_id', '=', $song_id)->delete();
        return $this->deleteSong($song_id);
    }

    public function deleteSong(int $song_id): bool
    {
        return (bool)DB::table('songs')->delete($song_id);
    }
}
