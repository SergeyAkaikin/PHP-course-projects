<?php

namespace MusicService\Domain;

class Notification
{
    public int $notificationId;
    public int $artistId;
    public int $listenerId;
    public string $notificationType;
    public int $itemId;

    public function __construct($notificationId, $artistId, $listenerId, $notificationType, $itemId)
    {
        $this->notificationId = $notificationId;
        $this->artistId = $artistId;
        $this->listenerId = $listenerId;
        $this->notificationType = $notificationType;
        $this->itemId = $itemId;
    }
}