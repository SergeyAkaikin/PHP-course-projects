import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Album } from 'src/app/models/music';
import { UserInfo } from 'src/app/models/user';

@Component({
  selector: 'app-user',
  templateUrl: './user.component.html',
  styleUrls: ['./user.component.scss']
})
export class UserComponent {
  public user: UserInfo;
  public albums: Album[];

  constructor(private route: ActivatedRoute) {
    this.user =  this.route.snapshot.data['user'];
    this.albums =  this.route.snapshot.data['albums'];
  }
}
