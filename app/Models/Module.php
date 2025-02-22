<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    protected $table = 'module';
    public $timestamps = false; 

    protected $fillable = [
        'transNo',
        'module',
        'routes',
        'sort',
        'status',
        'updated_at'
    ];

    
}
