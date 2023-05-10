import { inject } from '@angular/core';
import {
  RouterStateSnapshot,
  ActivatedRouteSnapshot,
  ResolveFn
} from '@angular/router';
import { Album, Playlist, Song } from '../models/music';
import { MusicLibraryService } from '../services/music-library.service';
import { AuthState } from '../services/auth.state';


export const albumResolver: ResolveFn<{album: Album, canManage: boolean}> =
  (route: ActivatedRouteSnapshot, state: RouterStateSnapshot) => {
    return inject(MusicLibraryService).getAlbum(+route.paramMap.get('id')!);
  }
export const albumSongsResolver: ResolveFn<Song[]> =
  (route: ActivatedRouteSnapshot, state: RouterStateSnapshot) => {
    return inject(MusicLibraryService).getAlbumSongs(+route.paramMap.get('id')!);
  }
export const albumsResolver: ResolveFn<Album[]> =
  (route: ActivatedRouteSnapshot, state: RouterStateSnapshot) => {
    return inject(MusicLibraryService).getTopAlbums();
  }
  export const playlistResolver: ResolveFn<Playlist> =
  (route: ActivatedRouteSnapshot, state: RouterStateSnapshot) => {
    return inject(MusicLibraryService).getCurrentUserMainPlaylist();
  }

