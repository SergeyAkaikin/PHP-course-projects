<?php
declare(strict_types=1);

namespace App\Http\Requests;


class PlaylistUpdateRequest extends PlaylistStoreRequest
{


    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
        ];
    }
}
