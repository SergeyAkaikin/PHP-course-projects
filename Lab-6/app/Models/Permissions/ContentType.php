<?php

namespace App\Models\Permissions;

enum ContentType: int
{
    case User = 1;

    case Song = 2;

    case Album = 3;
    case  Playlist = 4;
}
