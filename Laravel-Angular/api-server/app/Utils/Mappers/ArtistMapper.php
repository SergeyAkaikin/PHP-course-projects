<?php
declare(strict_types=1);

namespace App\Utils\Mappers;

use App\Models\User;
use App\ApiModels\ArtistModel;
use Carbon\Carbon;

class ArtistMapper
{
    public function mapArtist(User $artist): ArtistModel
    {
        $model = new ArtistModel();
        $model->id = $artist->id;
        $model->user_name = $artist->user_name;
        $model->email = $artist->email;
        $model->years = now()->diffInYears(Carbon::parse($artist->birth_date));
        return $model;
    }
}
