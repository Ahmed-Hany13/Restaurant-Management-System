<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('table')->latest()->get();
        $tables = Table::all();

        $statusOptions = [
            'all' => 'All',
            'confirmed' => 'Confirmed',
            'arrived' => 'Arrived',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'no-show' => 'No-Show',
        ];

        $typeOptions = [
            'all' => 'All',
            'now' => 'Walk-in / Immediate',
            'scheduled' => 'Scheduled',
        ];

        return view('admin.reservations.view-reservations', compact(
            'reservations',
            'statusOptions',
            'typeOptions',
            'tables'
        ));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'reservation_date' => ['nullable', 'date'],
            'reservation_time' => ['nullable', 'string'],
            'table_id' => ['required', 'exists:tables,id'],
        ]);

        $table = Table::findOrFail($validated['table_id']);

        if ($reservation->table_id !== $table->id && $table->status !== 'available') {
            return redirect()->back()->withErrors(['table_id' => 'Selected table is not available.']);
        }

        $previousTable = $reservation->table;

        $reservation->update([
            'reservation_date' => $validated['reservation_date'] ?? null,
            'reservation_time' => $validated['reservation_time'] ?? null,
            'table_id' => $table->id,
            'table_type' => $table->type,
        ]);

        if ($previousTable instanceof Table && $previousTable->id !== $table->id && $previousTable->status === 'reserved') {
            $previousTable->update(['status' => 'available']);
        }

        if ($table->status === 'available') {
            $table->update(['status' => 'reserved']);
        }

        return redirect()->back()->with('success', 'Reservation updated successfully.');
    }

    public function arrive(Reservation $reservation)
    {
        if ($reservation->status === 'arrived') {
            return redirect()->back()->with('error', 'Reservation already marked as arrived.');
        }

        $reservation->update(['status' => 'arrived']);

        $reservation->table?->update(['status' => 'occupied']);

        return redirect()->back()->with('success', 'Reservation marked as arrived.');
    }

    public function cancel(Reservation $reservation)
    {
        if ($reservation->status === 'cancelled') {
            return redirect()->back()->with('error', 'Reservation already cancelled.');
        }

        $reservation->update(['status' => 'cancelled']);

        if ($reservation->table instanceof Table && $reservation->table->status === 'reserved') {
            $reservation->table->update(['status' => 'available']);
        }

        return redirect()->back()->with('success', 'Reservation cancelled.');
    }

    public function markNoShow(Reservation $reservation)
    {
        if ($reservation->status === 'no-show') {
            return redirect()->back()->with('error', 'Reservation already marked as no-show.');
        }

        $reservation->update(['status' => 'no-show']);

        if ($reservation->table instanceof Table && $reservation->table->status === 'reserved') {
            $reservation->table->update(['status' => 'available']);
        }

        return redirect()->back()->with('success', 'Reservation marked as no-show.');
    }

    public function show(Reservation $reservation)
    {
        return view('admin.reservations.show-reservation', compact('reservation'));
    }
}
