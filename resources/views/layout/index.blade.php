<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif;" >
    <header>
        <h1>Сайт кино</h1>
        @guest
            <a href="{{route('show.register')}}">Регистрация</a>
            <a href="{{route('show.login')}}">Авторизация</a>
        @endguest

        @auth
            <form action="{{route('logout')}}" method="POST">
                <input type="submit" value="Выйти из учетной записи">
            </form>
        @endauth
    </header>
    <main>
        @yield('main')
    </main>
    <footer>
        <p>подвал💀💀💀</p>
    </footer>
</body>
</html>