<?php

namespace MusicService\NotificationsService;

use MusicService\Objects\Album;
use MusicService\Objects\Song;

interface Notification
{
    public function getNotificationObject(): Song|Album;

    public function __toString(): string;
}