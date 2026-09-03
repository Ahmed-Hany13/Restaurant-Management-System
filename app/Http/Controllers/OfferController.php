<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfferRequest;
use App\Models\MenuItem;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offers = Offer::paginate(10);
        return view('admin.offers.index', compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menuItems = MenuItem::where('status', 'active')->get();
        return view('admin.offers.create-offer', compact('menuItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOfferRequest $request)
    {
        $validated = $request->validated();

        // Convert applicable_days array to JSON
        if (!empty($validated['applicable_days'])) {
            $validated['applicable_days'] = json_encode($validated['applicable_days']);
        } else {
            $validated['applicable_days'] = null;
        }

        // Convert display_on_menu checkbox to boolean
        $validated['display_on_menu'] = $request->has('display_on_menu') ? true : false;

        $offer = Offer::create($validated);

        if (!empty($validated['menu_items'])) {
            $menuItemIds = array_filter(explode(',', $validated['menu_items']));

            $data = [];
            foreach ($menuItemIds as $itemId) {
                $menuItem = MenuItem::find($itemId);
                if ($menuItem) {
                    $discountedPrice = $this->calculateDiscountedPrice(
                        $menuItem->price,
                        $validated['discount_value'],
                        $validated['discount_type']
                    );

                    $data[$itemId] = [
                        'discounted_price' => $discountedPrice,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            $offer->items()->attach($data);
        }

        return redirect()->route('offers.index')->with('success', 'Offer created successfully.');
    }

    /**
     * Calculate discounted price based on discount type and value
     */
    private function calculateDiscountedPrice(float $originalPrice, float $discountValue, string $discountType)
    {
        if ($discountType === 'percentage') {
            return $originalPrice * (100 - $discountValue) / 100;
        } elseif ($discountType === 'fixed') {
            return $originalPrice - $discountValue;
        }else{
            return $originalPrice;
        }
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
        $offer = Offer::findOrFail($id);
        $offer->items()->detach();
        $offer->delete();

        return redirect()->route('offers.index')->with('success', 'Offer deleted successfully.');
    }
}
