<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Playlist;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PlaylistRepository
{

    public function getPlaylists(): Collection
    {

        return Playlist::query()->get();;
    }

    public function getPlaylist(int $id): ?Playlist
    {
        return Playlist::query()->find($id);
    }


    public function getPlaylistsByUserId(int $user_id): Collection
    {
        return DB::table('playlists')->where('user_id', '=', $user_id)->get();
    }

    public function deletePlaylist(int $id): void
    {
        DB::table('playlists')->delete($id);
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
        DB::table('playlists')
            ->insert([
                ['user_id' => $user_id, 'title' => $title, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]);
    }

    public function putSongToPlaylist(int $playlist_id, int $song_id): void
    {
        DB::table('playlist_songs')
            ->insert([
                ['playlist_id' => $playlist_id, 'song_id' => $song_id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]);
    }

    public function updatePlaylist(int $id, string $title): void
    {
        DB::table('playlists')
            ->where('id', '=', $id)
            ->update([
                'title' => $title,
                'updated_at' => Carbon::now()
            ]);
    }

}
