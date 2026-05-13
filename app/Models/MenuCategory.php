<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'menu_section_id', 'description', 'display_order', 'status'];

    public function menuSection()
    {
        return $this->belongsTo(MenuSection::class);
    }

    public function menuSubcategories()
    {
        return $this->hasMany(MenuSubcategory::class);
    }
}
