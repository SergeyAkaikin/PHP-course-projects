<?php
declare(strict_types=1);
namespace MusicService\DataAccess\DataRepository;

use Carbon\Carbon;
use MusicService\Domain\Artist;
use PDO;

class ArtistRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }



    /**
     * @return Artist[]
     */
    public function getArtists(): array
    {
        $sql = 'select id from artist;';
        $statement = $this->pdo->query($sql);
        $artistsId = $statement->fetchAll();
        /** @var Artist[] $artists */
        $artists = [];
        foreach ($artistsId as $artistId) {
            $artists[] = $this->getArtist($artistId->id);
        }
        return $artists;
    }

    public function getArtist(int $id): ?Artist
    {
        $sql = 'select * from artist where id=?';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);
        if ($statement->rowCount() === 0) {
            return null;
        }
        /**
         * @var Artist $artist
         */
        $artist = (new \JsonMapper())->map($statement->fetch(), new Artist());


        $sql = 'select id from album where artist_id = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$artist->id]);
        $albums = array_map(
            static fn ($row): int => $row->id,
            $statement->fetchAll()
        );

        $sql = 'select id from song where artist_id = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$artist->id]);
        $songs = array_map(
            static fn ($row): int => $row->id,
            $statement->fetchAll()
        );

        $artist->songs_id = $songs;
        $artist->albums_id = $albums;
        return $artist;
    }

    public function deleteArtist(int $id): void
    {
        $sql = 'delete from artist where id=?;';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);
    }

    public function putArtist(
        string $name,
        string $surname,
        string $lastname,
        Carbon $birth_date,
        string $email,
        string $artist_name
    ): void
    {
        $sql = 'insert into artist(name, surname, lastname, birth_date, email, artist_name)
                values (?, ?, ?, ?, ?, ?);';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            $name, $surname, $lastname, $birth_date->toDateString(), $email, $artist_name
        ]);
    }
}