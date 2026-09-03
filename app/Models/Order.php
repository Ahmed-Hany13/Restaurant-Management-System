<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'table_id',
        'menu_item_id',
        'quantity',
        'total_price',
        'order_number',
        'status',
        'preparation_started_at',
        'customer_name',
        'phone',
        'guest_count',
        'unit_price',
        'offer_applied',
        'discount_amount',
    ];

    protected $casts = [
        'preparation_started_at' => 'datetime',
    ];

    public function menuItems()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
