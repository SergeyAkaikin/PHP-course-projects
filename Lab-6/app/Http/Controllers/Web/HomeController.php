<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\AlbumRepository;
use App\Repositories\ArtistRepository;
use App\Repositories\SongRepository;
use App\Services\AlbumPageService;
use App\Services\CacheService;
use App\Utils\Mappers\AlbumMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly AlbumRepository  $albumRepository,
        private readonly SongRepository   $songRepository,
        private readonly AlbumPageService $albumPageService,
        private readonly CacheService     $cacheService,
    )
    {
    }

    public function albums(): View
    {
        return \view('home');
    }


    public function albumInfo(Request $request, int $album_id): JsonResponse|View
    {


        $album = $this->cacheService->getOrAdd(
            "full_albums:{$album_id}",
            function () use ($album_id) {
                $album = $this->albumRepository->getAlbum($album_id);

                if ($album === null) {
                    return response()->json('Album not found', 404);
                }


                $songs = $this->songRepository->getSongsByAlbum($album_id);
                $artist_name = (new ArtistRepository())->getArtist($album->artist_id)->user_name;
                return (new AlbumMapper())->mapFullAlbum($album, $songs, $artist_name);

            },
            120
        );

        $canManage = $this->albumPageService->canManage($request, $album_id);

        return \view('album', ['album' => $album, 'canManage' => $canManage]);
    }


    public function login(): View
    {
        return \view('login');
    }

}
