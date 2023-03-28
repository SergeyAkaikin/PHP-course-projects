<?php

namespace App\Http\Requests;

use App\Http\RequestModels\AlbumUpdateRequestModel;

class AlbumUpdateRequest extends CommonFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
        ];
    }

    public function body(): AlbumUpdateRequestModel
    {
        return $this->innerBodyObject(new AlbumUpdateRequestModel());
    }
}
