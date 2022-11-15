<?php

namespace MusicService\NotificationsService;

use MusicService\Entity\Artist;
use MusicService\Objects\Song;

/**
 * @property Song $song
 * @property Artist $artist
 */
final class SongNotification implements Notification
{
    private Song $song;
    private Artist $artist;

    public function __construct(Song $song, Artist $artist)
    {
        $this->song = $song;
        $this->artist = $artist;
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
        return $this->artist->getUserName() . ' published a new song - ' . $this->song->getName() . "\n";
    }

    public function getNotificationObject(): Song
    {
        // TODO: Implement getNotificationObject() method.
        return $this->song;
    }
}