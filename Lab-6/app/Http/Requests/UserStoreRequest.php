<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\RequestModels\UserRequestModel;

class UserStoreRequest extends CommonFormRequest
{


    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|alpha',
            'surname' => 'required|alpha',
            'lastname' => 'required|alpha',
            'birth_date' => 'required|date_format:Y-m-d',
            'email' => 'required|email:rfc',
            'user_name' => 'required|string',
        ];
    }

    public function body(): UserRequestModel
    {
        return $this->innerBodyObject(new UserRequestModel());
    }

}
