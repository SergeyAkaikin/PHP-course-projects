<?php
declare(strict_types=1);

namespace MusicService\Api\Requests\GetRequests;

use MusicService\Api\Models\GetModels\GetByIdModel;
use MusicService\Http\Request;

class GetByIdRequest extends Request
{

    public function rules(): array
    {
        return [
            'id' => ['required', 'int'],
        ];
    }

    public function getModel(): GetByIdModel
    {
        return (new \JsonMapper())->map((object)$this->data->params, new GetByIdModel());
    }
}