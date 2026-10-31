@extends('layout.index')
@section('main')
<h1>Регистрация на сайте</h1>
    <form action="{{route('store.register')}}" method="POST">
        @csrf
        <input type="text" name="login" placeholder="Login">    
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Пароль">
        <input type="submit" value="Зарегистрироваться">
    </form>
@endsection