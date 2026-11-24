<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Электронный билет — Absolute Cinema</title>
</head>

<body style="
    margin: 0;
    padding: 0;
    background-color: #101010;
    font-family: Arial, Helvetica, sans-serif;
    color: #f1f1f1;
">

<table
    role="presentation"
    width="100%"
    cellpadding="0"
    cellspacing="0"
    style="background-color: #101010; padding: 30px 10px;"
>
    <tr>
        <td align="center">

            <table
                role="presentation"
                width="600"
                cellpadding="0"
                cellspacing="0"
                style="
                    max-width: 600px;
                    width: 100%;
                    background-color: #191919;
                    border: 1px solid #333333;
                    border-radius: 12px;
                    overflow: hidden;
                "
            >

                {{-- Шапка --}}
                <tr>
                    <td
                        align="center"
                        style="
                            padding: 30px 20px;
                            background-color: #222222;
                            border-bottom: 1px solid #383838;
                        "
                    >
                        <h1 style="
                            margin: 0;
                            font-size: 28px;
                            letter-spacing: 3px;
                            color: #ffffff;
                            font-weight: 700;
                        ">
                            ABSOLUTE CINEMA
                        </h1>

                        <p style="
                            margin: 10px 0 0;
                            color: #aaaaaa;
                            font-size: 13px;
                            letter-spacing: 1px;
                        ">
                            ВАШ ЭЛЕКТРОННЫЙ БИЛЕТ
                        </p>
                    </td>
                </tr>

                {{-- Основная информация --}}
                <tr>
                    <td style="padding: 30px 25px;">

                        <h2 style="
                            margin: 0 0 25px;
                            font-size: 24px;
                            color: #ffffff;
                            text-align: center;
                        ">
                            {{ $ticket->screening->movie->title }}
                        </h2>

                        <table
                            role="presentation"
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            style="
                                border-collapse: collapse;
                                font-size: 15px;
                            "
                        >
                            <tr>
                                <td style="
                                    padding: 12px 0;
                                    color: #999999;
                                    border-bottom: 1px solid #333333;
                                ">
                                    Дата и время
                                </td>

                                <td align="right" style="
                                    padding: 12px 0;
                                    color: #ffffff;
                                    border-bottom: 1px solid #333333;
                                    font-weight: bold;
                                ">
                                    {{ $ticket->screening->start_time->format('d.m.Y H:i') }}
                                </td>
                            </tr>

                            <tr>
                                <td style="
                                    padding: 12px 0;
                                    color: #999999;
                                    border-bottom: 1px solid #333333;
                                ">
                                    Зал
                                </td>

                                <td align="right" style="
                                    padding: 12px 0;
                                    color: #ffffff;
                                    border-bottom: 1px solid #333333;
                                ">
                                    {{ $ticket->screening->hall->name_hall }}
                                </td>
                            </tr>

                            <tr>
                                <td style="
                                    padding: 12px 0;
                                    color: #999999;
                                    border-bottom: 1px solid #333333;
                                ">
                                    Ряд
                                </td>

                                <td align="right" style="
                                    padding: 12px 0;
                                    color: #ffffff;
                                    border-bottom: 1px solid #333333;
                                ">
                                    {{ $ticket->row }}
                                </td>
                            </tr>

                            <tr>
                                <td style="
                                    padding: 12px 0;
                                    color: #999999;
                                    border-bottom: 1px solid #333333;
                                ">
                                    Место
                                </td>

                                <td align="right" style="
                                    padding: 12px 0;
                                    color: #ffffff;
                                    border-bottom: 1px solid #333333;
                                ">
                                    {{ $ticket->seat }}
                                </td>
                            </tr>

                            <tr>
                                <td style="
                                    padding: 15px 0 0;
                                    color: #999999;
                                ">
                                    Стоимость
                                </td>

                                <td align="right" style="
                                    padding: 15px 0 0;
                                    color: #ffffff;
                                    font-size: 20px;
                                    font-weight: bold;
                                ">
                                    {{ $ticket->screening->price }} ₽
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>

                {{-- QR-код --}}
                <tr>
                    <td
                        align="center"
                        style="
                            padding: 25px;
                            background-color: #222222;
                            border-top: 1px solid #333333;
                            border-bottom: 1px solid #333333;
                        "
                    >
                        <h3 style="
                            margin: 0 0 15px;
                            color: #ffffff;
                            font-size: 18px;
                        ">
                            QR-код билета
                        </h3>

                        <p style="
                            margin: 0 0 20px;
                            color: #aaaaaa;
                            font-size: 14px;
                            line-height: 1.5;
                        ">
                            Предъявите этот QR-код при входе в кинотеатр.
                        </p>

                        <img
                            src="{{ $message->embedData((string) $qrCode, 'ticket-qrcode.png', 'image/png') }}"
                            alt="QR-код билета"
                            width="220"
                            height="220"
                            style="
                                display: block;
                                width: 220px;
                                height: 220px;
                                max-width: 100%;
                                background-color: #ffffff;
                                padding: 10px;
                                border-radius: 8px;
                            "
                        >

                        <p style="
                            margin: 20px 0 5px;
                            color: #999999;
                            font-size: 12px;
                        ">
                            Уникальный код билета
                        </p>

                        <p style="
                            margin: 0;
                            color: #ffffff;
                            font-size: 13px;
                            word-break: break-all;
                        ">
                            {{ $ticket->unque_code }}
                        </p>
                    </td>
                </tr>

                {{-- Подвал --}}
                <tr>
                    <td
                        align="center"
                        style="
                            padding: 25px 20px;
                            background-color: #191919;
                        "
                    >
                        <p style="
                            margin: 0 0 10px;
                            color: #aaaaaa;
                            font-size: 13px;
                            line-height: 1.5;
                        ">
                            Спасибо, что выбрали Absolute Cinema.
                        </p>

                        <p style="
                            margin: 0;
                            color: #666666;
                            font-size: 12px;
                        ">
                            © {{ date('Y') }} Absolute Cinema
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>