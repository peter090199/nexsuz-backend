<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_code',
        'file_path',
        'trans_no',
        'title',
        'description',
        'stats',
    ];
    protected $casts = [
        'stats' => 'array', // Automatically cast stats JSON to array
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_code', 'code'); // Assuming `code` is a unique identifier for users
    }
}
