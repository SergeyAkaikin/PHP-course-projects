<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\RequestModels\SongRequestModel;

class SongStoreRequest extends CommonFormRequest
{


    /**
     * @return array<string, string>
     */
    public function rules()
    {
        return [
            'artistId' => 'required|integer',
            'title' => 'required|string',
            'genre' => 'required|string',
            'file' => 'required|mimes:mp3',
        ];
    }


    public function body(): SongRequestModel
    {
        $model = new SongRequestModel();
        $model->title = $this->get('title');
        $model->genre = $this->get('genre');
        $model->artistId = intval($this->get('artistId'));
        $model->file = $this->file('file');
        return $model;
    }
}
