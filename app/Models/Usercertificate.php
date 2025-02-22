<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usercertificate extends Model
{
      
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'code',
        'transNo',
        'certificate_title',
        'certificate_provider',
        'date_completed'
    ];
}
