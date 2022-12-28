<?php

namespace MusicService\Api\Requests\PutRequests;

use MusicService\Api\Models\PutModels\PutArtistModel;

class PutArtistRequest extends PutUserRequest
{

    public function getModel(): PutArtistModel
    {
        return (new \JsonMapper())->map(parent::getModel(), new PutArtistModel());
    }
}