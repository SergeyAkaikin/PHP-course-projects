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
        $sql = 'select * from album;';
        $statement = $this->pdo->query($sql);
        $mapper = new \JsonMapper();
        return array_map(
            static fn ($row): Album => $mapper->map($row, new Album()),
            $statement->fetchAll()
        );
    }

    public function getAlbum(int $id): ?Album
    {
        $sql = 'select * from album where id = ?;';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);
        if ($statement->rowCount() === 0) {
            return null;
        }
        return (new \JsonMapper())->map($statement->fetch(), new Album());;
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