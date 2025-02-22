<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Roleaccessmenu extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'rolecode', // example field
        'transNo',
        'menus_id', // example field
        'created_by',
        'updated_by'
    ];

}