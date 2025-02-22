<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Roleaccesssubmenu extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'rolecode',
        'transNo',
        'rolemenus_id',
        'submenus_id',
        'created_by',
        'updated_by',
    ];
}
