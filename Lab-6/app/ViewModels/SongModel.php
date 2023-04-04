<?php
declare(strict_types=1);

namespace App\ViewModels;

class SongModel
{
    public int $id;
    public int $artist_id;
    public string $title;
    public string $genre;
    public ?string $path;
}
