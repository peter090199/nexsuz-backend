<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transNo',
        'module',
        'description',
        'price',
        'image',
        'desc_images',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'price' => 'array',
        'image' => 'array',
        'desc_images' => 'array',
    ];

    public function prices()
    {
        return $this->hasMany(MenuItemPrice::class);
    }

    public function images()
    {
        return $this->hasMany(MenuItemImage::class);
    }

    public function descImages()
    {
        return $this->hasMany(MenuItemDescImage::class);
    }
}
