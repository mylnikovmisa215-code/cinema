<?php

namespace App\Http\Controllers;

use App\Models\Screening;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class TicketController extends Controller
{
    // Отображение схемы зала и доступных мест
    public function showSeats($id)
    {
        $screening = Screening::with([
            'movie',
            'hall',
            'tickets',
        ])->findOrFail($id);

        $hall = $screening->hall;

        $occupiedSeats = $screening->tickets
            ->map(function ($ticket) {
                return $ticket->row . '-' . $ticket->seat;
            })
            ->toArray();

        return view('tickets.seats', compact(
            'screening',
            'hall',
            'occupiedSeats'
        ));
    }

    // Сохранение выбранных билетов
    public function store(Request $request, $id)
    {
        $screening = Screening::with('hall')->findOrFail($id);

        $validated = $request->validate([
            'seats' => ['required', 'array', 'min:1'],
            'seats.*.row' => ['required', 'integer', 'min:1'],
            'seats.*.seat' => ['required', 'integer', 'min:1'],
        ]);

        $seats = collect($validated['seats']);

        if ($seats->unique(fn ($seat) => $seat['row'] . '-' . $seat['seat'])->count() !== $seats->count()) {
            throw ValidationException::withMessages([
                'seats' => 'Выбраны повторяющиеся места.',
            ]);
        }

        foreach ($seats as $seat) {
            if (
                $seat['row'] > $screening->hall->rows_count ||
                $seat['seat'] > $screening->hall->seats_count
            ) {
                throw ValidationException::withMessages([
                    'seats' => 'Выбрано место, которого нет в зале.',
                ]);
            }
        }

        try {
            DB::transaction(function () use ($seats, $screening) {
                foreach ($seats as $seat) {
                    Ticket::create([
                        'user_id' => Auth::id(),
                        'screen_id' => $screening->id,
                        'row' => $seat['row'],
                        'seat' => $seat['seat'],
                        'is_paid' => false,
                    ]);
                }
            });
        } catch (QueryException $exception) {
            if ($exception->getCode() === '23000') {
                throw ValidationException::withMessages([
                    'seats' => 'Одно или несколько выбранных мест уже заняты. Обновите страницу.',
                ]);
            }

            throw $exception;
        }

        return redirect()
            ->route('tickets.seats', $screening->id)
            ->with('success', 'Места успешно забронированы!');
    }

    // Отмена бронирования
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Вы не можете отменить чужой билет.');
        }

        if ($ticket->is_paid) {
            return redirect()
                ->route('show.profile')
                ->with('error', 'Нельзя отменить уже оплаченный билет.');
        }

        $ticket->delete();

        return redirect()
            ->route('show.profile')
            ->with('success', 'Бронирование успешно отменено.');
    }

    // Имитация оплаты билета
    public function pay($id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Вы не можете оплатить чужой билет.');
        }

        if ($ticket->is_paid) {
            return redirect()
                ->route('show.profile')
                ->with('error', 'Этот билет уже оплачен.');
        }

        $ticket->update([
            'is_paid' => true,
        ]);

        return redirect()
            ->route('show.profile')
            ->with('success', 'Билет успешно оплачен!');
    }
}
