<?php
declare(strict_types=1);

namespace App\ApiModels;

class AlbumModel
{
    public int $id;
    public string $artistName;
    public int $artistId;
    public string $title;
    public string $date;
    public int $rating;
}
