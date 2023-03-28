<?php
declare(strict_types=1);

namespace App\Http\RequestModels;

class SongRequestModel
{
    public int $artist_id;
    public string $title;
    public string $genre;

}
