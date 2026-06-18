<?php

namespace App\Livewire\Procedures;

use App\Models\Procedure;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;

class ProcedureIndex extends Component
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
    public function downloadCertificate()
    {
        // Verificar que el trámite esté completado
        if ($this->procedure->status !== Procedure::STATUS_COMPLETED) {
            $this->dispatch('toast', type: 'error', message: 'El trámite no está completado');
            return;
        }

        // Generar el PDF
        $pdf = PDF::loadView('pdf.certificate', [
            'procedure' => $this->procedure
        ]);

        // Configurar el PDF
        $pdf->setPaper('a4', 'portrait');

        // Descargar el PDF
        return response()->streamDownload(
            function () use ($pdf) {
                echo $pdf->output();
            },
            'certificado_' . $this->procedure->id . '_' . date('Ymd') . '.pdf'
        );
    }

    public function render()
    {
        $query = Procedure::with('user');

        if (!auth()->user()->can('view all procedures')) {
            $query->where('user_id', auth()->id());
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('student_name', 'like', '%' . $this->search . '%')
                    ->orWhere('student_identification', 'like', '%' . $this->search . '%')
                    ->orWhere('certificate_type', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $procedures = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.procedures.procedure-index', [
            'procedures' => $procedures,
        ]);
    }
}
