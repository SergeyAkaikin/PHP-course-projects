import { inject } from '@angular/core';
import {
  RouterStateSnapshot,
  ActivatedRouteSnapshot,
  ResolveFn
} from '@angular/router';
import { UserInfo } from '../models/user';
import { UserService } from '../services/user.service';
import { Album, Song } from '../models/music';
import { MusicLibraryService } from '../services/music-library.service';
import { AuthState } from '../services/auth.state';


export const userResolver: ResolveFn<UserInfo> =
  (route: ActivatedRouteSnapshot, state: RouterStateSnapshot) => {
    return inject(UserService).getUser(+route.paramMap.get('id')!);
  }
export const userAlbumsResolver: ResolveFn<Album[]> =
  (route: ActivatedRouteSnapshot, state: RouterStateSnapshot) => {
    return inject(UserService).getUserAlbums(+route.paramMap.get('id')!);
  }

export const artistResolver: ResolveFn<UserInfo[]> =
  (route: ActivatedRouteSnapshot, state: RouterStateSnapshot) => {
    return inject(UserService).getArtists();
  }

  export const userInfoResolver: ResolveFn<UserInfo|null> =
  (route: ActivatedRouteSnapshot, state: RouterStateSnapshot) => {
    return inject(AuthState).getUserInfo();
  }
