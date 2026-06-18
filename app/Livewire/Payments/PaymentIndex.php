<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $verifiedFilter = '';
    public $perPage = 10;

    public function render()
    {
        $query = Payment::with(['procedure', 'verifiedBy']);

        if ($this->search) {
            $query->whereHas('procedure', function($q) {
                $q->where('student_name', 'like', '%' . $this->search . '%')
                  ->orWhere('student_identification', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->verifiedFilter !== '') {
            $query->where('is_verified', $this->verifiedFilter);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.payments.payment-index', [
            'payments' => $payments,
        ]);
    }
}