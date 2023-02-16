<?php

namespace App\Http\Controllers;

use App\Http\Requests\SongStoreRequest;
use App\Http\Requests\SongToAlbumRequest;
use App\Models\Song;
use App\Repositories\SongRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ramsey\Collection\Collection;

class SongController extends Controller
{
    public function __construct(private SongRepository $repository)
    {
    }

    /**
     *
     * @return Response
     */
    public function index()
    {
        return response($this->repository->getSongs(), 200);
    }

    /**
     *
     * @param Request $request
     * @return Response
     */
    public function store(SongStoreRequest $request)
    {
        $validated = $request->validated();
        /**
         * @var Song $song
         */
        $song = (object)$validated;
        $this->repository->putSong(
            $song->artist_id,
            $song->title,
            $song->genre
        );
        return response()->noContent();
    }

    /**
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $song = $this->repository->getSong($id);
        return ($song === null) ? response()->json('Song not found', 404) : response()->json($song);
    }

    /**
     *
     * @param SongStoreRequest $request
     * @param int $id
     * @return Response
     */
    public function update(SongStoreRequest $request, $id)
    {
        $validated = $request->validated();
        /**
         * @var Song $song
         */
        $song = (object)$validated;
        $this->repository->updateSong(
            $id,
            $song->artist_id,
            $song->title,
            $song->genre
        );
        return response()->noContent();

    }

    /**
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->repository->deleteSong($id);
        return response()->noContent();
    }

    /**
     * @param int $id
     * @return Response
     */
    public function showAlbumSongs(int $id)
    {
        return response($this->repository->getSongsByAlbum($id),200);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function showArtistSongs(int $id)
    {
        return response()->json($this->repository->getSongsByArtist($id));
    }

    /**
     * @param SongToAlbumRequest $request
     * @return Response
     */
    public function storeAlbumSong(SongToAlbumRequest $request)
    {
        $validated = $request->validated();
        $this->repository->putSongToAlbum(
            $validated['album_id'],
            $validated['song_id']
        );
        return response()->noContent();
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function showPlaylistSongs(int $id)
    {
        return response()->json($this->repository->getSongsFromPlaylist($id));
    }
}
