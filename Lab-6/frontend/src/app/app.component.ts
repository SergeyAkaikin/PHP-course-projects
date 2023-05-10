import { Component, OnInit } from '@angular/core';
import { UserService } from './services/user.service';
import { AuthState } from './services/auth.state';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  title = 'Music Service';

  constructor(private userService: UserService, private authState: AuthState) {
  }
  public ngOnInit(): void {
    this.userService.getCurrentUser().subscribe(
      userInfo => {
        this.authState.setUserInfo(userInfo);
      },
      error => {
        if (error.status === 401) {
          this.authState.setUserInfo(null);
        }
      });
  }
}
