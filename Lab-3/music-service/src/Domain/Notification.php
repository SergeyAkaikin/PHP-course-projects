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
    public int $id;
    public int $artistId;
    public int $listenerId;
    public string $notificationType;
    public int $itemId;

    /**
     * @param $id
     * @param $artistId
     * @param $listenerId
     * @param $notificationType
     * @param $itemId
     */
    public function __construct($id, $artistId, $listenerId, $notificationType, $itemId)
    {
        $this->id = $id;
        $this->artistId = $artistId;
        $this->listenerId = $listenerId;
        $this->notificationType = $notificationType;
        $this->itemId = $itemId;
    }
}