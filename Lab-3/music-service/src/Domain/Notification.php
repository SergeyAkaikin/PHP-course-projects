<?php

namespace MusicService\Domain;

/**
 * @property int $notificationId
 * @property int $artistId
 * @property int $listenerId
 * @property string $notificationType
 * @property int $itemId
 */
class Notification
{
    public int $notificationId;
    public int $artistId;
    public int $listenerId;
    public string $notificationType;
    public int $itemId;

    /**
     * @param $notificationId
     * @param $artistId
     * @param $listenerId
     * @param $notificationType
     * @param $itemId
     */
    public function __construct($notificationId, $artistId, $listenerId, $notificationType, $itemId)
    {
        $this->notificationId = $notificationId;
        $this->artistId = $artistId;
        $this->listenerId = $listenerId;
        $this->notificationType = $notificationType;
        $this->itemId = $itemId;
    }
}