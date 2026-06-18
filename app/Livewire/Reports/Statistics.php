<?php

namespace App\Livewire\Reports;

use App\Models\Procedure;
use App\Models\User;
use App\Models\PreEnrollment;
use App\Models\Payment;
use Livewire\Component;
use Carbon\Carbon;

class Statistics extends Component
{
    public $year;
    public $reportType = 'monthly';

    public function mount()
    {
        $this->year = date('Y');
    }

    public function updatedYear()
    {
        $this->dispatch('toast', type: 'info', message: 'Actualizando datos para el año ' . $this->year);
    }

    public function render()
    {
        // Trámites por mes usando received_at
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[$i] = Procedure::whereMonth('received_at', $i)
                ->whereYear('received_at', $this->year)
                ->count();
        }

        // Distribución por estado
        $statusDistribution = [
            'Secretaría' => Procedure::where('status', Procedure::STATUS_SECRETARY)->count(),
            'Finanzas' => Procedure::where('status', Procedure::STATUS_FINANCE)->count(),
            'Revisión Académica' => Procedure::where('status', Procedure::STATUS_ACADEMIC_REVIEW)->count(),
            'Firma' => Procedure::where('status', Procedure::STATUS_SIGNATURE)->count(),
            'Observación' => Procedure::where('status', Procedure::STATUS_OBSERVATION)->count(),
            'Completado' => Procedure::where('status', Procedure::STATUS_COMPLETED)->count(),
        ];

        // Tipos de certificado
        $certificateTypes = Procedure::selectRaw('certificate_type, COUNT(*) as total')
            ->groupBy('certificate_type')
            ->pluck('total', 'certificate_type')
            ->toArray();

        // Demanda por programa
        $programDemand = Procedure::selectRaw('program, COUNT(*) as total')
            ->groupBy('program')
            ->pluck('total', 'program')
            ->toArray();

        // Tiempo promedio de finalización (días entre created_at y completed_at)
        $averageCompletionTime = Procedure::whereNotNull('completed_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(DAY, created_at, completed_at)) as avg_days')
            ->value('avg_days');

        // Ingresos mensuales usando verified_at
        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenue[$i] = Payment::whereMonth('verified_at', $i)
                ->whereYear('verified_at', $this->year)
                ->where('is_verified', true)
                ->sum('amount');
        }

        // Ingresos totales
        $totalRevenue = Payment::where('is_verified', true)->sum('amount');
        
        // Ingresos del año seleccionado
        $yearlyRevenue = Payment::whereYear('verified_at', $this->year)
            ->where('is_verified', true)
            ->sum('amount');

        return view('livewire.reports.statistics', [
            'monthlyData' => $monthlyData,
            'statusDistribution' => $statusDistribution,
            'certificateTypes' => $certificateTypes,
            'programDemand' => $programDemand,
            'averageCompletionTime' => round($averageCompletionTime ?? 0, 1),
            'monthlyRevenue' => $monthlyRevenue,
            'totalUsers' => User::count(),
            'totalProcedures' => Procedure::count(),
            'totalPreEnrollments' => PreEnrollment::count(),
            'totalRevenue' => $totalRevenue,
            'yearlyRevenue' => $yearlyRevenue,
            'pendingPayments' => Payment::where('is_verified', false)->sum('amount'),
        ]);
    }
}