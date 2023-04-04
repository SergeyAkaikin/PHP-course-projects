@extends('layout')

@section('header')
    <header id="header_list">
        <h1>
            @yield('header_content')
        </h1>
    </header>
@endsection

@section('content')
    <main id="main_content">
    @yield('main_content')
    </main>
    @endsection

@section('footer')
    <footer>
    Контактная информация:
    <a class="contacts" href="tel:+79093297832">+79093297832</a>
    </footer>
@endsection


