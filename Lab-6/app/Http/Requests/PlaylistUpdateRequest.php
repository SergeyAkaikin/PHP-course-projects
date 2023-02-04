<?php

namespace App\Http\Requests;


class PlaylistUpdateRequest extends PlaylistStoreRequest
{


    public function rules(): array
    {
        return [
            'title' => 'required|string',
        ];
    }
}
