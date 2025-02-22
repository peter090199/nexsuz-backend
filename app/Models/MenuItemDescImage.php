<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemDescImage extends Model
{
    use HasFactory;

    protected $fillable = ['menu_item_id', 'desc_text'];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}