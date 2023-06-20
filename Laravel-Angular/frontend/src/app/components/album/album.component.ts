import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Album, Playlist, Song } from 'src/app/models/music';
import { AuthState } from 'src/app/services/auth.state';
import { MusicLibraryService } from 'src/app/services/music-library.service';

@Component({
  selector: 'app-album',
  templateUrl: './album.component.html',
  styleUrls: ['./album.component.scss']
})
export class AlbumComponent {
  private userId: number = 0;
  public album: Album;
  public canManage: boolean;
  public songs: Song[];
  private playlist: Playlist;

  constructor(
    private route: ActivatedRoute,
    private musicLibrary: MusicLibraryService,
    private authState: AuthState,
    ) {

    this.album = this.route.snapshot.data['album']['album'];
    this.canManage = this.route.snapshot.data['album']['canManage'];
    this.songs = this.route.snapshot.data['albumSongs'];
    this.playlist = this.route.snapshot.data['playlist'];

    this.authState.getUserInfo().subscribe(authInfo => {
      if (authInfo !== null) {
        this.markPlaylistSongs(authInfo.id);
      }
    });

  }

  public addOrRemoveSong(song: Song) {
    if (song.added) {
      this.musicLibrary.removeSong(this.playlist.id, song.id).subscribe(() => song.added = false)
    } else {
      this.musicLibrary.addSong(this.playlist.id, song.id).subscribe(() => song.added = true);
    }
  }

//to back
  private markPlaylistSongs(userId: number) {
    this.userId = userId;
        this.musicLibrary.getUserSongs(this.userId).subscribe(data => {
          let playlist = data.map(song => song.id);
          this.songs.forEach((song) => {
            if (playlist.includes(song.id)) {
              song.added = true;
            } else {
              song.added = false;
            }
          })
        });
  }

  public deleteAlbumSong(song: Song) {
    this.musicLibrary.deleteAlbumSong(this.album.id, song.id).subscribe(() => {
      const index = this.songs.findIndex(s => s === song);
      this.songs.splice(index, 1);
    });
  }
}
