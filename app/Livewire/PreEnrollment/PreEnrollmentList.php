<?php

namespace App\Livewire\PreEnrollment;

use App\Models\PreEnrollment;
use Livewire\Component;
use Livewire\WithPagination;

class PreEnrollmentList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $perPage = 10;

    protected $queryString = ['search', 'status'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updateStatus($id, $status)
    {
        $preEnrollment = PreEnrollment::findOrFail($id);
        $preEnrollment->update(['status' => $status]);
        session()->flash('success', 'Estado actualizado correctamente');
    }

    public function delete($id)
    {
        PreEnrollment::findOrFail($id)->delete();
        session()->flash('success', 'Preinscripción eliminada correctamente');
    }

    public function render()
    {
        $query = PreEnrollment::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('identification_number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $preEnrollments = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.pre-enrollment.pre-enrollment-list', [
            'preEnrollments' => $preEnrollments,
        ]);
    }
}