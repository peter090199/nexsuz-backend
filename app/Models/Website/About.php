<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $table = 'about'; // Replace with your actual table name

    protected $fillable = [
        'transNo',
        'about',
        'description',
        'created_by',
        'updated_by'
    ];
}
