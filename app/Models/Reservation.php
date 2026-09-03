<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_number',
        'customer_name',
        'phone',
        'guest_count',
        'reservation_type',
        'table_id',
        'table_type',
        'reservation_date',
        'reservation_time',
        'duration_hours',
        'special_occasion',
        'notes',
        'status',
        'user_id',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'duration_hours' => 'decimal:1',
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
