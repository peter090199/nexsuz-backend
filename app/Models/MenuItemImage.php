<?php

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemImage extends Model
{
    use HasFactory;

    protected $fillable = ['menu_item_id', 'image_url'];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}