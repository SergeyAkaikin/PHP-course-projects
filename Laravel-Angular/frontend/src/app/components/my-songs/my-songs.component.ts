import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Playlist, Song } from 'src/app/models/music';
import { AuthState } from 'src/app/services/auth.state';
import { MusicLibraryService } from 'src/app/services/music-library.service';

@Component({
  selector: 'app-my-songs',
  templateUrl: './my-songs.component.html',
  styleUrls: ['./my-songs.component.scss']
})
export class MySongsComponent {
  private userId: number = 1;
  public songs: Song[] = [];
  private playlist: Playlist;
  constructor(
    private musicLibrary: MusicLibraryService,
    private route: ActivatedRoute,
    ) {
    this.userId = this.route.snapshot.data['userInfo'].id;
    this.musicLibrary.getUserSongs(this.userId).subscribe(data => this.songs = data);
    this.playlist = this.route.snapshot.data['playlist'];
  }

  public deleteSong(song: Song) {
    this.musicLibrary.removeSong(this.playlist.id, song.id).subscribe(() => location.reload());
  }

}
