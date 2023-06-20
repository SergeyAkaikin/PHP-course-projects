<?php

namespace MusicService\Api\Requests\PutRequests;

use MusicService\Api\Models\PutModels\PutUserModel;
use MusicService\Http\Request;

class PutUserRequest extends Request
{

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'surname' => ['required'],
            'lastname' => ['required'],
            'email' => ['required', 'ableEmail'],
            'birth_date' => ['required', 'ableDate'],
            'user_name' => ['required']
        ];
    }

    public function getModel(): PutUserModel
    {
        return (new \JsonMapper())->map((object)$this->data->params, new PutUserModel());
    }
}