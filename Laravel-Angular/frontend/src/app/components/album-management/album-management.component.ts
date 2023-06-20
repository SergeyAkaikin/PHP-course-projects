import { Component } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { Album } from 'src/app/models/music';
import { MusicLibraryService } from 'src/app/services/music-library.service';

@Component({
  selector: 'app-album-management',
  templateUrl: './album-management.component.html',
  styleUrls: ['./album-management.component.scss']
})
export class AlbumManagementComponent {
  public album: Album;
  public form: FormGroup;

  constructor(
    private route: ActivatedRoute,
    private musicLibrary: MusicLibraryService,
    private router: Router,
    ) {
    this.album = this.route.snapshot.data['album']['album'];
    this.form = new FormGroup({
      title: new FormControl(this.album.title, [Validators.required]),
    });
  }

  public get titleControl() {
    return this.form.get('title');
  }

  public submit(): void {
    if (this.form.valid) {
      this.musicLibrary.updateAlbum(this.album.id, this.form.value).subscribe(() =>
        this.router.navigateByUrl(`/albums/${this.album.id}`)
      );
    }
  }
}
