<?php

namespace App\Livewire\Procedures;

use App\Models\Procedure;
use App\Models\Payment;
use Livewire\WithFileUploads;
use Livewire\Component;

class ProcedureForm extends Component
{
    use WithFileUploads;
    public $procedureId;
    public $certificate_type;
    public $student_name;
    public $student_identification;
    public $birth_date;
    public $program;
    public $payment_receipt;

    protected $rules = [
        // 'payment_receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'certificate_type' => 'required|string',
        'student_name' => 'required|string|max:255',
        'student_identification' => 'required|string|validate_rut',
        'birth_date' => 'required|date',
        'program' => 'required|string',
    ];
    protected $messages = [
        'payment_receipt.mimes' => 'Solo se permiten archivos JPG, PNG o PDF',
        'payment_receipt.max' => 'El archivo no debe superar los 2MB',
        'student_identification.validate_rut' => 'Revise su información y vuelva a intentarlo (formato: 12345678-9)',
    ];

    public function mount($procedureId = null)
    {
        if ($procedureId) {
            $this->procedureId = $procedureId;
            $procedure = Procedure::with('payments')->findOrFail($procedureId);
            if (auth()->id() != $procedure->user_id && !auth()->user()->can('edit procedures')) {
                $this->dispatch('toast-and-redirect', type: 'error', message: 'No tienes permisos para editar este trámite', route: url()->previous());
                return;
            }

            $this->certificate_type = $procedure->certificate_type;
            $this->student_name = $procedure->student_name;
            $this->student_identification = $procedure->student_identification;
            $this->birth_date = $procedure->birth_date->format('Y-m-d');
            $this->program = $procedure->program;

            if ($procedure->payments->isNotEmpty()) {
                $payment = $procedure->payments->first();
                $this->payment_receipt = $payment->receipt_path;
            }
        }
    }

    public function save()
    {
        if ($this->payment_receipt && is_a($this->payment_receipt, 'Illuminate\Http\UploadedFile')) {
            $rules['payment_receipt'] = 'file|mimes:jpg,jpeg,png,pdf|max:2048';
        }
        $this->validate();

        $data = [
            'user_id' => auth()->id(),
            'certificate_type' => $this->certificate_type,
            'student_name' => $this->student_name,
            'student_identification' => $this->formatIdentificationNumber($this->student_identification),
            'birth_date' => $this->birth_date,
            'program' => $this->program,
            // 'status' => Procedure::STATUS_SECRETARY,
            // 'received_at' => now(),
        ];

        if ($this->procedureId) {
            $procedure = Procedure::findOrFail($this->procedureId);
            $procedure->update($data);

            $payment = $procedure->payments->first();
            if ($this->payment_receipt && is_a($this->payment_receipt, 'Illuminate\Http\UploadedFile')) {
                $originalName = pathinfo($this->payment_receipt->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $this->payment_receipt->getClientOriginalExtension();
                $uniqueName = $originalName . '_' . time() . '.' . $extension;
                $receiptPath = $this->payment_receipt->storeAs('receipts', $uniqueName, 'public');

                if ($payment) {
                    $payment->update(['receipt_path' => $receiptPath]);
                } else {
                    $originalName = pathinfo($this->payment_receipt->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $this->payment_receipt->getClientOriginalExtension();
                    $uniqueName = $originalName . '_' . time() . '.' . $extension;
                    $receiptPath = $this->payment_receipt->storeAs('receipts', $uniqueName, 'public');

                    Payment::create([
                        'procedure_id' => $procedure->id,
                        'reference_number' => 'PAY-' . strtoupper(uniqid()),
                        'receipt_path' => $receiptPath,
                        'is_verified' => false,
                    ]);
                }
            }
            $procedure->addHistory(auth()->id(), 'Editado', null, "Editado", 'Trámite editado');
            $this->dispatch('toast-and-redirect', type: 'success', message: 'Trámite actualizado exitosamente', route: route('procedures.index'));
        } else {
            $procedure = Procedure::create($data);

            if ($this->payment_receipt && is_a($this->payment_receipt, 'Illuminate\Http\UploadedFile')) {
                $originalName = pathinfo($this->payment_receipt->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $this->payment_receipt->getClientOriginalExtension();
                $uniqueName = $originalName . '_' . time() . '.' . $extension;
                $receiptPath = $this->payment_receipt->storeAs('receipts', $uniqueName, 'public');

                Payment::create([
                    'procedure_id' => $procedure->id,
                    'reference_number' => 'PAY-' . strtoupper(uniqid()),
                    'receipt_path' => $receiptPath,
                    'is_verified' => false,
                ]);
            }

            $procedure->addHistory(auth()->id(), 'create', null, Procedure::STATUS_SECRETARY, 'Trámite creado');
            $this->dispatch('toast-and-redirect', type: 'success', message: 'Trámite creado exitosamente', route: route('procedures.index'));
        }

        // return redirect()->route('procedures.index');
    }

    protected function validateRut($rut)
    {
        // Limpiar el RUT
        $rut = preg_replace('/[^k0-9]/i', '', $rut);
        $rut = strtoupper($rut);

        // Separar número y dígito verificador
        $numero = substr($rut, 0, -1);
        $dv_ingresado = substr($rut, -1);

        // Validar formato
        if (!is_numeric($numero) || strlen($numero) < 6) {
            return false;
        }

        // Calcular dígito verificador
        $suma = 0;
        $multiplo = 2;

        for ($i = strlen($numero) - 1; $i >= 0; $i--) {
            $suma += $numero[$i] * $multiplo;
            $multiplo = $multiplo == 7 ? 2 : $multiplo + 1;
        }

        $dv_esperado = 11 - ($suma % 11);
        if ($dv_esperado == 11) $dv_esperado = 0;
        if ($dv_esperado == 10) $dv_esperado = 'K';

        // Comparar
        return $dv_esperado == $dv_ingresado;
    }

    // Formatear el RUT para almacenar (opcional)
    protected function formatIdentificationNumber($rut)
    {
        // Limpiar el RUT
        $rut = preg_replace('/[^k0-9]/i', '', $rut);
        $rut = strtoupper($rut);

        // Formatear como 12345678-9
        if (strlen($rut) > 1) {
            $numero = substr($rut, 0, -1);
            $dv = substr($rut, -1);
            return $numero . '-' . $dv;
        }

        return $rut;
    }

    public function render()
    {
        return view('livewire.procedures.procedure-form');
    }
}
