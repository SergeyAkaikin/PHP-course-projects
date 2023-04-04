<!-- resources/views/home.blade.php -->
<?php /**@var \App\ViewModels\AlbumFullModel[] $albums */?>
@extends('base')

@section('header_content')
    Albums
@endsection

@section('main_content')
    <section>
        <ol id="list" class="list">
        </ol>
    </section>
@endsection
<script src="{{URL::asset('js/artist.js')}}"></script>
<script src="{{URL::asset('js/base.js')}}"></script>
<script src="{{URL::asset('js/home_page.js')}}" defer></script>



