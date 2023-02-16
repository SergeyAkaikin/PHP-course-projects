<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaylistStoreRequest;
use App\Http\Requests\PlaylistUpdateRequest;
use App\Http\Requests\SongPlaylistStoreRequest;
use App\Models\Playlist;
use App\Repositories\PlaylistRepository;

class PlaylistController extends Controller
{
    public function __construct(private PlaylistRepository $repository)
    {
    }
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response($this->repository->getPlaylists());
    }

    /**
     *
     * @param  PlaylistStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlaylistStoreRequest $request)
    {
        $validated = $request->validated();
        /**
         * @var Playlist $playlist
         */
        $playlist = (object)$validated;
        $this->repository->putPlaylist(
            $playlist->user_id,
            $playlist->title
        );

        return response()->noContent();
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $playlist = $this->repository->getPlaylist($id);
        return ($playlist === null) ? response('Playlist not found', 404) : response($playlist, 200);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlaylistUpdateRequest $request, $id)
    {
        $validated = $request->validated();
        $this->repository->updatePlaylist(
            $id,
            $validated['title'],
        );

        return response()->noContent();
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->repository->deletePlaylist($id);
        return response()->noContent();
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function showUserPlaylists(int $id)
    {
        return response($this->repository->getPlaylistsByUserId($id));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function putSongToPlaylist(SongPlaylistStoreRequest $request, int $id)
    {
        $validated = $request->validated();
        $this->repository->putSongToPlaylist($id, $validated['song_id']);
        return response()->noContent();
    }
    /**
     * @return \Illuminate\Http\Response
     */
    public function deleteSongFromPlaylist(int $playlist_id, int $song_id)
    {
       $this->repository->deleteSongFromPlaylist($playlist_id, $song_id);
       return response()->noContent();
    }
}
