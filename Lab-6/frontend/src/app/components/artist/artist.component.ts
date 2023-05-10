import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { UserInfo } from 'src/app/models/user';
import { UserService } from 'src/app/services/user.service';

@Component({
  selector: 'app-artist',
  templateUrl: './artist.component.html',
  styleUrls: ['./artist.component.scss']
})
export class ArtistComponent {
  public artists: UserInfo[] = [];

  constructor(private userService: UserService, private route: ActivatedRoute) {
    this.artists = this.route.snapshot.data['artists'];
  }
}
