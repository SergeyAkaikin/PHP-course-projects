<?php
declare(strict_types=1);

namespace MusicService\Api\Controllers;

use MusicService\Api\Requests\DeleteRequests\DeleteSongFromPlaylistRequest;
use MusicService\Api\Requests\GetRequests\GetByIdRequest;
use MusicService\Api\Requests\GetRequests\GetUserPlaylistRequest;
use MusicService\Api\Requests\PutRequests\PutPlaylistRequest;
use MusicService\Api\Requests\PutRequests\PutSongToPlaylistRequest;
use MusicService\Api\Status;
use MusicService\DataAccess\DataRepository\PlaylistRepository;
use MusicService\Http\Response;

class PlaylistController extends BaseController
{
    public function __construct(private readonly PlaylistRepository $repository)
    {
    }

    public function getPlayLists(): Response
    {
        return $this->successResponse($this->repository->getPlaylists());
    }

    public function getPlaylist(GetByIdRequest $request): Response
    {
        $body = $request->getModel();
        $playlist = $this->repository->getPlaylist($body->id);
        if ($playlist === null) {
            return $this->failResponse(Status::NotFound, 'Playlist not found');
        }
        return $this->successResponse($playlist);
    }

    public function getUserPlaylist(GetUserPlaylistRequest $request): Response
    {
        $body = $request->getModel();
        return $this->successResponse($this->repository->getPlaylistsByUserId($body->user_id));
    }

    public function deletePlaylist(GetByIdRequest $request): Response
    {
        $body = $request->getModel();
        $this->repository->deletePlaylist($body->id);
        return $this->successResponse(null);
    }

    public function deleteSongFromPlaylist(DeleteSongFromPlaylistRequest $request): Response
    {
        $body = $request->getModel();
        $this->repository->deleteSongFromPlaylist($body->playlist_id, $body->song_id);
        return $this->successResponse(null);
    }

    public function putPlaylist(PutPlaylistRequest $request): Response
    {
        $body = $request->getModel();
        $this->repository->putPlaylist($body->user_id, $body->title);
        return $this->successResponse(null);
    }

    public function putSongToPlaylist(PutSongToPlaylistRequest $request): Response
    {
        $body = $request->getModel();
        $this->repository->putSongToPlaylist($body->playlist_id, $body->song_id);
        return $this->successResponse(null);
    }


}