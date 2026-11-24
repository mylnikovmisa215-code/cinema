
@extends('layout.index')

@section('main')

    <div class="mb-4">
        <h1 class="fw-bold mb-3">Кабинет Администратора</h1>

        <nav class="d-flex flex-wrap gap-2">
            <a href="{{ route('show.admin') }}" class="btn btn-outline-secondary btn-sm">
                Главная
            </a>

            <a href="{{ route('admin.movies') }}" class="btn btn-outline-secondary btn-sm">
                Фильмы
            </a>

            <a href="{{ route('admin.screenings') }}" class="btn btn-outline-secondary btn-sm">
                Сеансы
            </a>

            <a href="{{ route('admin.screenings.create') }}" class="btn btn-outline-secondary btn-sm">
                Добавить сеанс
            </a>

            <a href="{{ route('admin.tickets') }}" class="btn btn-outline-secondary btn-sm">
                Билеты
            </a>
        </nav>
    </div>

    {{-- Сообщения об успешных действиях --}}
    @if (session('success'))
        <div class="alert alert-success bg-dark text-light border-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Сообщения об ошибках --}}
    @if (session('error'))
        <div class="alert alert-danger bg-dark text-light border-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger bg-dark text-light border-danger">
            <strong>Ошибки:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Форма добавления дополнительного сеанса --}}
    @if (request()->routeIs('admin.screenings.create'))

        <div class="card bg-dark border-secondary mb-4">
            <div class="card-body p-4">

                <h2 class="h4 mb-4">ДОБАВЛЕНИЕ ДОПОЛНИТЕЛЬНОГО СЕАНСА.</h2>

                <form action="{{ route('admin.screenings.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Выберите фильм</label>
                        <select name="movie_id" class="form-select" required>
                            <option value="">Выберите фильм</option>

                            @foreach ($movies as $movie)
                                <option value="{{ $movie->id }}"
                                    {{ old('movie_id') == $movie->id ? 'selected' : '' }}>
                                    {{ $movie->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Выберите зал</label>
                        <select name="hall_id" class="form-select" required>
                            <option value="">Выберите зал</option>

                            @foreach ($halls as $hall)
                                <option value="{{ $hall->id }}"
                                    {{ old('hall_id') == $hall->id ? 'selected' : '' }}>
                                    {{ $hall->name_hall }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Дата и время сеанса</label>
                        <input
                            type="datetime-local"
                            name="start_time"
                            class="form-control"
                            value="{{ old('start_time') }}"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Цена билета</label>
                        <input
                            type="number"
                            name="price"
                            class="form-control"
                            min="0"
                            step="0.01"
                            value="{{ old('price') }}"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Формат показа</label>
                        <select name="format" class="form-select" required>
                            <option value="">Выберите формат</option>

                            @foreach (['2D', '3D', 'IMAX', 'VIP'] as $format)
                                <option value="{{ $format }}"
                                    {{ old('format') == $format ? 'selected' : '' }}>
                                    {{ $format }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-danger">
                        Добавить сеанс
                    </button>
                </form>

            </div>
        </div>

    @else

        {{-- Добавление фильма, зала и первого сеанса --}}
        @if (!request()->routeIs('admin.screenings')
            && !request()->routeIs('admin.tickets'))

            <div class="card bg-dark border-secondary mb-4">
                <div class="card-body p-4">

                    <h2 class="h4 mb-4">ДОБАВЛЕНИЕ ФИЛЬМА, ЗАЛА И ПЕРВОГО СЕАНСА.</h2>

                    <form
                        action="{{ route('admin.movies.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        <h3 class="h5 mb-3 text-secondary">01 / СОЗДАНИЕ ЗАЛА</h3>

                        <div class="mb-3">
                            <label class="form-label">Название зала</label>
                            <input
                                type="text"
                                name="name_hall"
                                class="form-control"
                                value="{{ old('name_hall') }}"
                                required
                            >
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Общее количество мест</label>
                                <input
                                    type="number"
                                    name="total_seats"
                                    class="form-control"
                                    min="1"
                                    value="{{ old('total_seats') }}"
                                    required
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Количество мест в одном ряду</label>
                                <input
                                    type="number"
                                    name="seats_per_row"
                                    class="form-control"
                                    min="1"
                                    value="{{ old('seats_per_row') }}"
                                    required
                                >
                            </div>
                        </div>

                        <hr class="border-secondary my-4">

                        <h3 class="h5 mb-3 text-secondary">02 / ЖАНР ФИЛЬМА</h3>

                        <div class="mb-3">
                            <label class="form-label">Название жанра</label>
                            <input
                                type="text"
                                name="genre_name"
                                class="form-control"
                                value="{{ old('genre_name') }}"
                                required
                            >
                        </div>

                        <hr class="border-secondary my-4">

                        <h3 class="h5 mb-3 text-secondary">03 / ИНФОРМАЦИЯ О ФИЛЬМЕ</h3>

                        <div class="mb-3">
                            <label class="form-label">Название фильма</label>
                            <input
                                type="text"
                                name="title"
                                class="form-control"
                                value="{{ old('title') }}"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Постер фильма</label>
                            <input
                                type="file"
                                name="poster"
                                class="form-control"
                                accept="image/jpeg,image/png,image/webp"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Описание фильма</label>
                            <textarea
                                name="description"
                                class="form-control"
                                rows="4"
                                required
                            >{{ old('description') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Длительность (в минутах)</label>
                                <input
                                    type="number"
                                    name="duration"
                                    class="form-control"
                                    min="1"
                                    value="{{ old('duration') }}"
                                    required
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Год выпуска</label>
                                <input
                                    type="number"
                                    name="year"
                                    class="form-control"
                                    min="1888"
                                    max="2100"
                                    value="{{ old('year') }}"
                                    required
                                >
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Возрастной рейтинг</label>
                            <select name="age_rating" class="form-select" required>
                                <option value="">Выберите рейтинг</option>

                                @foreach (['0+', '6+', '12+', '16+', '18+'] as $rating)
                                    <option value="{{ $rating }}"
                                        {{ old('age_rating') == $rating ? 'selected' : '' }}>
                                        {{ $rating }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ссылка на трейлер</label>
                            <input
                                type="url"
                                name="trailer_url"
                                class="form-control"
                                value="{{ old('trailer_url') }}"
                            >
                        </div>

                        <hr class="border-secondary my-4">

                        <h3 class="h5 mb-3 text-secondary">04 / ПЕРВЫЙ СЕАНС</h3>

                        <div class="mb-3">
                            <label class="form-label">Дата и время сеанса</label>
                            <input
                                type="datetime-local"
                                name="start_time"
                                class="form-control"
                                value="{{ old('start_time') }}"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Цена билета</label>
                            <input
                                type="number"
                                name="price"
                                class="form-control"
                                min="0"
                                step="0.01"
                                value="{{ old('price') }}"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Формат показа</label>
                            <select name="format" class="form-select" required>
                                <option value="">Выберите формат</option>

                                @foreach (['2D', '3D', 'IMAX', 'VIP'] as $format)
                                    <option value="{{ $format }}"
                                        {{ old('format') == $format ? 'selected' : '' }}>
                                        {{ $format }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-danger">
                            Добавить фильм и сеанс
                        </button>
                    </form>

                </div>
            </div>

        @endif

        {{-- Список фильмов --}}
        @if (isset($movies) && request()->routeIs('admin.movies'))

            <div class="mb-4">
                <h2 class="h4 mb-4">СПИСОК ФИЛЬМОВ.</h2>

                <div class="row g-4">
                    @forelse ($movies as $movie)

                        <div class="col-12 col-lg-6">
                            <div class="card bg-dark border-secondary h-100">
                                <div class="card-body p-3">

                                    <div class="row g-3">
                                        <div class="col-4">
                                            @if ($movie->images->isNotEmpty())
                                                <img
                                                    src="{{ asset('storage/' . $movie->images->first()->image_path) }}"
                                                    alt="Постер фильма {{ $movie->title }}"
                                                    class="img-fluid rounded"
                                                >
                                            @else
                                                <div class="bg-secondary text-center p-3 rounded">
                                                    <small>Постер отсутствует</small>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-8">
                                            <h3 class="h5 mb-2">{{ $movie->title }}</h3>

                                            <p class="text-secondary small mb-1">
                                                {{ $movie->year }} · {{ $movie->duration }} мин.
                                            </p>

                                            <p class="small mb-2">
                                                Возрастной рейтинг: {{ $movie->age_rating }}
                                            </p>

                                            <p class="small mb-2">
                                                <span class="text-secondary">Жанры:</span>
                                                @forelse ($movie->genres as $genre)
                                                    {{ $genre->name }}@if (!$loop->last), @endif
                                                @empty
                                                    Не указаны
                                                @endforelse
                                            </p>
                                        </div>
                                    </div>

                                    <hr class="border-secondary">

                                    <p class="small mb-3">
                                        <span class="text-secondary">Описание:</span>
                                        {{ $movie->description }}
                                    </p>

                                    <form
                                        action="{{ route('admin.movies.destroy', $movie->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Вы действительно хотите удалить этот фильм?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            Удалить фильм
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="col-12">
                            <p class="text-secondary">Фильмов пока нет.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        @endif

        {{-- Список сеансов --}}
        @if (isset($screenings))

            <div class="mb-4">
                <h2 class="h4 mb-4">СПИСОК СЕАНСОВ.</h2>

                <div class="row g-3">
                    @forelse ($screenings as $screening)

                        <div class="col-12 col-md-6 col-xl-4">
                            <div class="card bg-dark border-secondary h-100">
                                <div class="card-body p-3">

                                    <h3 class="h5 mb-3">
                                        {{ $screening->movie->title }}
                                    </h3>

                                    <p class="small mb-2">
                                        <span class="text-secondary">Зал:</span>
                                        {{ $screening->hall->name_hall }}
                                    </p>

                                    <p class="small mb-2">
                                        <span class="text-secondary">Дата и время:</span>
                                        {{ $screening->start_time->format('d.m.Y H:i') }}
                                    </p>

                                    <p class="small mb-2">
                                        <span class="text-secondary">Цена:</span>
                                        {{ $screening->price }} руб.
                                    </p>

                                    <p class="small mb-3">
                                        <span class="text-secondary">Формат:</span>
                                        {{ $screening->format }}
                                    </p>

                                    <form
                                        action="{{ route('admin.screenings.destroy', $screening->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Вы действительно хотите удалить этот сеанс?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            Удалить сеанс
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="col-12">
                            <p class="text-secondary">Сеансов пока нет.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        @endif

        {{-- Список забронированных билетов --}}
        @if (isset($tickets))

            <div class="mb-4">
                <h2 class="h4 mb-4">ЗАБРОНИРОВАННЫЕ БИЛЕТЫ.</h2>

                @if ($tickets->isEmpty())

                    <p class="text-secondary">Забронированных билетов пока нет.</p>

                @else

                    <div class="table-responsive">
                        <table class="table table-dark table-hover table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>Фильм</th>
                                    <th>Дата сеанса</th>
                                    <th>Зал</th>
                                    <th>Пользователь</th>
                                    <th>Email</th>
                                    <th>Ряд</th>
                                    <th>Место</th>
                                    <th>Цена</th>
                                    <th>Оплата</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->screening->movie->title }}</td>

                                        <td>
                                            {{ $ticket->screening->start_time->format('d.m.Y H:i') }}
                                        </td>

                                        <td>{{ $ticket->screening->hall->name_hall }}</td>

                                        <td>{{ $ticket->user->login }}</td>

                                        <td>{{ $ticket->user->email }}</td>

                                        <td>{{ $ticket->row }}</td>

                                        <td>{{ $ticket->seat }}</td>

                                        <td>{{ $ticket->screening->price }} руб.</td>

                                        <td>
                                            @if ($ticket->is_paid)
                                                <span class="text-success">Оплачен</span>
                                            @else
                                                <span class="text-warning">Ожидает оплаты</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @endif
            </div>

        @endif

    @endif

@endsection
