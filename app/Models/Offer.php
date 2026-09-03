<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'discount_value',
        'discount_type',
        'applicable_items_count',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'status',
        'applicable_days',
        'display_on_menu',
    ];

    protected $casts = [
        'applicable_days' => 'json',
        'start_date' => 'date',
        'end_date' => 'date',
        'display_on_menu' => 'boolean',
    ];

    public function items()
    {
        return $this->belongsToMany(MenuItem::class, 'offer_menu_item', 'offer_id', 'menu_item_id')
            ->withPivot('discounted_price')
            ->withTimestamps();
    }

    public function isActive(): bool
    {

        if ($this->status !== 'active') {
            return false;
        }

        $today = now()->format('Y-m-d');
        $currentTime = now()->format('H:i');
        $dayOfWeek = now()->dayOfWeek;

        if ( $today < $this->start_date->format('Y-m-d')) {
            return false;
        }

        if ($today > $this->end_date->format('Y-m-d')) {
            return false;
        }

        if ($this->start_time || $this->end_time) {
            if ($this->start_time && $currentTime < $this->start_time) {
                return false;
            }

            if ($this->end_time && $currentTime > $this->end_time) {
                return false;
            }
        }

        if ($this->applicable_days && is_array($this->applicable_days)) {

            $Day = $dayOfWeek === 0 ? 7 : $dayOfWeek;

            if (!in_array($Day, $this->applicable_days)) {
                return false;
            }
        }

        return true;
    }
    public static function getActiveOffersForItem($menuItemId): array
    {
        return self::whereHas('items', function ($query) use ($menuItemId) {
            $query->where('menu_item_id', $menuItemId);
        })->get()->filter(function ($offer) {
            return $offer->isActive();
        })->values()->toArray();
    }
}
