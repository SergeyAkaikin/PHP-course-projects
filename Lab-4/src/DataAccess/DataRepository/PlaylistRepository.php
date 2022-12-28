<?php
declare(strict_types=1);

namespace MusicService\DataAccess\DataRepository;

use MusicService\Domain\Playlist;
use PHPUnit\Util\Json;

class PlaylistRepository
{
    public function __construct(private readonly \PDO $pdo)
    {
    }

    /**
     * @return Playlist[]
     */
    public function getPlaylists(): array
    {
        $sql = 'select * from playlist;';
        $statement = $this->pdo->query($sql);
        $playlists = array_map(
            static fn($row): Playlist => (new \JsonMapper())->map($row, new Playlist()),
            $statement->fetchAll()
        );

        foreach ($playlists as $playlist) {
            $id = $playlist->id;
            $sql = "select song_id from playlist_songs where playlist_id={$id}";
            $statement = $this->pdo->query($sql);
            $playlist->songs_id = array_map(
                static fn($row): int => $row->song_id,
                $statement->fetchAll()
            );
        }

        return $playlists;
    }

    public function getPlaylist(int $id): ?Playlist
    {
        $sql = "select * from playlist where id={$id}";
        $statement = $this->pdo->query($sql);
        if ($statement->rowCount() === 0) {
            return null;
        }
        $userPlaylist = (new \JsonMapper())->map((object)$statement->fetch(), new Playlist());
        $sql = "select song_id from playlist_songs where id={$id}";
        $statement = $this->pdo->query($sql);
        $userPlaylist->songs_id = array_map(
            static fn($row): int => $row->song_id,
            $statement->fetchAll()
        );

        return $userPlaylist;
    }

    /**
     * @return Playlist[]
     */
    public function getPlaylistsByUserId(int $user_id): array
    {
        $sql = "select * from playlist where user_id={$user_id}";
        $statement = $this->pdo->query($sql);
        $playlists = array_map(
            static fn ($row): Playlist => (new \JsonMapper())->map($row, new Playlist()),
            $statement->fetchAll()
        );

        foreach ($playlists as $playlist) {
            $sql = "select song_id from playlist_songs where playlist_id = {$playlist->id}";
            $statement = $this->pdo->query($sql);
            $playlist->songs_id = array_map(
                static fn($row): int => $row->song_id,
                $statement->fetchAll()
            );
        }

        return $playlists;
    }

    public function deletePlaylist(int $id): void
    {
        $sql = "delete from playlist where id = {$id}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
    }

    public function deleteSongFromPlaylist(int $playlist_id, int $song_id): void
    {
        $sql = "delete from playlist_songs where playlist_id = {$playlist_id} and song_id = {$song_id}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
    }

    public function putPlaylist(int $user_id, string $title): void
    {
        $sql = "insert into playlist(user_id, title) values ({$user_id}, {$title})";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
    }

    public function putSongToPlaylist(int $playlist_id, int $song_id): void
    {
        $sql = "insert into playlist_songs(playlist_id, song_id) values ({$playlist_id}, {$song_id})";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
    }

}