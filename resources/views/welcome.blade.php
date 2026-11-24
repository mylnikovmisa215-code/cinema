@extends('layout.index')

@section('main')

    <div class="mb-5">
        <p class="text-uppercase mb-2" style="color: #8f2929; letter-spacing: 3px; font-size: 12px;">
            Independent movie space
        </p>

        <h1 class="display-5 fw-bold mb-3">
            Сейчас актуально<span style="color: #8f2929;">.</span>
        </h1>

        <p class="text-muted">
            Выбирай фильм. Находи свой сеанс. Смотри кино. Клипай эдиты
        </p>

        <div style="width: 60px; height: 3px; background: #8f2929;"></div>
    </div>

    @forelse ($movies as $movie)

        <div class="card mb-4 overflow-hidden">
            <div class="row g-0">

                {{-- Постер --}}
                <div class="col-md-4 col-lg-3">
                    @if ($movie->images->isNotEmpty())
                        <img
                            src="{{ asset('storage/' . $movie->images->first()->image_path) }}"
                            alt="Постер фильма {{ $movie->title }}"
                            class="w-100 h-100"
                            style="object-fit: cover; min-height: 350px; max-height: 480px;"
                        >
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100"
                             style="min-height: 350px; background: #252525;">
                            <span class="text-muted">ПОСТЕР ОТСУТСТВУЕТ</span>
                        </div>
                    @endif
                </div>

                {{-- Информация о фильме --}}
                <div class="col-md-8 col-lg-9">
                    <div class="card-body p-4 p-lg-5">

                        <p class="text-uppercase mb-2"
                           style="color: #8f2929; font-size: 11px; letter-spacing: 2px;">
                            Movie / {{ $movie->year }}
                        </p>

                        <h2 class="fw-bold mb-3">
                            {{ $movie->title }}
                        </h2>

                        <div class="d-flex flex-wrap gap-2 mb-4">

                            <span class="badge rounded-0"
                                  style="background: #333; color: #d6d0c4;">
                                {{ $movie->year }}
                            </span>

                            <span class="badge rounded-0"
                                  style="background: #333; color: #d6d0c4;">
                                {{ $movie->duration }} мин.
                            </span>

                            <span class="badge rounded-0"
                                  style="background: #8f2929; color: white;">
                                {{ $movie->age_rating }}
                            </span>

                        </div>

                        <div class="mb-3">
                            <span class="text-muted small text-uppercase">
                                Жанры
                            </span>

                            <div class="mt-2 d-flex flex-wrap gap-2">
                                @forelse ($movie->genres as $genre)
                                    <span class="badge rounded-0"
                                          style="background: #252525; color: #d6d0c4; border: 1px solid #353535;">
                                        {{ $genre->name }}
                                    </span>
                                @empty
                                    <span class="text-muted">Не указаны</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="mb-4">
                            <span class="text-muted small text-uppercase">
                                О фильме
                            </span>

                            <p class="mt-2 mb-0" style="line-height: 1.7;">
                                {{ $movie->description }}
                            </p>
                        </div>

                        <hr style="border-color: #353535;">

                        {{-- Расписание --}}
                        <div class="mt-4">

                            <h5 class="text-uppercase mb-3"
                                style="letter-spacing: 1px;">
                                Сеансы
                            </h5>

                            @forelse ($movie->screenings as $screening)

                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 p-3 mb-2"
                                     style="background: #252525; border-left: 3px solid #8f2929;">

                                    <div>
                                        <div class="fw-bold mb-1">
                                            {{ $screening->start_time->format('d.m.Y H:i') }}
                                        </div>

                                        <div class="text-muted small">
                                            Зал: {{ $screening->hall->name_hall ?? 'Не указан' }}
                                            <span class="mx-2">|</span>
                                            {{ $screening->format }}
                                        </div>

                                        <div class="mt-2 small">
                                            {{ $screening->price }} руб.
                                        </div>
                                    </div>

                                    <a href="{{ route('tickets.seats', $screening->id) }}"
                                       class="btn btn-primary btn-sm px-3">
                                        Выбрать места
                                    </a>

                                </div>

                            @empty

                                <p class="text-muted mb-0">
                                    На данный момент будущих сеансов нет.
                                </p>

                            @endforelse

                        </div>

                    </div>
                </div>

            </div>
        </div>

    @empty

        <div class="card p-5 text-center">
            <h4>Афиша пока пуста</h4>
            <p class="text-muted mb-0">
                Скоро здесь появятся новые фильмы.
            </p>
        </div>

    @endforelse

@endsection