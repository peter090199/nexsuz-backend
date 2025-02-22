<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usertraining extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'code',
        'transNo',
        'training_title',
        'training_provider',
        'date_completed',
    ];
}
