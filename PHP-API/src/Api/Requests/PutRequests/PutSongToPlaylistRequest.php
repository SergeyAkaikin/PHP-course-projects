<?php
declare(strict_types=1);
namespace MusicService\Api\Requests\PutRequests;

use MusicService\Api\Models\PutModels\PutSongToPlaylistModel;
use MusicService\Http\Request;

class PutSongToPlaylistRequest extends Request
{

    public function rules(): array
    {
        return [
            'playlist_id' => ['required', 'int'],
            'song_id' => ['required', 'int']
        ];
    }

    public function getModel(): PutSongToPlaylistModel
    {
        return (new \JsonMapper())->map((object)$this->data->params, new PutSongToPlaylistModel());
    }
}