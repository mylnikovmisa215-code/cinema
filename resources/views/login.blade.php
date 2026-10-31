@extends('layout.index')
@section('main')
    <h1>Вход в личный кабинет</h1>
    <form action="{{route('store.login')}}" method="POST">
        @csrf
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Пароль">
        <input type="submit" value="Войти">
    </form>
@endsection