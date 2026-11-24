<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>UNDERGROUND CINEMA</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg: #111111;
            --surface: #1d1d1d;
            --surface-light: #252525;
            --text: #d6d0c4;
            --muted: #858078;
            --accent: #8f2929;
            --accent-hover: #702020;
            --border: #353535;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: #151515;
            border-bottom: 1px solid var(--border);
        }

        .navbar {
            min-height: 75px;
        }

        .brand {
            color: var(--text);
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 3px;
            text-decoration: none;
        }

        .brand span {
            color: var(--accent);
        }

        .nav-link {
            color: var(--muted) !important;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-link:hover {
            color: var(--text) !important;
        }

        .btn-primary {
            background-color: var(--accent);
            border-color: var(--accent);
            color: white;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: var(--accent-hover);
            border-color: var(--accent-hover);
        }

        .btn-outline-light {
            border-color: var(--border);
            color: var(--text);
        }

        .btn-outline-light:hover {
            background-color: var(--surface-light);
            border-color: var(--muted);
            color: white;
        }

        .card {
            background-color: var(--surface);
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 4px;
        }

        .form-control,
        .form-select {
            background-color: var(--surface-light);
            border: 1px solid var(--border);
            color: var(--text);
        }

        .form-control:focus,
        .form-select:focus {
            background-color: var(--surface-light);
            border-color: var(--accent);
            color: var(--text);
            box-shadow: 0 0 0 0.2rem rgba(143, 41, 41, 0.2);
        }

        .form-control::placeholder {
            color: var(--muted);
        }

        .table {
            --bs-table-bg: var(--surface);
            --bs-table-color: var(--text);
            --bs-table-border-color: var(--border);
        }

        .table thead th {
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        h1, h2, h3, h4, h5 {
            color: var(--text);
            font-weight: 700;
        }

        a {
            color: #c66b6b;
        }

        a:hover {
            color: #e08b8b;
        }

        main {
            flex: 1;
            padding-top: 40px;
            padding-bottom: 60px;
        }

        footer {
            background-color: #151515;
            border-top: 1px solid var(--border);
            padding: 22px 0;
            color: var(--muted);
            font-size: 12px;
            letter-spacing: 1px;
            text-align: center;
        }

        footer p {
            margin: 0;
        }

        .text-muted {
            color: var(--muted) !important;
        }
    </style>
</head>

<body>

    <header>
        <div class="container">
            <nav class="navbar d-flex justify-content-between align-items-center">

                <a href="{{ url('/') }}" class="brand">
                    UNDERGROUND<span>.</span>
                </a>

                <div class="d-flex align-items-center gap-3">

                    @guest
                        <a href="{{ route('show.register') }}" class="nav-link">
                            Регистрация
                        </a>

                        <a href="{{ route('show.login') }}" class="btn btn-primary btn-sm px-3">
                            Войти
                        </a>
                    @endguest

                    @auth
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf

                            <button type="submit" class="btn btn-outline-light btn-sm px-3">
                                Выйти
                            </button>
                        </form>
                    @endauth

                </div>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            @yield('main')
        </div>
    </main>

    <footer>
        <div class="container">
            <p>UNDERGROUND CINEMA — INDEPENDENT MOVIE SPACE © 2026</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>