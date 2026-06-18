<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class PaymentVerification extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    #[On('verify')]
    public function verify($id)
    {
        $payment = Payment::findOrFail($id);

        $payment->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => auth()->id(),            
        ]);


        $payment->procedure->addHistory(
            auth()->id(),
            'payment_verified',
            $payment->procedure->status,
            $payment->procedure->status,
            'Pago verificado por secretaría'
        );
        $payment->procedure->updateStatus(
            "signature",
            auth()->id(),
            "Aprobado para firmar"
        );

        $this->dispatch('toast', type: 'success', message: 'Pago verificado correctamente');
    }

    #[On('reject')]
    public function reject($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->procedure->updateStatus('observation', auth()->id(), 'Pago rechazado');

        $this->dispatch('toast', type: 'warning', message: 'Pago rechazado');
    }

    public function render()
    {
        $query = Payment::with(['procedure', 'verifiedBy'])
            ->where('is_verified', false);

        if ($this->search) {
            $query->whereHas('procedure', function ($q) {
                $q->where('student_name', 'like', '%' . $this->search . '%')
                    ->orWhere('student_identification', 'like', '%' . $this->search . '%');
            });
        }

        $payments = $query->orderBy('created_at', 'asc')->paginate($this->perPage);

        return view('livewire.payments.payment-verification', [
            'payments' => $payments,
        ]);
    }
}