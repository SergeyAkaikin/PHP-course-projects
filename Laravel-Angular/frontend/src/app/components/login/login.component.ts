import { Component } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from 'src/app/services/auth.service';
import { AuthState } from 'src/app/services/auth.state';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {
  public form: FormGroup;

  constructor(
    private authService: AuthService,
    private router: Router,
    private authState: AuthState,
    ) {
    this.form = new FormGroup({
      userName: new FormControl(null, [Validators.required]),
      password: new FormControl(null, [Validators.required])
    });
  }

  public get loginControl() {
    return this.form.get('userName');
  }
  public get passwordControl() {
    return this.form.get('password');
  }

  public submit(): void {
    if (this.form.valid) {
      this.authService.login(this.form.value).subscribe(authInfo => {
        this.authState.setUserInfo(authInfo);
        this.router.navigateByUrl('/');
      })
    }
  }
}
