<?php


namespace App\Utils\Mappers;

use App\Models\Album;
use App\Models\Song;
use App\Repositories\ArtistRepository;
use App\ViewModels\AlbumFullModel;
use App\ViewModels\AlbumModel;
use App\ViewModels\SongModel;
use Illuminate\Support\Collection;

class AlbumMapper
{
    public function mapAlbum(Album $album): AlbumModel
    {
        $mapped = new AlbumModel();
        $mapped->id = $album->id;
        $mapped->artist_id = $album->artist_id;
        $mapped->title = $album->title;
        $mapped->date = $album->created_at->year;
        $mapped->rating = $album->rating;
        $mapped->artist_name = (new ArtistRepository())->getArtist($album->artist_id)->user_name;

        return $mapped;
    }

    /**
     * @param Collection<int, Song> $songs
     */
    public function mapFullAlbum(Album $album, Collection $songs, string $artist_name): AlbumFullModel
    {
        $mapped = new AlbumFullModel();
        $mapped->id = $album->id;
        $mapped->artist_id = $album->artist_id;
        $mapped->title = $album->title;
        $mapped->date = $album->created_at->year;
        $mapped->songCount = $songs->count();
        $mapped->rating = $album->rating;
        $mapped->artist_name = $artist_name;
        $songMapper = new SongMapper();
        $mapped->songs = $songs->map(fn(Song $song): SongModel => $songMapper->mapSong($song))->toArray();

        return $mapped;
    }

}
