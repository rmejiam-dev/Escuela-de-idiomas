<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcedureHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'procedure_id',
        'user_id',
        'action',
        'from_status',
        'to_status',
        'comments',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}