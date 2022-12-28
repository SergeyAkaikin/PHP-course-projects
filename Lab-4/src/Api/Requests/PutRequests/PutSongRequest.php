<?php

namespace MusicService\Api\Requests\PutRequests;

use MusicService\Api\Models\PutModels\PutSongModel;
use MusicService\Http\Request;

class PutSongRequest extends Request
{

    public function rules(): array
    {
        return [
            'artist_id' => ['required', 'int'],
            'title' => ['required'],
            'genre' => ['required'],
        ];
    }

    public function getModel(): PutSongModel
    {
        return (new \JsonMapper())->map((object)$this->data->params, new PutSongModel());
    }
}