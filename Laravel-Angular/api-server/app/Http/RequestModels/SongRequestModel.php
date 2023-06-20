<?php
declare(strict_types=1);

namespace App\Http\RequestModels;

use Illuminate\Http\UploadedFile;

class SongRequestModel
{
    public int $artistId;
    public string $title;
    public string $genre;
    public UploadedFile $file;

}
