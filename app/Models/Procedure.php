<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    use HasFactory;

    const STATUS_RECEPTION = 'reception';
    const STATUS_SECRETARY = 'secretary';
    const STATUS_ACADEMIC_REVIEW = 'academic_review';
    const STATUS_SIGNATURE = 'signature';
    const STATUS_OBSERVATION = 'observation';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FINANCE = 'finance';

    protected $fillable = [
        'user_id',
        'certificate_type',
        'student_name',
        'student_identification',
        'birth_date',
        'program',
        'study_period',
        'final_grades_average',
        'status',
        'observations',
        'observed_from_stage',
        'received_at',
        'secretary_approved_at',
        'finance_approved_at',
        'academic_reviewed_at',
        'signed_at',
        'completed_at',
        'generated_at',
        'certificate_file_path',
        'document_hash',
        'grades_data',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'received_at' => 'datetime',
        'secretary_approved_at' => 'datetime',
        'finance_approved_at' => 'datetime',
        'academic_reviewed_at' => 'datetime',
        'signed_at' => 'datetime',
        'completed_at' => 'datetime',
        'generated_at' => 'datetime',
        'final_grades_average' => 'decimal:2',
        'grades_data' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($procedure) {
            if (empty($procedure->status)) {
                $procedure->status = self::STATUS_SECRETARY;
            }
            if (empty($procedure->received_at)) {
                $procedure->received_at = now();
            }
        });
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function histories()
    {
        return $this->hasMany(ProcedureHistory::class);
    }

    public function signatures()
    {
        return $this->hasMany(DigitalSignature::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function addHistory($userId, $action, $fromStatus, $toStatus, $comments = null, $metadata = null)
    {
        return $this->histories()->create([
            'user_id' => $userId,
            'action' => $action,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'comments' => $comments,
            'metadata' => $metadata,
        ]);
    }

    public function updateStatus($newStatus, $userId, $comments = null)
    {
        $oldStatus = $this->status;
        $this->status = $newStatus;
        $this->save();

        $this->addHistory($userId, 'status_change', $oldStatus, $newStatus, $comments);

        return $this;
    }
}