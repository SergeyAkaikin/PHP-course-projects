<?php

namespace MusicService\Api\Requests\DeleteRequests;

use MusicService\Api\Models\DeleteModels\DeleteSongFromPlaylistModel;
use MusicService\Http\Request;

class DeleteSongFromPlaylistRequest extends Request
{

    public function rules(): array
    {
        return [
            'playlist_id' => ['required', 'int'],
            'song_id' => ['required', 'int']
        ];
    }

    public function getModel(): DeleteSongFromPlaylistModel
    {
        return (new \JsonMapper())->map((object)$this->data->params, new DeleteSongFromPlaylistModel());
    }
}