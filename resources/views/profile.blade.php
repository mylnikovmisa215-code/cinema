@extends('layout.index')

@section('main')

    {{-- Заголовок --}}
    <div class="mb-5">
        <p class="text-uppercase mb-2"
           style="color: #8f2929; letter-spacing: 3px; font-size: 12px;">
            Personal space
        </p>

        <h1 class="display-5 fw-bold">
            Аккаунт<span style="color: #8f2929;">.</span>
        </h1>

        <p class="text-muted">
            Твои бронирования и билеты
        </p>

        <div style="width: 60px; height: 3px; background: #8f2929;"></div>
    </div>

    <h4 class="text-uppercase mb-4" style="letter-spacing: 1px;">
        Мои билеты
    </h4>

    @if ($tickets->isEmpty())

        <div class="card p-5 text-center">
            <h5>Билетов пока нет</h5>

            <p class="text-muted mb-4">
                Ты ещё не забронировал ни одного места.
            </p>

            <div>
                <a href="{{ route('catalog') }}" class="btn btn-primary px-4">
                    Перейти к афише
                </a>
            </div>
        </div>

    @else

        <div class="row g-4">

            @foreach ($tickets as $ticket)

                <div class="col-12 col-lg-6">

                    <div class="card h-100 overflow-hidden">

                        {{-- Верхняя часть билета --}}
                        <div class="p-4"
                             style="border-bottom: 1px dashed #555;">

                            <div class="d-flex justify-content-between align-items-start gap-3">

                                <div>
                                    <p class="text-uppercase mb-2"
                                       style="color: #8f2929; font-size: 11px; letter-spacing: 2px;">
                                        Underground cinema / Ticket
                                    </p>

                                    <h4 class="fw-bold mb-0">
                                        {{ $ticket->screening->movie->title }}
                                    </h4>
                                </div>

                                @if ($ticket->is_paid)
                                    <span class="badge rounded-0 px-3 py-2"
                                          style="background: #315b43; color: white;">
                                        ОПЛАЧЕН
                                    </span>
                                @else
                                    <span class="badge rounded-0 px-3 py-2"
                                          style="background: #8f2929; color: white;">
                                        НЕ ОПЛАЧЕН
                                    </span>
                                @endif

                            </div>

                        </div>

                        {{-- Информация о билете --}}
                        <div class="p-4">

                            <div class="row g-3">

                                <div class="col-6">
                                    <p class="text-muted small mb-1">
                                        ДАТА И ВРЕМЯ
                                    </p>

                                    <div class="fw-semibold">
                                        {{ $ticket->screening->start_time->format('d.m.Y H:i') }}
                                    </div>
                                </div>

                                <div class="col-6">
                                    <p class="text-muted small mb-1">
                                        ЗАЛ
                                    </p>

                                    <div class="fw-semibold">
                                        {{ $ticket->screening->hall->name_hall }}
                                    </div>
                                </div>

                                <div class="col-6">
                                    <p class="text-muted small mb-1">
                                        РЯД
                                    </p>

                                    <div class="fw-semibold">
                                        {{ $ticket->row }}
                                    </div>
                                </div>

                                <div class="col-6">
                                    <p class="text-muted small mb-1">
                                        МЕСТО
                                    </p>

                                    <div class="fw-semibold">
                                        {{ $ticket->seat }}
                                    </div>
                                </div>

                            </div>

                            <hr style="border-color: #353535;">

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    Стоимость билета
                                </span>

                                <h4 class="fw-bold mb-0">
                                    {{ $ticket->screening->price }} 
                                    <span class="text-muted fs-6">руб.</span>
                                </h4>
                            </div>

                            {{-- Действия --}}
                            @if (!$ticket->is_paid)

                                <div class="d-flex flex-wrap gap-2 mt-4">

                                    <form
                                        action="{{ route('tickets.pay', $ticket->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Вы действительно хотите оплатить этот билет?');"
                                    >
                                        @csrf

                                        <button type="submit"
                                                class="btn btn-primary btn-sm px-3">
                                            Оплатить билет
                                        </button>
                                    </form>

                                    <form
                                        action="{{ route('tickets.destroy', $ticket->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Вы действительно хотите отменить бронирование?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-outline-light btn-sm px-3">
                                            Отменить
                                        </button>
                                    </form>

                                </div>

                            @else

                                <div class="mt-4 small text-muted">
                                    Билет успешно оплачен.
                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @endif

    <div class="mt-5">
        <a href="{{ route('catalog') }}"
           class="btn btn-outline-light btn-sm px-4">
            ← Вернуться в каталог
        </a>
    </div>

@endsection