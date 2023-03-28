@php use App\ViewModels\AlbumFullModel; @endphp
    <!-- resources/views/album.blade.php -->
<?php
/**
 * @var AlbumFullModel $album
 * @var bool $canManage
 * @var bool $canDelete
 */
?>

@extends('base')


@section('header_content')
    <h1>Album page</h1>
@endsection

@section('main_content')
    <section>
        <h1 id="title">{{$album->title}}</h1>
        <img src="" alt="album cover">
        <p>Year - {{$album->date->year}}</p>
        <div class="songs_list">
            <h3>Songs:</h3>
            <ul>
                @foreach($album->songs as $i => $song)
                    <li>{{$song->title}} - ({{$song->genre}})</li>
                @endforeach
            </ul>
        </div>
        @if($canManage)
            <input id="album_id" type="hidden" value="{{$album->id}}">
            <a class="button" id="album_update">Update</a>
            <a class="button" id="add_album_song">Add song</a>
        @endif
        @if($canDelete)
            <a class="button" id="album_delete" onclick="deleteAlbum({{$album->id}})">Delete</a>
        @endif
    </section>
    <script src="{{URL::asset('js/album.js')}}" defer></script>
@endsection

