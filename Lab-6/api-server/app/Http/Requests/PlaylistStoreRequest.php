<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\RequestModels\PlaylistRequestModel;


class PlaylistStoreRequest extends CommonFormRequest
{

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer',
            'title' => 'required|string',
        ];
    }

    public function body(): PlaylistRequestModel
    {
        return $this->innerBodyObject(new PlaylistRequestModel());
    }
}
