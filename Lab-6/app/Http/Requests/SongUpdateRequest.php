<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\RequestModels\SongRequestModel;

class SongUpdateRequest extends CommonFormRequest
{
    /**
     * @return array<string, string>
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'genre' => 'required|string',
        ];
    }


    public function body(): SongRequestModel
    {
        return $this->innerBodyObject(new SongRequestModel());
    }
}

