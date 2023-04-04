<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Album;
use App\ViewModels\AlbumFullModel;
use App\ViewModels\AlbumModel;
use Illuminate\Support\Collection;

class AlbumRatingService
{

    public function getRankedList(Collection $albums): Collection
    {
        return $albums->sortByDesc(fn (Album|AlbumModel|AlbumFullModel $album): int => $album->rating)->values();
    }
}
