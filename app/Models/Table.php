<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;
    protected $fillable = [
        'table_number',
        'type',
        'max_capacity',
        'min_capacity',
        'status',
        'location',
        'notes',
        'unique_token'
    ];


    protected static function booted(): void
    {
        static::creating(function (Table $table): void {
            if (empty($table->unique_token)) {
                $table->unique_token = bin2hex(random_bytes(16));
            }
        });
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

}
