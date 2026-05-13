<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuSection extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'display_order', 'status'];

    public function menuCategories()
    {
        return $this->hasMany(MenuCategory::class);
    }
}
