<?php

namespace MusicService\Api\Requests\PutRequests;

use MusicService\Api\Models\PutModels\PutSongToAlbumModel;
use MusicService\Http\Request;

class PutSongToAlbumRequest extends Request
{

    public function rules(): array
    {
        return [
            'album_id' => ['required', 'int'],
            'song_id' => ['required', 'int'],
        ];
    }

    public function getModel(): PutSongToAlbumModel
    {
        return (new \JsonMapper())->map((object)$this->data->params, new PutSongToAlbumModel());
    }
}