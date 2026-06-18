<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'identification_number',
        'program_interest',
        'message',
        'status',
        'request_ip',
        'captcha_response',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}