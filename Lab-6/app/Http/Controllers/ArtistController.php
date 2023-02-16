<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\Artist;
use App\Repositories\ArtistRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use JsonMapper;

class ArtistController extends Controller
{
    public function __construct(private ArtistRepository $repository)
    {
    }

    /**
     *
     * @return Response
     */
    public function index()
    {
        $artists = $this->repository->getArtists();
        return response($artists, 200);

    }

    /**
     *
     * @param UserStoreRequest $request
     * @return Response
     */
    public function store(UserStoreRequest $request)
    {
        $validated = $request->validated();
        /**
         * @var Artist $data
         */
        $data = (object)$validated;
        $this->repository->putArtist(
            $data->name,
            $data->surname,
            $data->lastname,
            new Carbon($data->birth_date),
            $data->email,
            $data->user_name
        );

        return response()->noContent();
    }

    /**
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $artist = $this->repository->getArtist($id);
        return ($artist === null) ? response('Artist not found', 404) : response($artist, 200);
    }

    /**
     *
     * @param UserStoreRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UserStoreRequest $request, $id)
    {
        $validated = $request->validated();
        /**
         * @var Artist $data
         */
        $data = (object)$validated;
        $this->repository->updateArtist(
            $id,
            $data->name,
            $data->surname,
            $data->lastname,
            new Carbon($data->birth_date),
            $data->email,
            $data->user_name
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
        $this->repository->deleteArtist($id);

        return response()->noContent();

    }
}
