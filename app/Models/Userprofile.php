<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class Userprofile extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'code',
        'transNo',
        'photo_pic',
        'contact_no',
        'contact_visibility',
        'email',
        'email_visibility',
        'summary',
        'date_birth',
        'home_country',
        'home_state',
        'current_location',
        'current_state'
    ];
}

