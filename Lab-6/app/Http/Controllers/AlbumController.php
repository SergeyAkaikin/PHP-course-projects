<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumStoreRequest;
use App\Models\Album;
use App\Repositories\AlbumRepository;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class AlbumController extends Controller
{
    public function __construct(private AlbumRepository $repository)
    {
    }
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response($this->repository->getAlbums(), 200);
    }

    /**
     *
     * @param  AlbumStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AlbumStoreRequest $request)
    {
        $validated = $request->validated();
        /**
         * @var Album $album
         */
        $album = (object)$validated;
        $this->repository->putAlbum($album->artist_id, $album->title);
        return response()->noContent();
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $album = $this->repository->getAlbum($id);
        return ($album === null) ? new Response('Album not found', 404) : response($album, 200);
    }

    /**
     *
     * @param  AlbumStoreRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AlbumStoreRequest $request, $id)
    {
        $validated = $request->validated();
        /**
         * @var Album $album
         */
        $album = (object)$validated;
        $this->repository->updateAlbum($id, $album->artist_id, $album->title);
        return response()->noContent();
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->repository->deleteAlbum($id);
        return response()->noContent();
    }
}