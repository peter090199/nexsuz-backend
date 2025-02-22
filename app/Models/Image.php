<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $table = 'menu_items';

    protected $fillable = [
        'module', 
        'description', 
        'price', 
        'desc_images', 
        'image'
    ];

    protected $casts = [
        'price' => 'array',
        'desc_images' => 'array',
    ];
}
