<?php
declare(strict_types=1);

namespace App\ViewModels;

use Carbon\Carbon;

class AlbumModel
{
    public int $id;
    public int $artist_id;
    public string $title;
    public Carbon $date;
    public int $rating;
}
