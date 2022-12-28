<?php
declare(strict_types=1);

namespace MusicService\Api\Models\PutModels;

class PutSongToPlaylistModel
{
    public int $playlist_id;
    public int $song_id;
}