<?php

namespace MusicService\Api\Requests\PutRequests;

use MusicService\Api\Models\PutModels\PutPlaylistModel;
use MusicService\Http\Request;

class PutPlaylistRequest extends Request
{

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'int'],
            'title' => ['required']
        ];
    }

    public function getModel(): PutPlaylistModel
    {
        return (new \JsonMapper())->map((object)$this->data->params, new PutPlaylistModel());
    }
}