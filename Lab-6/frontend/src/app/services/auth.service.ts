import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { UserInfo } from '../models/user';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  constructor(private http: HttpClient) { }

  public login(authInfo: { userName: string, password: string }): Observable<UserInfo> {
    return this.http.post<UserInfo>('/api/login', authInfo)
  }

  public logout(): Observable<any> {
    return this.http.get<any>('/api/logout');
  }
}
