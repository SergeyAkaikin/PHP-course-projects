<?php
declare(strict_types=1);

namespace App\Http\RequestModels;

use Illuminate\Http\UploadedFile;

class SongRequestModel
{
    public int $artist_id;
    public string $title;
    public string $genre;
    public UploadedFile $file;

}
