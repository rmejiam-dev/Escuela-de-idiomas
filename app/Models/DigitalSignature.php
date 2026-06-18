<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'procedure_id',
        'user_id',
        'signer_name',
        'signer_position',
        'signature_image_path',
        'signature_hash',
        'signed_at',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
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