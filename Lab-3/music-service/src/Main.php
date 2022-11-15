<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
/*
 * Далее иллюстрируется пример работы сервиса уведомлений, который является реализацией поведенческого паттерна observer.
 */
use MusicService\Entity\Artist;
use MusicService\Entity\User;
use MusicService\Objects\Album;
use MusicService\Objects\Song;

$user1 = new User('Anton',
    'Svoykin',
    'Vladimirovich',
    '2002-11-9',
    'antsv@mail.ru',
    'AntSvoy',
    '12345678');

$artist = new Artist(
    'Ivan',
    'Ivanov',
    'Ivanovich',
    '1999-06-27',
    'ivan123@gmail.com',
    'Ivano',
    'qwerty1234');

$artist->subscribe($user1);
$artist->publishSong(new Song('Pole', 'Ivano', 'Hor'));
$songs = [new Song('Berezka', 'Ivano', 'Hor'), new Song('Nebo', 'Ivano', 'Hor')];
$album = new Album('Rodina', $songs);

$artist->publishAlbum($album);
$firstUpdate = null;

foreach ($user1->getUpdates() as $update) {
    print($update);
    $firstUpdate = $update->getNotificationObject();
    print($firstUpdate->getName() . "\n");
}



$artist->unsubscribe($user1);

$artist->publishSong(new Song('Dom', 'Ivano', 'Hor'));

foreach ($user1->getUpdates() as $update) {
    print($update . "\n");
    print('Check notificationList');
}





