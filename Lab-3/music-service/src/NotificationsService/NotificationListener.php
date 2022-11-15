<?php

namespace MusicService\NotificationsService;


interface NotificationListener
{
    public function update(Notification $notificationObject): void;
}