<?php
declare(strict_types=1);

namespace MusicService\Api\Controllers;


use MusicService\Api\Requests\GetRequests\GetByIdRequest;
use MusicService\Api\Requests\PutRequests\PutAlbumRequest;
use MusicService\Api\Requests\PutRequests\PutSongToAlbumRequest;
use MusicService\Api\Status;
use MusicService\DataAccess\DataRepository\AlbumRepository;
use MusicService\Http\Response;

class AlbumController extends BaseController
{

    public function __construct(private readonly AlbumRepository $albumRepository)
    {
    }

    public function getAlbums(): Response
    {

        return $this->successResponse($this->albumRepository->getAlbums());
    }

    public function getAlbum(GetByIdRequest $request): Response
    {
        $body = $request->getModel();
        $album = $this->albumRepository->getAlbum($body->id);
        if ($album === null) {
            return $this->failResponse(Status::NotFound, 'Album not found');
        }
        return $this->successResponse($album);
    }

    public function deleteAlbum(GetByIdRequest $request): Response
    {
        $body = $request->getModel();
        $this->albumRepository->deleteAlbum($body->id);
        return $this->successResponse(null);
    }

    public function putAlbum(PutAlbumRequest $request): Response
    {
        $body = $request->getModel();
        $this->albumRepository->putAlbum($body->artist_id, $body->title);
        return $this->successResponse(null);
    }

    public function putSongToAlbum(PutSongToAlbumRequest $request): Response
    {
        $body = $request->getModel();
        $this->albumRepository->putSongToAlbum($body->artist_id, $body->song_id);
        return $this->successResponse(null);
    }
}