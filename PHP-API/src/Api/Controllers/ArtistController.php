<?php
declare(strict_types=1);

namespace MusicService\Api\Controllers;

use MusicService\Api\Requests\GetRequests\GetByIdRequest;
use MusicService\Api\Requests\PutRequests\PutArtistRequest;
use MusicService\Api\Status;
use MusicService\DataAccess\DataRepository\ArtistRepository;
use MusicService\Http\Response;

class ArtistController extends BaseController
{

    public function __construct(private readonly ArtistRepository $repository)
    {
    }


    public function getArtists(): Response
    {

        return $this->successResponse($this->repository->getArtists());
    }

    public function getArtist(GetByIdRequest $request): Response
    {
        $body = $request->getModel();
        $artist = $this->repository->getArtist($body->id);
        if ($artist === null) {
            return $this->failResponse(Status::NotFound, 'Artist not found');
        }
        return $this->successResponse($artist);
    }

    public function deleteArtist(GetByIdRequest $request): Response
    {
        $body = $request->getModel();
        $this->repository->deleteArtist($body->id);
        return $this->successResponse(null);
    }

    public function putArtist(PutArtistRequest $request): Response
    {
        $body = $request->getModel();
        $this->repository->putArtist(
            $body->name,
            $body->surname,
            $body->lastname,
            $body->birth_date,
            $body->email,
            $body->user_name
        );
        return $this->successResponse(null);
    }
}