<?php
declare(strict_types=1);

namespace MusicService\DataAccess\DataRepository;

use MusicService\Domain\Song;
use PDO;

class SongRepository
{

    public function __construct(private readonly PDO $pdo)
    {
    }


    /**
     * @return Song[]
     */
    public function getSongs(): array
    {
        $sql = 'select * from song;';
        $statement = $this->pdo->query($sql);
        return array_map(
            static fn($row): Song => (new \JsonMapper())->map($row, new Song()),
            $statement->fetchAll()
        );
    }

    public function getSong(int $id): ?Song
    {
        $sql = 'select * from song where id=?';
        $statement = $this->pdo->prepare($sql);
        $flag = $statement->execute([$id]);
        if ($statement->rowCount() === 0) {
            return null;
        }
        return (new \JsonMapper())->map($statement->fetch(), new Song());
    }

    public function deleteSong(int $id): void
    {
        $sql = 'delete from song where id=?;';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);
    }

    public function putSong(int $artist_id, string $title, string $genre): void
    {
        $sql = 'insert into song(artist_id, title, genre) values(?, ?, ?, ?)';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$artist_id, $title, $genre]);
    }

}