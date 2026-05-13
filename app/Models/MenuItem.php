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
}
