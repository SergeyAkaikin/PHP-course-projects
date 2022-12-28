<?php
declare(strict_types=1);

namespace MusicService\Api\Requests\GetRequests;

use MusicService\Api\Models\GetModels\GetUserPlaylistModel;
use MusicService\Http\Request;

class GetUserPlaylistRequest extends Request
{

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'int']
        ];
    }

    public function getModel(): GetUserPlaylistModel
    {
        return (new \JsonMapper())->map((object)$this->data->params, new GetUserPlaylistModel());
    }
}