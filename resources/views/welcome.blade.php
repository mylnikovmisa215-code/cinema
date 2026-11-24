
@extends('layout.index')

@section('main')

    <h1>Афиша кинотеатра</h1>

    <p>Выберите фильм и ознакомьтесь с его описанием.</p>

    <hr>

    @forelse ($movies as $movie)

        <div>
            <h2>{{ $movie->title }}</h2>

            {{-- Постер фильма --}}
            @if ($movie->images->isNotEmpty())
                <img
                    src="{{ asset('storage/' . $movie->images->first()->image_path) }}"
                    alt="Постер фильма {{ $movie->title }}"
                    width="200"
                >
            @else
                <p>Постер отсутствует</p>
            @endif

            <p>
                <strong>Год выпуска:</strong>
                {{ $movie->year }}
            </p>

            <p>
                <strong>Длительность:</strong>
                {{ $movie->duration }} мин.
            </p>

            <p>
                <strong>Возрастной рейтинг:</strong>
                {{ $movie->age_rating }}
            </p>

            <p>
                <strong>Жанры:</strong>

                @forelse ($movie->genres as $genre)
                    {{ $genre->name }}@if (!$loop->last), @endif
                @empty
                    Не указаны
                @endforelse
            </p>

            <p>
                <strong>Описание:</strong>
                {{ $movie->description }}
            </p>

            {{-- Расписание сеансов --}}
            <h3>Расписание сеансов</h3>

            @forelse ($movie->screenings as $screening)

                <div>
                    <p>
                        <strong>Дата и время:</strong>
                        {{ $screening->start_time->format('d.m.Y H:i') }}
                    </p>

                    <p>
                        <strong>Зал:</strong>
                        {{ $screening->hall->name ?? 'Не указан' }}
                    </p>

                    <p>
                        <strong>Формат:</strong>
                        {{ $screening->format }}
                    </p>

                    <p>
                        <strong>Цена билета:</strong>
                        {{ $screening->price }} руб.
                    </p>

                    <a href="{{ route('tickets.seats', $screening->id) }}">
                        Выбрать места
                    </a>
                </div>

                <hr>

            @empty
                <p>На данный момент будущих сеансов нет.</p>
            @endforelse

        </div>

        <hr>

    @empty
        <p>Пока фильмов нет.</p>
    @endforelse

@endsection
