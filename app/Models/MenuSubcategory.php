<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuSubcategory extends Model
{
    use HasFactory ;
    protected $fillable = ['name', 'menu_category_id', 'item_count', 'display_order', 'status'];

    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
}
