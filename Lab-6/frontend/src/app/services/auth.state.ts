import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable } from 'rxjs';
import { UserInfo } from '../models/user';

@Injectable({
  providedIn: 'root'
})
export class AuthState {

  private userInfo: BehaviorSubject<UserInfo|null> = new BehaviorSubject<UserInfo|null>(null);

  private _isLoading: boolean = true;

  public setUserInfo(user: UserInfo|null): void {
    this._isLoading = false;
    this.userInfo.next(user);
  }

  public getUserInfo(): Observable<UserInfo|null> {
    return this.userInfo;
  }

  public get isLoading(): boolean {
    return this._isLoading;
  }
}
