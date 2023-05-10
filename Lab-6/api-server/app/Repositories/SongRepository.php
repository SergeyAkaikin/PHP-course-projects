<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Song;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SongRepository
{


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

    public function createSong(int $artist_id, string $title, string $genre, string $path): int
    {
        $song = new Song();
        $song->title = $title;
        $song->genre = $genre;
        $song->path = $path;
        $song->artist_id = $artist_id;
        $song->save();
        return $song->id;
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

    /**
     * @return Collection<int, Song>
     */
    public function getPlaylistSongs(int $playlist_id): Collection
    {

        $songs = Song::query()->join('playlist_songs', 'songs.id', '=', 'song_id')
            ->where('playlist_id', '=', $playlist_id)
            ->select(['songs.id as id', 'artist_id', 'songs.title as title', 'genre', 'songs.path as path' ])
            ->get();
        return $songs;
    }

    public function getArtistId(int $song_id): ?int
    {
        $songIdObject = DB::table('songs')->where('id', '=', $song_id)->select('artist_id')->first();
        return $songIdObject?->artist_id;
    }

    public function deleteSongFromAlbum(int $album_id, int $song_id): void
    {
        DB::table('album_songs')->where('album_id', '=', $album_id)
            ->where('song_id', '=', $song_id)->delete();
        $this->deleteSong($song_id);
    }

    public function deleteSong(int $song_id): void
    {
        Song::query()->find($song_id)->delete();
    }
}
