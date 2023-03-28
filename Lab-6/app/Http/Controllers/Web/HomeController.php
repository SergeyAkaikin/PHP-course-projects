<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Repositories\AlbumRepository;
use App\Repositories\SongRepository;
use App\Services\AlbumPageService;
use App\Services\CacheService;
use App\Utils\Mappers\AlbumMapper;
use App\ViewModels\AlbumFullModel;
use App\ViewModels\AlbumModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly AlbumRepository $albumRepository,
        private readonly SongRepository  $songRepository,
        private readonly AlbumPageService $albumPageService,
        private readonly CacheService $cacheService,
    )
    {
    }

    public function index(): View
    {
//        $albums = $this->albumRepository->getAlbums();
//
//        $albumModels = $albums->map(
//            function (Album $album): AlbumModel {
//                $songs = $this->songRepository->getSongsByAlbum($album->id);
//
//                return (new AlbumMapper())->mapFullAlbum($album, $songs);
//            }
//        );
//
//        $albumModels = $this->albumPageService->sortedByRate($albumModels)->toArray();

        return \view('home');
    }


    public function show(Request $request, int $album_id): JsonResponse|View
    {


        $album = $this->cacheService->getOrAdd(
            "full_albums:{$album_id}",
            function () use ($album_id)
            {
            $album = $this->albumRepository->getAlbum($album_id);

            if ($album === null) {
                return response()->json('Album not found', 404);
            }


            $songs = $this->songRepository->getSongsByAlbum($album_id);
            return (new AlbumMapper())->mapFullAlbum($album, $songs);

        },
            120
        );

        $album = (new \JsonMapper())->map($album, new AlbumFullModel());
        $canManage = $this->albumPageService->canManage($request, $album_id);
        $canDelete = $this->albumPageService->canDelete($request, $album_id);

        return \view('album', ['album' => $album, 'canManage' => $canManage, 'canDelete' => $canDelete]);
    }

    public function login(): View
    {
        return \view('login');
    }


}
