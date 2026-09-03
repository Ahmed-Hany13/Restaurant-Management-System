<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\StoreReservationRequest;
use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = Table::with('orders.menuItems')->get();
        $sections = MenuSection::where('status', 'active')
            ->with(['menuCategories' => function ($query) {
                $query->where('status', 'active')->orderBy('display_order')
                    ->with(['menuSubcategories' => function ($query) {
                        $query->where('status', 'active')->orderBy('display_order');
                    }]);
            }])->orderBy('display_order')->get();

        $menuItems = MenuItem::with('menuSubcategory.menuCategory.menuSection', 'offers')
            ->where('status', 'active')
            ->get();

        $reservations = Reservation::with('table')
            ->whereIn('status', ['confirmed', 'reserved'])
            ->latest()
            ->get();

        return view('orders_page', compact('tables', 'sections', 'menuItems', 'reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();

        $table = Table::findOrFail($validated['table_id']);
        if ($table->status !== 'available' && $table->status !== 'reserved') {
            return back()->withInput()->with('error', 'The selected table is not available.');
        }

        $items = json_decode($validated['order_items'], true);
        if (!is_array($items) || empty($items)) {
            return back()->withInput()->with('error', 'Order must include at least one item.');
        }

        $orderNumber = 'ORD-' . strtoupper(Str::random(6));
        $lineCount = 0;

        foreach ($items as $item) {
            if (empty($item['menu_item_id']) || empty($item['quantity'])) {
                continue;
            }

            $menuItem = MenuItem::find($item['menu_item_id']);
            if (!$menuItem) {
                continue;
            }

            $unitPrice = $menuItem->price;
            $offerApplied = false;
            $discountAmount = 0;

            if (!empty($item['offer_price']) && is_numeric($item['offer_price']) && $item['offer_price'] < $unitPrice) {
                $discountAmount = ($unitPrice - $item['offer_price']) * $item['quantity'];
                $unitPrice = $item['offer_price'];
                $offerApplied = true;
            }

            Order::create([
                'order_number' => $orderNumber,
                'status' => 'pending',
                'customer_name' => $validated['customer_name'],
                'phone' => $validated['phone'],
                'guest_count' => $validated['guest_count'],
                'table_id' => $table->id,
                'menu_item_id' => $menuItem->id,
                'quantity' => $item['quantity'],
                'unit_price' => $unitPrice,
                'total_price' => ($unitPrice * $item['quantity']),
                'offer_applied' => $offerApplied,
                'discount_amount' => $discountAmount,
                'special_instructions' => $item['special_instructions'] ?? null,
            ]);

            $lineCount++;
        }

        if ($lineCount === 0) {
            return back()->withInput()->with('error', 'Order must include valid menu items.');
        }

        $table->update(['status' => 'occupied']);

        return redirect()->route('orders.index')->with('success', "Order {$orderNumber} placed successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    public function storeReservation(StoreReservationRequest $request)
    {

        $validated = $request->validated();

        $table = null;
        if (!empty($validated['table_id'])) {
            $table = Table::findOrFail($validated['table_id']);
        } elseif ($validated['reservation_type'] === 'now') {
            $table = Table::where('status', 'available')
                ->where('min_capacity', '<=', $validated['guest_count'])
                ->where('max_capacity', '>=', $validated['guest_count'])
                ->when(!empty($validated['table_type']), fn ($query) => $query->where('type', $validated['table_type']))
                ->first();
        }
        // dd('test1');
        if (!$table) {
            return back()->withInput()->with('error', 'No suitable table is available for this reservation.');
        }
        // dd('test2');
        if ($table->status !== 'available') {
            return back()->withInput()->with('error', 'The selected table is not available.');
        }

        if ($validated['reservation_type'] === 'scheduled' && !empty($validated['reservation_date']) && !empty($validated['reservation_time'])) {
            $reservationDate = $validated['reservation_date'];
            if ($reservationDate instanceof Carbon) {
                $reservationDate = $reservationDate->toDateString();
            }

            $start = Carbon::parse($reservationDate . ' ' . $validated['reservation_time']);
            $end = $start->copy()->addHours((float) ($validated['duration_hours'] ?? 1));

            $hasConflict = Reservation::where('table_id', $table->id)
                ->whereDate('reservation_date', $reservationDate)
                ->where('status', '!=', 'cancelled')
                ->get()
                ->contains(function (Reservation $reservation) use ($start, $end): bool {
                    if (!$reservation->reservation_time) {
                        return false;
                    }

                    $reservationDateValue = $reservation->reservation_date;
                    if ($reservationDateValue instanceof Carbon) {
                        $reservationDateValue = $reservationDateValue->toDateString();
                    }

                    $reservationStart = Carbon::parse($reservationDateValue . ' ' . $reservation->reservation_time);
                    $reservationEnd = $reservationStart->copy()->addHours((float) ($reservation->duration_hours ?? 1));

                    return $start->lt($reservationEnd) && $end->gt($reservationStart);
                });
        // dd('test3');
            if ($hasConflict) {
                return back()->withInput()->with('error', 'The selected table is already reserved for that time slot.');
            }
        }

        $reservation = Reservation::create([
            'reservation_number' => 'RSV-' . strtoupper(Str::random(6)),
            'customer_name' => $validated['customer_name'],
            'phone' => $validated['phone'],
            'guest_count' => $validated['guest_count'],
            'reservation_type' => $validated['reservation_type'],
            'table_id' => $table->id,
            'table_type' => $validated['table_type'] ?? $table->type,
            'reservation_date' => $validated['reservation_date'] ?? null,
            'reservation_time' => $validated['reservation_time'] ?? null,
            'duration_hours' => $validated['duration_hours'] ?? null,
            'special_occasion' => $validated['special_occasion'] ?? 'None',
            'notes' => $validated['notes'] ?? null,
            'status' => 'confirmed',
            'user_id' => $request->user()?->id,
        ]);


        $table->update(['status' => 'reserved']);



        return redirect()->back()->with('success', 'Reservation created successfully. Reservation number: ' . $reservation->reservation_number);
    }
}
