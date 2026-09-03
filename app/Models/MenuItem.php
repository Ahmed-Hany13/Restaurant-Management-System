<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'price', 'menu_subcategory_id', 'status', 'has_offer', 'image', 'preparation_time'];

    public function menuSubcategory()
    {
        return $this->belongsTo(MenuSubcategory::class);
    }


    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_menu_item', 'menu_item_id', 'offer_id')
            ->withPivot('discounted_price');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    public function getActiveOffers()
    {
        return $this->offers()
            ->where('status', 'active')
            ->get()
            ->filter(function ($offer) {
                return $offer->isActive();
            });
    }
}
