
@extends('layout.index')

@section('main')

    <h1>Личный кабинет</h1>

    <h2>Мои билеты</h2>

    @if ($tickets->isEmpty())

        <p>У вас пока нет забронированных билетов.</p>

    @else

        @foreach ($tickets as $ticket)

            <div style="
                border: 1px solid #ccc;
                border-radius: 8px;
                padding: 15px;
                margin-bottom: 15px;
                max-width: 500px;
            ">

                <h3>{{ $ticket->screening->movie->title }}</h3>

                <p>
                    <strong>Дата и время:</strong>
                    {{ $ticket->screening->start_time->format('d.m.Y H:i') }}
                </p>

                <p>
                    <strong>Зал:</strong>
                    {{ $ticket->screening->hall->name_hall }}
                </p>

                <p>
                    <strong>Ряд:</strong> {{ $ticket->row }}
                </p>

                <p>
                    <strong>Место:</strong> {{ $ticket->seat }}
                </p>

                <p>
                    <strong>Стоимость:</strong>
                    {{ $ticket->screening->price }} руб.
                </p>

                <p>
                    <strong>Статус оплаты:</strong>

                    @if ($ticket->is_paid)
                        <span style="color: green;">Оплачен</span>
                    @else
                        <span style="color: orange;">Ожидает оплаты</span>
                    @endif
                </p>

                @if (!$ticket->is_paid)

                    <form
                        action="{{ route('tickets.pay', $ticket->id) }}"
                        method="POST"
                        onsubmit="return confirm('Вы действительно хотите оплатить этот билет?');"
                        style="margin-bottom: 10px;"
                    >
                        @csrf

                        <button
                            type="submit"
                            style="
                                background: #198754;
                                color: white;
                                border: none;
                                padding: 8px 15px;
                                border-radius: 5px;
                                cursor: pointer;
                            "
                        >
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

                        <button
                            type="submit"
                            style="
                                background: #dc3545;
                                color: white;
                                border: none;
                                padding: 8px 15px;
                                border-radius: 5px;
                                cursor: pointer;
                            "
                        >
                            Отменить бронирование
                        </button>
                    </form>

                @endif

            </div>

        @endforeach

    @endif

    <a href="{{ route('catalog') }}">Вернуться в каталог</a>

@endsection
