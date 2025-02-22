<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemPrice extends Model
{
    use HasFactory;

    protected $fillable = ['menu_item_id', 'price'];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
