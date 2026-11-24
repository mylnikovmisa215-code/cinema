
@extends('layout.index')

@section('main')

    <h1>Панель администратора</h1>

    <nav>
        <a href="{{ route('show.admin') }}">Главная</a> |
        <a href="{{ route('admin.movies') }}">Фильмы</a> |
        <a href="{{ route('admin.screenings') }}">Сеансы</a> |
        <a href="{{ route('admin.screenings.create') }}">Добавить сеанс</a>
    </nav>

    <br>

    {{-- Сообщения об успешных действиях --}}
    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    {{-- Сообщения об ошибках --}}
    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    @if ($errors->any())
        <div>
            <strong>Ошибки:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Форма добавления дополнительного сеанса --}}
    @if (request()->routeIs('admin.screenings.create'))

        <h2>Добавление дополнительного сеанса</h2>

        <form action="{{ route('admin.screenings.store') }}" method="POST">
            @csrf

            <label>Выберите фильм</label>
            <select name="movie_id" required>
                <option value="">Выберите фильм</option>

                @foreach ($movies as $movie)
                    <option value="{{ $movie->id }}"
                        {{ old('movie_id') == $movie->id ? 'selected' : '' }}>
                        {{ $movie->title }}
                    </option>
                @endforeach
            </select>

            <br><br>

            <label>Выберите зал</label>
            <select name="hall_id" required>
                <option value="">Выберите зал</option>

                @foreach ($halls as $hall)
                    <option value="{{ $hall->id }}"
                        {{ old('hall_id') == $hall->id ? 'selected' : '' }}>
                        {{ $hall->name_hall }}
                    </option>
                @endforeach
            </select>

            <br><br>

            <label>Дата и время сеанса</label>
            <input
                type="datetime-local"
                name="start_time"
                value="{{ old('start_time') }}"
                required
            >

            <br><br>

            <label>Цена билета</label>
            <input
                type="number"
                name="price"
                min="0"
                step="0.01"
                value="{{ old('price') }}"
                required
            >

            <br><br>

            <label>Формат показа</label>
            <select name="format" required>
                <option value="">Выберите формат</option>

                @foreach (['2D', '3D', 'IMAX', 'VIP'] as $format)
                    <option value="{{ $format }}"
                        {{ old('format') == $format ? 'selected' : '' }}>
                        {{ $format }}
                    </option>
                @endforeach
            </select>

            <br><br>

            <button type="submit">Добавить сеанс</button>
        </form>

    @else

        {{-- Добавление фильма, зала и первого сеанса --}}
        @if (!request()->routeIs('admin.screenings'))

            <h2>Добавление фильма, зала и первого сеанса</h2>

            <form action="{{ route('admin.movies.store') }}" method="POST">
                @csrf

                <h3>Создание зала</h3>

                <label>Название зала</label>
                <input
                    type="text"
                    name="name_hall"
                    value="{{ old('name_hall') }}"
                    required
                >

                <br><br>

                <label>Общее количество мест</label>
                <input
                    type="number"
                    name="total_seats"
                    min="1"
                    value="{{ old('total_seats') }}"
                    required
                >

                <br><br>

                <label>Количество мест в одном ряду</label>
                <input
                    type="number"
                    name="seats_per_row"
                    min="1"
                    value="{{ old('seats_per_row') }}"
                    required
                >

                <h3>Жанр фильма</h3>

                <label>Название жанра</label>
                <input
                    type="text"
                    name="genre_name"
                    value="{{ old('genre_name') }}"
                    required
                >

                <h3>Информация о фильме</h3>

                <label>Название фильма</label>
                <input
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    required
                >

                <br><br>

                <label>Описание фильма</label>
                <textarea name="description" required>{{ old('description') }}</textarea>

                <br><br>

                <label>Длительность (в минутах)</label>
                <input
                    type="number"
                    name="duration"
                    min="1"
                    value="{{ old('duration') }}"
                    required
                >

                <br><br>

                <label>Год выпуска</label>
                <input
                    type="number"
                    name="year"
                    min="1888"
                    max="2100"
                    value="{{ old('year') }}"
                    required
                >

                <br><br>

                <label>Возрастной рейтинг</label>
                <select name="age_rating" required>
                    <option value="">Выберите рейтинг</option>

                    @foreach (['0+', '6+', '12+', '16+', '18+'] as $rating)
                        <option value="{{ $rating }}"
                            {{ old('age_rating') == $rating ? 'selected' : '' }}>
                            {{ $rating }}
                        </option>
                    @endforeach
                </select>

                <br><br>

                <label>Ссылка на трейлер</label>
                <input
                    type="url"
                    name="trailer_url"
                    value="{{ old('trailer_url') }}"
                >

                <h3>Первый сеанс</h3>

                <label>Дата и время сеанса</label>
                <input
                    type="datetime-local"
                    name="start_time"
                    value="{{ old('start_time') }}"
                    required
                >

                <br><br>

                <label>Цена билета</label>
                <input
                    type="number"
                    name="price"
                    min="0"
                    step="0.01"
                    value="{{ old('price') }}"
                    required
                >

                <br><br>

                <label>Формат показа</label>
                <select name="format" required>
                    <option value="">Выберите формат</option>

                    @foreach (['2D', '3D', 'IMAX', 'VIP'] as $format)
                        <option value="{{ $format }}"
                            {{ old('format') == $format ? 'selected' : '' }}>
                            {{ $format }}
                        </option>
                    @endforeach
                </select>

                <br><br>

                <button type="submit">Добавить фильм и сеанс</button>
            </form>

        @endif

        {{-- Список фильмов --}}
        @if (isset($movies) && request()->routeIs('admin.movies'))

            <h2>Список фильмов</h2>

            @forelse ($movies as $movie)

                <div>
                    <h3>{{ $movie->title }}</h3>

                    <p>Описание: {{ $movie->description }}</p>
                    <p>Длительность: {{ $movie->duration }} мин.</p>
                    <p>Год выпуска: {{ $movie->year }}</p>
                    <p>Возрастной рейтинг: {{ $movie->age_rating }}</p>

                    <p>Жанры:
                        @forelse ($movie->genres as $genre)
                            {{ $genre->name }}@if (!$loop->last), @endif
                        @empty
                            Не указаны
                        @endforelse
                    </p>

                    <form
                        action="{{ route('admin.movies.destroy', $movie->id) }}"
                        method="POST"
                        onsubmit="return confirm('Вы действительно хотите удалить этот фильм?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit">Удалить фильм</button>
                    </form>
                </div>

                <hr>

            @empty
                <p>Фильмов пока нет.</p>
            @endforelse

        @endif

        {{-- Список сеансов --}}
        @if (isset($screenings))

            <h2>Список сеансов</h2>

            @forelse ($screenings as $screening)

                <div>
                    <h3>{{ $screening->movie->title }}</h3>

                    <p>Зал: {{ $screening->hall->name_hall }}</p>

                    <p>
                        Дата и время:
                        {{ $screening->start_time->format('d.m.Y H:i') }}
                    </p>

                    <p>Цена: {{ $screening->price }}</p>
                    <p>Формат: {{ $screening->format }}</p>

                    <form
                        action="{{ route('admin.screenings.destroy', $screening->id) }}"
                        method="POST"
                        onsubmit="return confirm('Вы действительно хотите удалить этот сеанс?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit">Удалить сеанс</button>
                    </form>
                </div>

                <hr>

            @empty
                <p>Сеансов пока нет.</p>
            @endforelse

        @endif

    @endif

@endsection
