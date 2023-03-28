<!-- resources/views/login.blade.php -->
@extends('base')

@section('header_content')
        <h1>Login</h1>
@endsection

@section('main_content')
        <section class="login">
            <form class="login" action = "/api/login" method="POST">
                <label for="user_name">User name</label>
                <input name="user_name" id="user_name">
                <label for="password"> Password</label>
                <input type="password" name="password" id="password">
                <button class="login">Login</button>
            </form>
        </section>
@endsection


