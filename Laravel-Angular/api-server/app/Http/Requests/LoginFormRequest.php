<?php

namespace App\Http\Requests;

use App\Http\RequestModels\LoginRequestModel;

class LoginFormRequest extends CommonFormRequest
{


    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'userName' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function body(): LoginRequestModel
    {
        return $this->innerBodyObject(new LoginRequestModel());
    }
}
