import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from 'src/app/services/auth.service';
import { AuthState } from 'src/app/services/auth.state';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent {
  public name: string|null = null;
  public isLogged: boolean = false;

  constructor(private authState: AuthState, private authService: AuthService, private router: Router) {
    this.authState.getUserInfo().subscribe(authInfo => {
      if (authInfo !== null) {
        this.name = authInfo.userName;
        this.isLogged = true;
      } else {
        this.name = null;
        this.isLogged = false;
      }

    })
  }

  public logout(): void {
    this.authService.logout().subscribe(() => {
      this.authState.setUserInfo(null);
      this.router.navigateByUrl('/login');
    })
  }
}
