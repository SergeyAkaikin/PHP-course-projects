<?php


namespace App\Utils\Mappers;

use App\Models\Album;
use App\Models\Song;
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
        $mapped->date = $album->created_at;

        return $mapped;
    }

    /**
     * @param Collection<int, Song> $songs
     */
    public function mapFullAlbum(Album $album, Collection $songs): AlbumFullModel
    {
        $mapped = new AlbumFullModel();
        $mapped->id = $album->id;
        $mapped->artist_id = $album->artist_id;
        $mapped->title = $album->title;
        $mapped->date = $album->created_at;
        $mapped->songCount = $songs->count();
        $mapped->rating = $album->rating;
        $mapped->songs = $songs->map(fn(Song $song): SongModel => $this->mapSong($song))->toArray();

        return $mapped;
    }

    public function mapSong(Song $song): SongModel
    {
        $mapped = new SongModel();
        $mapped->id = $song->id;
        $mapped->artist_id = $song->artist_id;
        $mapped->title = $song->title;
        $mapped->genre = $song->genre;

        return $mapped;
    }
}
