<?php
declare(strict_types=1);

namespace App\ApiModels;

class SongModel
{
    public int $id;
    public int $artistId;
    public string $title;
    public string $genre;
    public ?string $path;
}
