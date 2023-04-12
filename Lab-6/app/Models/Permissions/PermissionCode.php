<?php
declare(strict_types=1);

namespace App\Models\Permissions;
enum PermissionCode: int
{
    case AccessMusicCollection = 1;

    case ManageOwnLibrary = 2;

    case ManageOwnAlbums = 3;

    case ManageOwnSongs = 4;

    case AccessUserInformation = 5;

    case ManageOwnUserInfo = 6;

    case UploadMusicContent = 7;

    case CreatePlaylist = 8;
}
