<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Playlist;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PlaylistRepository
{

    public function getPlaylists(): Collection
    {
        return DB::table('playlist')->get();
    }

    public function getPlaylist(int $id): ?Playlist
    {
        $playlist = DB::table('playlist')->find($id);
        return ($playlist === null) ? null : (new \JsonMapper())->map($playlist, new Playlist());
    }


    public function getPlaylistsByUserId(int $user_id): Collection
    {
        return DB::table('playlist')->where('user_id', '=', $user_id)->get();
    }

    public function deletePlaylist(int $id): void
    {
        DB::table('playlist')->delete($id);
    }

    public function deleteSongFromPlaylist(int $playlist_id, int $song_id): void
    {
        DB::table('playlist_songs')
            ->where('playlist_id', '=', $playlist_id)
            ->where('song_id', '=', $song_id)
            ->delete();
    }

    public function putPlaylist(int $user_id, string $title): void
    {
        DB::table('playlist')
            ->insert([
                ['user_id' => $user_id, 'title' => $title],
            ]);
    }

    public function putSongToPlaylist(int $playlist_id, int $song_id): void
    {
        DB::table('playlist_songs')
            ->insert([
                ['playlist_id' => $playlist_id, 'song_id' => $song_id],
            ]);
    }

    public function updatePlaylist(int $id, string $title): void
    {
        DB::table('playlist')
            ->where('id', '=', $id)
            ->update([
                'title' => $title
            ]);
    }

}
