<?php
declare(strict_types=1);

namespace MusicService\Api\Requests\PutRequests;

use MusicService\Api\Models\PutModels\PutAlbumModel;
use MusicService\Http\Request;

class PutAlbumRequest extends Request
{

    public function rules(): array
    {
        return [
            'artist_id' => ['required', 'int'],
            'title' => ['required']
        ];
    }

    public function getModel(): PutAlbumModel
    {
        return (new \JsonMapper())->map((object)$this->data->params, new PutAlbumModel());
    }
}