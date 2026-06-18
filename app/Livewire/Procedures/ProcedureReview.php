<?php

namespace App\Livewire\Procedures;

use App\Models\Procedure;
use Livewire\Component;
use Livewire\WithPagination;

class ProcedureReview extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $search = '';

    public function render()
    {
        $query = Procedure::with('user');

        if (auth()->user()->hasRole('secretary')) {
            $query->whereIn('status', [Procedure::STATUS_RECEPTION, Procedure::STATUS_OBSERVATION]);
        } elseif (auth()->user()->hasRole('academic')) {
            $query->where('status', Procedure::STATUS_SECRETARY);
        } elseif (auth()->user()->hasRole('signer')) {
            $query->where('status', Procedure::STATUS_ACADEMIC_REVIEW);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('student_name', 'like', '%' . $this->search . '%')
                  ->orWhere('student_identification', 'like', '%' . $this->search . '%');
            });
        }

        $procedures = $query->orderBy('created_at', 'asc')->paginate(10);

        return view('livewire.procedures.procedure-review', [
            'procedures' => $procedures,
        ]);
    }
}