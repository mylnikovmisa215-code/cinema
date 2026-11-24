@extends('layout.index')

@section('main')

    <style>
        .cinema-screen {
            max-width: 600px;
            margin: 0 auto;
            padding: 13px;
            text-align: center;
            color: #d6d0c4;
            background: linear-gradient(180deg, #555 0%, #252525 100%);
            border-top: 3px solid #8f2929;
            letter-spacing: 5px;
            font-size: 13px;
            box-shadow: 0 12px 35px rgba(143, 41, 41, 0.12);
        }

        .seat-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            min-width: 34px;
            padding: 0;
            border: 1px solid #52775e;
            border-radius: 5px;
            background: #315b43;
            color: white;
            font-size: 11px;
            transition: 0.15s;
        }

        .seat-button:not(:disabled):hover {
            transform: scale(1.12);
            background: #477d5c;
        }

        .seat-button.selected {
            background: #d6d0c4;
            border-color: #d6d0c4;
            color: #111;
        }

        .seat-button:disabled {
            background: #702020;
            border-color: #8f2929;
            color: #d6d0c4;
            opacity: 0.65;
            cursor: not-allowed;
        }

        .seat-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            margin-bottom: 12px;
            min-width: max-content;
        }

        .row-label {
            min-width: 55px;
            color: #858078;
            font-size: 12px;
            text-align: right;
        }

        .seat-legend {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #b5afa4;
            font-size: 12px;
        }

        .legend-square {
            width: 13px;
            height: 13px;
            border-radius: 3px;
        }

        .booking-summary {
            background: #1d1d1d;
            border: 1px solid #353535;
            border-left: 3px solid #8f2929;
        }

        @media (max-width: 576px) {
            .seat-button {
                width: 29px;
                height: 29px;
                min-width: 29px;
                font-size: 10px;
            }

            .seat-row {
                gap: 5px;
            }

            .row-label {
                min-width: 45px;
            }
        }
    </style>

    {{-- Заголовок --}}
    <div class="mb-5">
        

        <h1 class="display-5 fw-bold">
            Выбор мест<span style="color: #8f2929;">.</span>
        </h1>

        <p class="text-muted">
            {{ $screening->movie->title }}
        </p>

        <div style="width: 60px; height: 3px; background: #8f2929;"></div>
    </div>

    {{-- Информация о сеансе --}}
    <div class="card p-4 mb-5">
        <div class="row g-3">

            <div class="col-6 col-md-3">
                <p class="text-muted small mb-1">ФИЛЬМ</p>
                <strong>{{ $screening->movie->title }}</strong>
            </div>

            <div class="col-6 col-md-3">
                <p class="text-muted small mb-1">ДАТА И ВРЕМЯ</p>
                <strong>{{ $screening->start_time->format('d.m.Y H:i') }}</strong>
            </div>

            <div class="col-6 col-md-3">
                <p class="text-muted small mb-1">ЗАЛ</p>
                <strong>{{ $hall->name_hall }}</strong>
            </div>

            <div class="col-6 col-md-3">
                <p class="text-muted small mb-1">ЦЕНА БИЛЕТА</p>
                <strong>
                    <span id="ticket-price">{{ $screening->price }}</span> руб.
                </strong>
            </div>

        </div>
    </div>

    {{-- Схема зала --}}
    <div class="mb-5">

        <h4 class="text-uppercase text-center mb-4"
            style="letter-spacing: 2px;">
            Схема кинозала
        </h4>

        <div class="cinema-screen mb-5">
        </div>

        {{-- Обозначения --}}
        <div class="d-flex justify-content-center flex-wrap gap-4 mb-4">

            <span class="seat-legend">
                <span class="legend-square" style="background: #315b43;"></span>
                Свободно
            </span>

            <span class="seat-legend">
                <span class="legend-square" style="background: #702020;"></span>
                Занято
            </span>

            <span class="seat-legend">
                <span class="legend-square" style="background: #d6d0c4;"></span>
                Выбрано
            </span>

        </div>

        {{-- Места --}}
        <div class="card p-3 p-md-4">
            <div class="overflow-auto py-3">

                @for ($row = 1; $row <= $hall->rows_count; $row++)

                    <div class="seat-row">

                        <span class="row-label">
                            РЯД {{ $row }}
                        </span>

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
                                title="Ряд {{ $row }}, место {{ $seat }}"
                            >
                                {{ $seat }}
                            </button>

                        @endfor

                    </div>

                @endfor

            </div>
        </div>

    </div>

    {{-- Форма бронирования --}}
    <form
        action="{{ route('tickets.store', $screening->id) }}"
        method="POST"
        id="booking-form"
    >
        @csrf

        <div class="booking-summary rounded p-4">

            <h4 class="text-uppercase mb-4"
                style="letter-spacing: 1px;">
                Ваш выбор
            </h4>

            <div class="mb-3">
                <p class="text-muted small mb-1">ВЫБРАННЫЕ МЕСТА</p>
                <strong id="selected-seats">Не выбраны</strong>
            </div>

            <div class="row g-3 mb-4">

                <div class="col-6">
                    <p class="text-muted small mb-1">КОЛИЧЕСТВО БИЛЕТОВ</p>
                    <h4 id="selected-count">0</h4>
                </div>

                <div class="col-6">
                    <p class="text-muted small mb-1">ОБЩАЯ СТОИМОСТЬ</p>
                    <h4>
                        <span id="total-price">0</span>
                        <span class="text-muted fs-6">руб.</span>
                    </h4>
                </div>

            </div>

            <div id="booking-inputs"></div>

            <p id="selection-error" class="text-danger small"></p>

            @error('seats')
                <p class="text-danger small">{{ $message }}</p>
            @enderror

            <button
                type="submit"
                id="booking-button"
                class="btn btn-primary px-4"
                disabled
            >
                Забронировать места
            </button>

        </div>

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
                        this.classList.add('selected');

                    } else {

                        selectedSeats.splice(index, 1);
                        this.classList.remove('selected');

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