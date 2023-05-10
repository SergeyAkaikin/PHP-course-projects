import { inject } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivateFn, Router, RouterStateSnapshot } from '@angular/router';
import { filter, map } from 'rxjs';
import { AuthState } from './auth.state';

export const authGuard: CanActivateFn =
  (route: ActivatedRouteSnapshot, state: RouterStateSnapshot) => {
    const router = inject(Router);
    const authState = inject(AuthState);

    return authState.getUserInfo().pipe(
      filter(authInfo => !authState.isLoading),
      map(userInfo => {
        if (userInfo === null) {
          router.navigateByUrl('/login');
        }

        return userInfo !== null
      })
    )
  };
