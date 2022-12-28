<?php
declare(strict_types=1);

namespace MusicService\Api\Controllers;

use MusicService\Api\Requests\GetRequests\GetByIdRequest;
use MusicService\Api\Requests\PutRequests\PutSongRequest;
use MusicService\Api\Status;
use MusicService\DataAccess\DataRepository\SongRepository;
use MusicService\Domain\Song;
use MusicService\Http\Request;
use MusicService\Http\Response;

class SongController extends BaseController
{
    public function __construct(private readonly SongRepository $songRepository)
    {
    }

    public function getSongs(): Response
    {
        return $this->successResponse($this->songRepository->getSongs());
    }

    public function getSong(GetByIdRequest $request): Response
    {
        $body = $request->getModel();
        $song = $this->songRepository->getSong($body->id);
        if ($song === null) {
            return $this->failResponse(Status::NotFound, 'Song not found');
        }
        return $this->successResponse($song);
    }

    public function deleteSong(GetByIdRequest $request): Response
    {
        $body = $request->getModel();
        $this->songRepository->deleteSong($body->id);
        return $this->successResponse(null);
    }

    public function putSong(PutSongRequest $request): Response
    {
        $body = $request->getModel();
        $this->songRepository->putSong(
            $body->artist_id,
            $body->title,
            $body->genre
        );

        return $this->successResponse(null);
    }

}