<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\RequestModels\AlbumRequestModel;

class AlbumStoreRequest extends CommonFormRequest
{

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'artist_id' => 'required|integer',
            'title' => 'required|string',
        ];
    }

    public function body(): AlbumRequestModel
    {
        return $this->innerBodyObject(new AlbumRequestModel());
    }

}
