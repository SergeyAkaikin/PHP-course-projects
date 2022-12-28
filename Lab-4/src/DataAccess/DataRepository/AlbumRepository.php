<?php
declare(strict_types=1);

namespace MusicService\DataAccess\DataRepository;

use MusicService\Domain\Album;
use PDO;

class AlbumRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return Album[]
     */
    public function getAlbums(): array
    {
        $sql = 'select id from album;';
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $albumsId = $statement->fetchAll();
        $statement->closeCursor();
        $albums = [];
        foreach ($albumsId as $album) {
            $albumObj = $this->getAlbum($album->id);
            if ($albumObj !== null) {
                $albums[] = $albumObj;
            }
        }

        return $albums;
    }

    public function getAlbum(int $id): ?Album
    {
        $sql = 'select * from album where id = ?;';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);
        $album = $statement->fetch();
        $statement->closeCursor();
        if ($album !== false) {
            $sql = 'select song_id from album_songs where album_id = ?';
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$album->id]);
            $songs = $statement->fetchAll();
            return Album::createAlbum($album->id, $album->artist_id, $album->title, $songs);
        }

        return null;
    }

    public function deleteAlbum(int $id): void {
        $sql = 'delete from album where id=?;';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);
        $statement->closeCursor();
    }

    public function putAlbum(int $artist_id, string $title): void
    {
        $sql = 'insert into album(artist_id, title) values(?, ?);';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$artist_id, $title]);
    }
    public function putSongToAlbum(int $album_id, int $song_id): void
    {
        $sql = 'insert into album_songs(album_id, song_id) values(?, ?);';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$album_id, $song_id]);
    }

}