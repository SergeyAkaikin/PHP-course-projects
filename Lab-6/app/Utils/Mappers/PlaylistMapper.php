<?php
declare(strict_types=1);

namespace App\Utils\Mappers;

use App\Models\Playlist;
use App\Models\Song;
use App\ViewModels\PlaylistFullModel;
use App\ViewModels\PlaylistModel;
use App\ViewModels\SongModel;
use Illuminate\Support\Collection;


class PlaylistMapper
{
    public function mapPlaylist(Playlist $playlist): PlaylistModel
    {
        $model = new PlaylistModel();
        $model->id = $playlist->id;
        $model->title = $playlist->title;
        $model->user_id = $playlist->user_id;

        return $model;
    }

    public function mapFullPlaylist(Playlist $playlist, Collection $songs)
    {
        $model = new PlaylistFullModel();
        $model->id = $playlist->id;
        $model->title = $playlist->title;
        $model->user_id = $playlist->user_id;
        $model->songCount = $songs->count();
        $mapper = new SongMapper();
        $model->songs = $songs->map(fn(Song $song): SongModel => $mapper->mapSong($song))->toArray();

        return $model;
    }

}
