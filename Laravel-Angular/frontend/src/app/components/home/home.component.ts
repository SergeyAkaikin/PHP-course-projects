import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Album } from 'src/app/models/music';
import { MusicLibraryService } from 'src/app/services/music-library.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent {
  public albums: Album[] = []

  constructor(private musicService: MusicLibraryService, private route: ActivatedRoute,) {
    this.albums = this.route.snapshot.data['albums']
  }


}
