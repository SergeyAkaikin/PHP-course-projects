import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { UserInfo } from '../models/user';
import { Observable } from 'rxjs';
import { Album } from '../models/music';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  constructor(private http: HttpClient) { }

  public getCurrentUser(): Observable<UserInfo> {
    return this.http.get<UserInfo>('/api/users/me');
  }

  public getUser(userId: number): Observable<UserInfo> {
    return this.http.get<UserInfo>(`/api/users/${userId}`);
  }
  public getUserAlbums(userId: number): Observable<Album[]> {
    return this.http.get<Album[]>(`/api/users/${userId}/albums`);
  }
  public getArtists(): Observable<UserInfo[]> {
    return this.http.get<UserInfo[]>('/api/artists');
  }
}
