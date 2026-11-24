
@extends('layout.index')

@section('main')

    <h1>Выбор мест</h1>

    <h2>{{ $screening->movie->title }}</h2>

    <p>
        <strong>Дата и время:</strong>
        {{ $screening->start_time->format('d.m.Y H:i') }}
    </p>

    <p>
        <strong>Зал:</strong>
        {{ $hall->name_hall }}
    </p>

    <p>
        <strong>Цена одного билета:</strong>
        <span id="ticket-price">{{ $screening->price }}</span> руб.
    </p>

    <hr>

    <h3>Схема зала</h3>

    <div style="text-align: center; margin-bottom: 25px;">
        <div style="
            background: #555;
            color: white;
            padding: 10px;
            border-radius: 5px;
            max-width: 500px;
            margin: auto;
        ">
            ЭКРАН
        </div>
    </div>

    {{-- Обозначения --}}
    <div style="
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    ">
        <span style="color: #198754;">● Свободно</span>
        <span style="color: #dc3545;">● Занято</span>
        <span style="color: #0d6efd;">● Выбрано</span>
    </div>

    {{-- Схема зала --}}
    <div style="overflow-x: auto;">

        @for ($row = 1; $row <= $hall->rows_count; $row++)

            <div style="
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 12px;
                justify-content: center;
            ">

                <strong style="min-width: 55px;">
                    Ряд {{ $row }}
                </strong>

                @for ($seat = 1; $seat <= $hall->seats_count; $seat++)

                    @php
                        $isOccupied = in_array(
                            $row . '-' . $seat,
                            $occupiedSeats
                        );
                    @endphp

                    <button
                        type="button"
                        class="seat-button"
                        data-row="{{ $row }}"
                        data-seat="{{ $seat }}"
                        {{ $isOccupied ? 'disabled' : '' }}
                        style="
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            width: 32px;
                            height: 32px;
                            min-width: 32px;
                            border: none;
                            border-radius: 5px;
                            background: {{ $isOccupied ? '#dc3545' : '#198754' }};
                            color: white;
                            font-size: 12px;
                            cursor: {{ $isOccupied ? 'not-allowed' : 'pointer' }};
                            opacity: {{ $isOccupied ? '0.7' : '1' }};
                        "
                    >
                        {{ $seat }}
                    </button>

                @endfor

            </div>

        @endfor

    </div>

    <hr>

    {{-- Форма бронирования --}}
    <form
        action="{{ route('tickets.store', $screening->id) }}"
        method="POST"
        id="booking-form"
    >
        @csrf

        <h3>Ваш выбор</h3>

        <p>
            <strong>Выбранные места:</strong>
            <span id="selected-seats">Не выбраны</span>
        </p>

        <p>
            <strong>Количество билетов:</strong>
            <span id="selected-count">0</span>
        </p>

        <p>
            <strong>Общая стоимость:</strong>
            <span id="total-price">0</span> руб.
        </p>

        <div id="booking-inputs"></div>

        <p id="selection-error" style="color: red;"></p>

        @error('seats')
            <p style="color: red;">{{ $message }}</p>
        @enderror

        <button
            type="submit"
            id="booking-button"
            disabled
            style="
                padding: 10px 20px;
                background: #0d6efd;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                opacity: 0.5;
            "
        >
            Забронировать места
        </button>

    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const seats = document.querySelectorAll('.seat-button');

            const selectedSeatsElement =
                document.getElementById('selected-seats');

            const selectedCountElement =
                document.getElementById('selected-count');

            const totalPriceElement =
                document.getElementById('total-price');

            const bookingInputs =
                document.getElementById('booking-inputs');

            const bookingButton =
                document.getElementById('booking-button');

            const selectionError =
                document.getElementById('selection-error');

            const bookingForm =
                document.getElementById('booking-form');

            const ticketPrice = Number(
                document.getElementById('ticket-price').textContent
            );

            const selectedSeats = [];

            seats.forEach(function (seat) {

                seat.addEventListener('click', function () {

                    const row = this.dataset.row;
                    const number = this.dataset.seat;

                    const seatKey = row + '-' + number;

                    const index = selectedSeats.indexOf(seatKey);

                    if (index === -1) {

                        selectedSeats.push(seatKey);

                        this.style.background = '#0d6efd';

                    } else {

                        selectedSeats.splice(index, 1);

                        this.style.background = '#198754';

                    }

                    updateSelection();

                });

            });

            function updateSelection() {

                selectedSeatsElement.textContent =
                    selectedSeats.length > 0
                        ? selectedSeats.join(', ')
                        : 'Не выбраны';

                selectedCountElement.textContent =
                    selectedSeats.length;

                totalPriceElement.textContent =
                    selectedSeats.length * ticketPrice;

                bookingInputs.innerHTML = '';

                selectedSeats.forEach(function (seatKey, index) {

                    const parts = seatKey.split('-');

                    const row = parts[0];
                    const seat = parts[1];

                    bookingInputs.innerHTML += `
                        <input
                            type="hidden"
                            name="seats[${index}][row]"
                            value="${row}"
                        >

                        <input
                            type="hidden"
                            name="seats[${index}][seat]"
                            value="${seat}"
                        >
                    `;

                });

                bookingButton.disabled = selectedSeats.length === 0;

                bookingButton.style.opacity =
                    selectedSeats.length > 0 ? '1' : '0.5';

                selectionError.textContent = '';

            }

            bookingForm.addEventListener('submit', function (event) {

                if (selectedSeats.length === 0) {

                    event.preventDefault();

                    selectionError.textContent =
                        'Сначала выберите хотя бы одно место.';

                }

            });

        });
    </script>

@endsection
