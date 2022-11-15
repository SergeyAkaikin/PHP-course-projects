<?php

declare(strict_types=1);

namespace MusicService\Entity;


use Generator;
use MusicService\NotificationsService\AlbumNotification;
use MusicService\NotificationsService\SongNotification;
use MusicService\Objects\Album;
use MusicService\Objects\Song;
use MusicService\Utils\SongUtils;

/**
 * @property Song[] $songsList
 * @property Album[] $albumsList
 * @property User[] $subscribers
 */
final class Artist extends User
{
    private array $songsList = [];
    private array $albumsList = [];
    private array $subscribers = [];

    /**
     * @param Song $song
     * @return void
     */
    public function publishSong(Song $song): void
    {
        $this->songsList[] = $song;
        $notification = new SongNotification($song, $this);
        foreach ($this->subscribers as $subscriber) {
            $subscriber->update($notification);
        }
    }

    /**
     * @param Album $album
     * @return void
     */
    public function publishAlbum(Album $album): void
    {
        $this->albumsList[] = $album;
        $notification = new AlbumNotification($album, $this);
        foreach ($this->subscribers as $subscriber) {
            $subscriber->update($notification);
        }
    }

    /**
     * @return Generator
     */
    public function getSongs(): Generator
    {
        foreach ($this->songsList as $song) {
            yield SongUtils::getSongCopy($song);
        }
        foreach ($this->albumsList as $album) {
            $album->getSongs();
        }
    }

    /**
     * @return Generator
     */
    public function getAlbums(): Generator
    {
        foreach ($this->albumsList as $album) {
            yield SongUtils::getAlbumCopy($album);
        }
    }

    /**
     * @param User $user
     * @return void
     */
    public function subscribe(User $user): void
    {
        if (!in_array($user, $this->subscribers, true)) {
            $this->subscribers[] = $user;
        }
    }

    /**
     * @param User $user
     * @return void
     */
    public function unsubscribe(User $user): void
    {
        if (in_array($user, $this->subscribers, true)) {
            $this->subscribers = array_diff($this->subscribers, [$user]);
        }
    }
}