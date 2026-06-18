<?php

namespace App\Livewire;

use App\Models\Procedure;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[$i] = Procedure::whereMonth('received_at', $i)
                ->whereYear('received_at', date('Y'))
                ->count();
        }

        $statusCounts = [
            'secretary' => Procedure::where('status', Procedure::STATUS_SECRETARY)->count(),
            'finance' => Procedure::where('status', Procedure::STATUS_FINANCE)->count(),            
            'academic_review' => Procedure::where('status', Procedure::STATUS_ACADEMIC_REVIEW)->count(),
            'signature' => Procedure::where('status', Procedure::STATUS_SIGNATURE)->count(),
            'observation' => Procedure::where('status', Procedure::STATUS_OBSERVATION)->count(),
            'completed' => Procedure::where('status', Procedure::STATUS_COMPLETED)->count(),
        ];

        return view('livewire.dashboard', [
            'totalUsers' => User::count(),
            'totalProcedures' => Procedure::count(),
            'pendingProcedures' => Procedure::whereIn('status', [
                Procedure::STATUS_RECEPTION, 
                Procedure::STATUS_SECRETARY, 
                Procedure::STATUS_ACADEMIC_REVIEW
            ])->count(),
            'completedProcedures' => Procedure::where('status', Procedure::STATUS_COMPLETED)->count(),
            'monthlyData' => $monthlyData,
            'statusCounts' => $statusCounts,
            'recentProcedures' => Procedure::with('user')->latest()->take(5)->get(),
        ]);
    }
}