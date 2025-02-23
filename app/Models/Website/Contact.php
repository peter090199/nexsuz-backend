<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = [
        'transNo',
        'contact_title',
        'description',
        'fbpage',
        'mLink',
        'phoneNumber',
    ];
}
