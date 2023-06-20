<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JsonMapper;

abstract class CommonFormRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    abstract public function body(): mixed;

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 400));
    }

    protected function innerBodyObject(mixed $body): mixed
    {
        return (new JsonMapper())->map((object)$this->input(), $body);
    }

    protected function innerBodyArray(string $itemClass): array
    {
        $jsonMapper = new JsonMapper();

        $data = $this->input();

        return array_map(fn($item) => $jsonMapper->map((object)$item, new $itemClass()), $data);
    }

}
