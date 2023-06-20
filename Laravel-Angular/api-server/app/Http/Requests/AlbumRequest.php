<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\RequestModels\AlbumRequestModel;

class AlbumRequest extends CommonFormRequest
{

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'artistId' => 'required|integer',
            'title' => 'required|string',
        ];
    }

    public function body(): AlbumRequestModel
    {
        return $this->innerBodyObject(new AlbumRequestModel());
    }

}
