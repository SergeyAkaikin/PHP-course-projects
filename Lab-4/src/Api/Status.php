<?php
declare(strict_types=1);

namespace MusicService\Api;

enum Status: int
{
    case Ok = 0;
    case NotFound = 10;
    case InvalidRequest = 11;
}
