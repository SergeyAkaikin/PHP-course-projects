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

        return Playlist::query()->get();
    }

    public function getPlaylist(int $playlist_id): ?Playlist
    {
        return Playlist::query()->find($playlist_id);
    }


    public function getPlaylistsByUserId(int $user_id): Collection
    {
        return Playlist::query()->where('user_id', '=', $user_id)->get();
    }

    public function deletePlaylist(int $playlist_id): bool
    {
        return (bool)Playlist::query()->where('id', '=', $playlist_id)->delete();
    }

    public function deleteSongFromPlaylist(int $playlist_id, int $song_id): bool
    {
        return (bool)DB::table('playlist_songs')
            ->where('playlist_id', '=', $playlist_id)
            ->where('song_id', '=', $song_id)
            ->delete();
    }

    public function putPlaylist(int $user_id, string $title): int
    {
        return Playlist::query()
            ->insertGetId(
                ['user_id' => $user_id, 'title' => $title, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            );
    }

    public function putSongToPlaylist(int $playlist_id, int $song_id): int
    {
        return DB::table('playlist_songs')
            ->insertGetId(
                ['playlist_id' => $playlist_id, 'song_id' => $song_id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            );
    }

    public function updatePlaylist(int $playlist_id, string $title): bool
    {
        return (bool)Playlist::query()
            ->where('id', '=', $playlist_id)
            ->update([
                'title' => $title,
                'updated_at' => Carbon::now()
            ]);
    }

    public function getUserId(int $playlist_id): ?int
    {
        $playlistIdObject = DB::table('playlists')->where('id', '=', $playlist_id)->select('user_id')->first();

        return $playlistIdObject?->user_id;
    }

    public function getMainUserPlaylist(int $user_id): ?Playlist
    {
        return Playlist::query()->where('user_id', '=', $user_id)
            ->where('title', '=', 'main')
            ->first();
    }

}
