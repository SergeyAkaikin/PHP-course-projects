import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Album, Playlist, Song } from '../models/music';


@Injectable({
  providedIn: 'root'
})
export class MusicLibraryService {

  constructor(private http: HttpClient) { }

  public getTopAlbums(): Observable<Album[]> {
    return this.http.get<Album[]>('/api/albums/');
  }

  public getAlbum(albumId: number): Observable<{album: Album, canManage: boolean }> {
    return this.http.get<{album: Album, canManage: boolean }>(`/api/albums/${albumId}`);
  }

  public getAlbumSongs(albumId: number): Observable<Song[]> {
    return this.http.get<Song[]>(`/api/albums/${albumId}/songs`);
  }

  public getPlaylists(userId: number): Observable<Playlist[]> {
    return this.http.get<Playlist[]>(`/api/users/${userId}/playlists`);
  }

  public addSong(playlistId: number, songId: number): Observable<any> {
    return this.http.post<any>(`/api/playlists/${playlistId}/songs/${songId}`, {});
  }

  public removeSong(playlistId: number, songId: number): Observable<any> {
    return this.http.delete<any>(`/api/playlists/${playlistId}/songs/${songId}`, {});
  }

  public getUserSongs(userId: number): Observable<Song[]> {
    return this.http.get<Song[]>(`/api/users/${userId}/songs`);
  }

  public getCurrentUserMainPlaylist(): Observable<Playlist> {
    return this.http.get<Playlist>('/api/users/me/mainPlaylist');
  }

  public deleteAlbumSong(albumId: number, songId: number): Observable<any> {
    return this.http.delete<any>(`/api/albums/${albumId}/songs/${songId}`);
  }

  public updateAlbum(albumId: number, title: string): Observable<any> {
    return this.http.post<any>(`/api/albums/${albumId}`, title);
  }

  public uploadSong(albumId: number, formData: FormData): Observable<any> {
    return this.http.post<any>(`/api/albums/${albumId}/songs`, formData);
  }
}
