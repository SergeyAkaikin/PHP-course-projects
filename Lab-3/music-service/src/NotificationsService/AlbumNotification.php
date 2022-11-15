<?php

namespace MusicService\NotificationsService;

use MusicService\Entity\Artist;
use MusicService\Objects\Album;

/**
 * @property $album
 * @property $artist
 */
final class AlbumNotification implements Notification
{
    private Album $album;
    private Artist $artist;

    /**
     * @param Album $album
     * @param Artist $artist
     */
    public function __construct(Album $album, Artist $artist)
    {
        $this->album = $album;
        $this->artist = $artist;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        // TODO: Implement __toString() method.
        return $this->artist->getUserName() . ' published a new album - ' . $this->album->getName() . "\n";
    }

    /**
     * @return Album
     */
    public function getNotificationObject(): Album
    {
        // TODO: Implement getNotificationObject() method.
        return $this->album;
    }
}