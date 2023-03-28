<?php
declare(strict_types=1);

namespace App\Models\Permissions;
enum PermissionCode: int
{
    case AccessMusicCollection = 1;

    case ManageOwnLibrary = 2;

    case ManageOwnAlbums = 3;

    case ManageOwnSongs = 4;

    case ManageAllMusicContent = 5;

    case AccessUserInformation = 6;

    case ManageOwnUserInfo = 7;

    case ManageAllUsersInfo = 8;

    case DeleteMusicContent = 9;

    case UploadMusicContent = 10;

    case CreatePlaylist = 11;
}
