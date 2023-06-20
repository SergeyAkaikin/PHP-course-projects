import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './components/login/login.component';
import { HomeComponent } from './components/home/home.component';
import { AlbumComponent } from './components/album/album.component';
import { ArtistComponent } from './components/artist/artist.component';
import { MySongsComponent } from './components/my-songs/my-songs.component';
import { albumResolver, albumSongsResolver, albumsResolver, playlistResolver } from './resolvers/album.resolver';
import { authGuard } from './services/auth.guard';
import { UserComponent } from './components/user/user.component';
import { artistResolver, userAlbumsResolver, userInfoResolver, userResolver } from './resolvers/user.resolver';
import { AlbumManagementComponent } from './components/album-management/album-management.component';

const routes: Routes = [
  { path: 'login', component: LoginComponent },
  {
    path: '',
    component: HomeComponent,
    canActivate: [authGuard],
    resolve: {albums: albumsResolver}
  },
  {
    path: 'albums/:id',
    component: AlbumComponent,
    canActivate: [authGuard],
    resolve: {album: albumResolver, albumSongs: albumSongsResolver, playlist: playlistResolver}
  },
  {
    path: 'artists',
    component: ArtistComponent,
    canActivate: [authGuard],
    resolve: {artists: artistResolver}
  },
  {
    path: 'mySongs',
    component: MySongsComponent,
    canActivate: [authGuard],
    resolve: {userInfo: userInfoResolver, playlist: playlistResolver}
  },
  {
    path: 'users/:id',
    component: UserComponent,
    canActivate: [authGuard],
    resolve: {user: userResolver, albums: userAlbumsResolver}
  },
  {
    path: 'albums/:id/management',
    canActivate: [authGuard],
    component: AlbumManagementComponent,
    resolve: {album: albumResolver},
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
