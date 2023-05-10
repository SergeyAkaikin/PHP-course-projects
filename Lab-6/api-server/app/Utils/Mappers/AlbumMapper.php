<?php


namespace App\Utils\Mappers;

use App\Models\Album;
use App\Models\Song;
use App\ApiModels\AlbumFullModel;
use App\ApiModels\AlbumModel;
use App\ApiModels\SongModel;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;

class AlbumMapper
{
    public function mapAlbum(Album $album): AlbumModel
    {
        $mapped = new AlbumModel();
        $mapped->id = $album->id;
        $mapped->artistId = $album->artist_id;
        $mapped->title = $album->title;
        $mapped->date = $album->created_at->year;
        $mapped->rating = $album->rating;
        $mapped->artistName = (new UserRepository())->getUser($album->artist_id)->user_name;

        return $mapped;
    }

    /**
     * @param Collection<int, Song> $songs
     */
    public function mapFullAlbum(Album $album, Collection $songs): AlbumFullModel
    {
        $mapped = new AlbumFullModel();
        $mapped->id = $album->id;
        $mapped->artistId = $album->artist_id;
        $mapped->title = $album->title;
        $mapped->date = $album->created_at->year;
        $mapped->songCount = $songs->count();
        $mapped->rating = $album->rating;
        $mapped->artistName = (new UserRepository())->getUser($album->artist_id)->user_name;
        $songMapper = new SongMapper();
        $mapped->songs = $songs->map(fn(Song $song): SongModel => $songMapper->mapSong($song))->toArray();

        return $mapped;
    }

}
