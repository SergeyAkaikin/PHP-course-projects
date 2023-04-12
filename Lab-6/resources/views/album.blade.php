<!-- resources/views/album.blade.php -->
<?php
use Carbon\Carbon
/** @var \App\ViewModels\AlbumFullModel $album*/ ?>
@extends('base')


@section('header_content')
    Album page
@endsection

@section('main_content')
    <section>
        <h1 id="title">{{$album->title}}</h1>
        <img id="cover" src="" alt="album cover">
        <p id="year">Year - {{$album->date}}</p>
        <p id="artis_name" onclick="renderArtist({{$album->artist_id}})" style="cursor: pointer">Artist - {{$album->artist_name}}</p>
        <div class="songs_list">
            <h3>Songs:</h3>
            <ol id="song_list">
                @foreach($album->songs as $i => $song)
                <li id="song-{{$song->id}}">
                    {{$song->title}} - {{$song->genre}}.<br>
                    <audio src="http://minio.music.local:9005/audio/{{$song->path}}" controls></audio>
                    <br>
                    <button id="add-song-{{$song->id}}" class="button" onclick="addSong({{$song->id}})">Add song</button>
                        @if($canManage)
                        <br>
                        <button class="button" onclick="deleteSong({{$album->id}}, {{$song->id}})">Delete Song</button>
                        @endif
                </li>
                @endforeach
            </ol>
        </div>
        @if($canManage)
        <button class="button" id="album_update" onclick="renderUpdating({{$album->id}})">Update</button>
        <button class="button" id="album_delete" onclick="renderDeleting({{$album->id}})">Delete Album</button>
        @endif

    </section>
@endsection
<script src="{{URL::asset('js/base.js')}}"></script>
<script src="{{URL::asset('js/album.js')}}"></script>
<script src="{{URL::asset('js/album_page.js')}}" defer></script>
