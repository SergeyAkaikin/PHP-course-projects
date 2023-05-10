import { Component, Input } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { MusicLibraryService } from 'src/app/services/music-library.service';

@Component({
  selector: 'app-upload-song',
  templateUrl: './upload-song.component.html',
  styleUrls: ['./upload-song.component.scss']
})
export class UploadSongComponent {
  public form: FormGroup;
  @Input() artistId: number = 1;
  @Input() albumId: number = 1;


  constructor(
    private musicLibrary: MusicLibraryService,
    private router: Router
    ) {
    this.form = new FormGroup({
      file: new FormControl('', [Validators.required]),
      title: new FormControl('', [Validators.required]),
      genre: new FormControl('', [Validators.required]),
      fileSource: new FormControl('', [Validators.required]),
    });
  }
  public get titleControl() {
    return this.form.get('title');
  }

  public get genresControl() {
    return this.form.get('genres');
  }

  get f(){
    return this.form.controls;
  }

  onFileChange(event: any) {

    if (event.target.files.length > 0) {
      const file = event.target.files[0];
      this.form.patchValue({
        fileSource: file
      });
    }
  }



  public submit() {
    if (this.form.valid) {
      const formData = new FormData();
      formData.append('file', this.form.get('fileSource')?.value);
      formData.append('title', this.form.get('title')?.value);
      formData.append('genre', this.form.get('genre')?.value);
      formData.append('artistId', `${this.artistId}`);
      this.musicLibrary.uploadSong(this.albumId, formData).subscribe(() => {
        this.router.navigateByUrl(`/albums/${this.albumId}`);
      })
    }
  }
}

