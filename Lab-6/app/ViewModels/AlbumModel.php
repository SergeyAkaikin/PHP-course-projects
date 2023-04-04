<?php
declare(strict_types=1);

namespace App\ViewModels;

use Carbon\Carbon;

class AlbumModel
{
    public int $id;
    public string $artist_name;
    public int $artist_id;
    public string $title;
    public string $date;
    public int $rating;
}
