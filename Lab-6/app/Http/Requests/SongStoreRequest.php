<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\RequestModels\SongRequestModel;

class SongStoreRequest extends CommonFormRequest
{


    /**
     * @return array<string, string>
     */
    public function rules()
    {
        return [
            'artist_id' => 'required|integer',
            'title' => 'required|string',
            'genre' => 'required|string',
        ];
    }


    public function body(): SongRequestModel
    {
        return $this->innerBodyObject(new SongRequestModel());
    }
}
