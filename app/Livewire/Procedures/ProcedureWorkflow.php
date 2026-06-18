<?php

namespace App\Livewire\Procedures;

use App\Models\Procedure;
use App\Models\DigitalSignature;
use App\Models\Payment;
use Livewire\Component;
use Livewire\WithFileUploads;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProcedureWorkflow extends Component
{
    use WithFileUploads;

    public $procedure;
    public $observation;
    public $final_grades_average;
    public $payment_amount;
    public $payment_method;
    public $payment_receipt;
    public $signature_image;
    public $signer_name;
    public $signer_position;
    public $grades_file;
    public $grades_data = [];
    public $grades_average = 0;
    public $approved_periods;

    protected $rules = [
        'observation' => 'nullable|string',
        'final_grades_average' => 'nullable|numeric|min:0|max:5',
        'payment_amount' => 'nullable|numeric|min:0',
        'payment_method' => 'nullable|string',
        'payment_receipt' => 'nullable|image|max:2048',
        'signature_image' => 'nullable|image|max:2048',
        'signer_name' => 'nullable|string|max:255',
        'signer_position' => 'nullable|string|max:255',
        'grades_file' => 'nullable|file|mimes:xlsx,xls,csv|max:2048',
        'approved_periods' => 'nullable|integer|min:1|max:12',
    ];

    public function mount($procedureId)
    {
        $this->procedure = Procedure::with(['histories', 'signatures', 'payments'])->findOrFail($procedureId);
    }
    public function uploadGrades($procedureId)
    {
        $this->validate([
            'grades_file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        $procedure = Procedure::findOrFail($procedureId);

        $file = $this->grades_file;
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $grades = [];
        $total = 0;
        $count = 0;

        // Saltar la primera fila (encabezados)
        $headers = array_shift($rows);

        foreach ($rows as $index => $row) {
            $periodText = $row[0] ?? null; // Columna A = Período (texto)
            $grade = $row[1] ?? null; // Columna B = Nota

            if (is_numeric($grade) && $grade >= 1.0 && $grade <= 5.0) {
                $grades[] = [
                    'period' => $periodText ?: ($index + 1),
                    'grade' => (float)$grade
                ];
                $total += (float)$grade;
                $count++;
            }
        }

        if (empty($grades)) {
            $this->dispatch('toast', type: 'error', message: 'No se encontraron notas válidas en el archivo');
            return;
        }

        $average = $total / $count;

        $procedure->update([
            'final_grades_average' => $average,
            'grades_data' => [
                'grades' => $grades                
            ]
        ]);
        $this->procedure->refresh();
        $this->dispatch('toast', type: 'success', message: "Notas cargadas correctamente. Promedio: " . number_format($average, 2));
        $this->grades_file = null;
    }


    public function viewFile($procedureId)
    {
        $payment = Payment::where('procedure_id', $procedureId)->first();

        if (!$payment || !$payment->receipt_path) {
            $this->dispatch('toast', type: 'error', message: 'No hay comprobante asociado');
            return;
        }

        $fullPath = storage_path('app/public/' . $payment->receipt_path);



        if (!file_exists($fullPath)) {
            $this->dispatch('toast', type: 'error', message: 'El archivo no existe');
            return;
        }

        $url = asset('storage/' . $payment->receipt_path);
        // dd($url);
        $this->dispatch('open-file', url: $url);
    }
    public function downloadCertificate($id)
    {
        $procedure = Procedure::with('signatures')->findOrFail($id);

        if (!$procedure->certificate_file_path) {
            return $this->generateCertificate($id);
        }

        if (!Storage::disk('public')->exists($procedure->certificate_file_path)) {
            return $this->generateCertificate($id);
        }

        $fileName = $procedure->student_name . "_" . str_replace('_', ' ', $procedure->certificate_type) . ".pdf";

        return Storage::disk('public')->download($procedure->certificate_file_path, $fileName);
    }

    // public function delete($id)
    // {
    //     $procedure = Procedure::with('signatures')->findOrFail($id);
    //     $procedure->update(['certificate_file_path' => null]);
    //     $this->dispatch('toast-and-redirect', type: 'success', message: 'Certificado eliminado correctamente', route: url()->previous());
    // }

    public function delete($id)
    {
        $procedure = Procedure::findOrFail($id);

        if ($procedure->certificate_file_path) {
            Storage::disk('public')->delete($procedure->certificate_file_path);
        }

        $procedure->update([
            'certificate_file_path' => null,
            'document_hash' => null,
            'generated_at' => null,
            'grades_data' => null // <-- LIMPIAR NOTAS TAMBIÉN
        ]);

        $this->dispatch('toast-and-redirect', type: 'success', message: 'Certificado eliminado correctamente', route: url()->previous());
    }
    public $loading = false;

    // public function generateCertificate($id)
    // {
    //     $procedure = Procedure::with('signatures')->findOrFail($id);
    //     $gradesData = session()->get('temp_grades_' . $id, []);
    //     $gradesAverage = session()->get('temp_average_' . $id, 0);
    //     $procedure->update([
    //         'generated_at' => now()
    //     ]);
    //     switch ($procedure->certificate_type) {
    //         case 'academic_record':
    //             $pdf = Pdf::loadView('pdf.academic_record', [
    //                 'procedure' => $procedure,
    //                 'grades_data' => $gradesData,
    //                 'grades_average' => $gradesAverage
    //             ]);
    //             break;
    //         case 'study_certificate':
    //             $pdf = Pdf::loadView('pdf.study_certificate', ['procedure' => $procedure,]);
    //             break;

    //         default:
    //             $pdf = Pdf::loadView('pdf.certificate', ['procedure' => $procedure]);
    //             break;
    //     }
    //     $pdf->setOptions([
    //         'defaultFont' => 'dejavu sans',
    //         'isRemoteEnabled' => true,
    //         'isHtml5ParserEnabled' => true,
    //         'dpi' => 96
    //     ]);
    //     $fileName = 'certificates/certificado_' . $procedure->id . '_' . date('Ymd_His') . '.pdf';
    //     $pdfContent = $pdf->output();


    //     $documentHash = hash('sha256', $pdfContent);

    //     if (!$documentHash) {
    //         $this->dispatch('toast-and-redirect', type: 'error', message: 'No se pudo generar HASH', route: url()->previous());
    //     }

    //     $procedure->update([
    //         'certificate_file_path' => $fileName,
    //         'document_hash' => $documentHash,
    //         'generated_at' => now()
    //     ]);


    //     Storage::disk('public')->put($fileName, $pdfContent);

    //     session()->forget('temp_grades_' . $id);
    //     session()->forget('temp_average_' . $id);

    //     $this->dispatch('toast-and-redirect', type: 'success', message: 'Certificado generado correctamente', route: url()->previous());
    // }
    public function generateCertificate($id)
    {
        $procedure = Procedure::with('signatures')->findOrFail($id);

        // Obtener datos del campo JSON
        $gradesData = $procedure->grades_data ?? null;
        $gradesArray = $gradesData['grades'] ?? [];
        $gradesAverage = $gradesData['average'] ?? 0;

        switch ($procedure->certificate_type) {
            case 'academic_record':
                $pdf = Pdf::loadView('pdf.academic_record', [
                    'procedure' => $procedure,
                    'grades_data' => $gradesArray,
                    'grades_average' => $gradesAverage
                ]);
                break;
            case 'study_certificate':
                $pdf = Pdf::loadView('pdf.study_certificate', ['procedure' => $procedure]);
                break;
            default:
                $pdf = Pdf::loadView('pdf.certificate', ['procedure' => $procedure]);
                break;
        }

        $pdf->setOptions([
            'defaultFont' => 'dejavu sans',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'dpi' => 96
        ]);

        $fileName = 'certificates/certificado_' . $procedure->id . '_' . date('Ymd_His') . '.pdf';
        $pdfContent = $pdf->output();
        $documentHash = hash('sha256', $pdfContent);

        if (!$documentHash) {
            $this->dispatch('toast-and-redirect', type: 'error', message: 'No se pudo generar HASH', route: url()->previous());
            return;
        }

        Storage::disk('public')->put($fileName, $pdfContent);

        $procedure->update([
            'certificate_file_path' => $fileName,
            'document_hash' => $documentHash,
            'generated_at' => now()
        ]);

        $this->dispatch('toast-and-redirect', type: 'success', message: 'Certificado generado correctamente', route: url()->previous());
    }

    public function approveSecretary()
    {
        $this->procedure->updateStatus(Procedure::STATUS_FINANCE, auth()->id(), 'Aprobado por secretaría');
        $this->procedure->update(['secretary_approved_at' => now()]);
        $this->dispatch('toast', type: 'success', message: 'Trámite aprobado por secretaría');
    }
    public function approveFromObservation()
    {
        $originStage = $this->procedure->observed_from_stage ?? Procedure::STATUS_SECRETARY;

        // NO actualizar el status con addHistory, solo cambiar el estado
        $this->procedure->status = $originStage;
        $this->procedure->observations = null;
        $this->procedure->observed_from_stage = null;
        $this->procedure->save();

        // Registrar en historial
        $this->procedure->addHistory(
            auth()->id(),
            'reingreso_desde_observacion',
            'observation',
            $originStage,
            'Reingresado desde observación a ' . str_replace('_', ' ', $originStage)
        );

        $this->dispatch('toast', type: 'success', message: 'Trámite reingresado a ' . str_replace('_', ' ', $originStage));
    }

    public function approveFinance()
    {
        $this->procedure->updateStatus(Procedure::STATUS_ACADEMIC_REVIEW, auth()->id(), 'Pago verificado por finanzas');
        $this->procedure->update(['finance_approved_at' => now()]);
        $this->dispatch('toast', type: 'success', message: 'Trámite enviado a revisión académica');
    }

    // public function rejectToObservation()
    // {
    //     $this->validate(['observation' => 'required|string|min:10']);

    //     $this->procedure->updateStatus(Procedure::STATUS_OBSERVATION, auth()->id(), $this->observation);
    //     $this->procedure->update(['observations' => $this->observation]);

    //     $this->dispatch('toast', type: 'success', message: 'Trámite enviado a observación');
    //     $this->observation = '';
    // }
    public function rejectToObservation()
    {
        $this->validate(['observation' => 'required|string|min:10']);

        // Verificar que la etapa actual permite observación
        $allowedStages = ['secretary', 'finance'];
        if (!in_array($this->procedure->status, $allowedStages)) {
            $this->dispatch('toast', type: 'error', message: 'No se puede enviar a observación en esta etapa');
            return;
        }

        $currentStage = $this->procedure->status;

        $this->procedure->updateStatus(Procedure::STATUS_OBSERVATION, auth()->id(), $this->observation);
        $this->procedure->update([
            'observations' => $this->observation,
            'observed_from_stage' => $currentStage, // Guardar la etapa de origen
        ]);

        $this->dispatch('toast', type: 'success', message: 'Trámite enviado a observación desde ' . str_replace('_', ' ', $currentStage));
        $this->observation = '';
    }

    public function approveAcademic()
    {
        $this->validate(['approved_periods' => 'nullable|integer|min:1|max:12']);

        $updateData = [
            'study_period' => $this->approved_periods,
            'academic_reviewed_at' => now()
        ];

        // Si es language_certificate, validar y agregar promedio
        if ($this->procedure->certificate_type === 'language_certificate') {
            $average = $this->final_grades_average ?? session()->get('temp_average_' . $this->procedure->id);

            if (!$average) {
                $this->addError('final_grades_average', 'Debe cargar notas o ingresar el promedio manualmente');
                return;
            }

            $updateData['final_grades_average'] = $average;
            $message = "Períodos: {$this->approved_periods} | Promedio: {$average}";

            // Limpiar datos temporales de sesión
            session()->forget('temp_grades_' . $this->procedure->id);
            session()->forget('temp_average_' . $this->procedure->id);
        } else {
            $message = "Períodos registrados: {$this->approved_periods}";
        }

        $this->procedure->updateStatus(Procedure::STATUS_SIGNATURE, auth()->id(), $message);
        $this->procedure->update($updateData);

        $this->dispatch('toast', type: 'success', message: $message);
        $this->reset(['approved_periods', 'final_grades_average']);
    }

    public function registerPayment()
    {
        $this->validate([
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_receipt' => 'required|image|max:2048',
        ]);

        $receiptPath = $this->payment_receipt->store('receipts', 'public');

        Payment::create([
            'procedure_id' => $this->procedure->id,
            'amount' => $this->payment_amount,
            'payment_method' => $this->payment_method,
            'reference_number' => 'PAY-' . strtoupper(uniqid()),
            'receipt_path' => $receiptPath,
            'is_verified' => false,
        ]);

        $this->procedure->addHistory(auth()->id(), 'payment_registered', $this->procedure->status, $this->procedure->status, 'Pago registrado');

        //cambio de estado a verificacion de pagos
        $this->procedure->updateStatus(Procedure::STATUS_FINANCE, auth()->id(), 'Pago en espera de aprobación');

        // session()->flash('success', 'Pago registrado correctamente');
        $this->reset(['payment_amount', 'payment_method', 'payment_receipt']);
        $this->dispatch('toast-and-redirect', type: 'success', message: 'Pago registrado correctamente', route: url()->previous());
    }

    public function verifyPayment($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $payment->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        $this->procedure->addHistory(auth()->id(), 'payment_verified', $this->procedure->status, $this->procedure->status, 'Pago verificado');

        $this->procedure->updateStatus(Procedure::STATUS_SIGNATURE, auth()->id(), 'Certificado a la espera de firmas');
        $this->dispatch('toast-and-redirect', type: 'success', message: 'Pago verificado correctamente', route: url()->previous());
    }

    public function signDocument()
    {
        $this->validate([
            'signature_image' => 'required|image|max:2048',
            'signer_name' => 'required|string|max:255',
            'signer_position' => 'required|string|max:255',
        ]);

        $signaturePath = $this->signature_image->store('signatures', 'public');
        $signatureHash = hash('sha256', file_get_contents($this->signature_image->getRealPath()));

        DigitalSignature::create([
            'procedure_id' => $this->procedure->id,
            'user_id' => auth()->id(),
            'signer_name' => $this->signer_name,
            'signer_position' => $this->signer_position,
            'signature_image_path' => $signaturePath,
            'signature_hash' => $signatureHash,
            'signed_at' => now(),
        ]);

        // No cambiar el estado aquí, solo agregar la firma
        $this->procedure->addHistory(auth()->id(), 'signature_added', $this->procedure->status, $this->procedure->status, 'Firma agregada por ' . $this->signer_name);

        $this->dispatch('toast', type: 'success', message: 'Firma agregada correctamente');
        $this->reset(['signature_image', 'signer_name', 'signer_position']);

        // Refrescar el componente para mostrar la nueva firma
        $this->procedure->refresh();
    }
    public function completeSignatures()
    {
        if ($this->procedure->signatures->count() === 0) {
            $this->dispatch('toast', type: 'error', message: 'Debe agregar al menos una firma');
            return;
        }

        $this->procedure->updateStatus(Procedure::STATUS_COMPLETED, auth()->id(), 'Todas las firmas completadas');
        $this->procedure->update(['signed_at' => now(), 'completed_at' => now()]);

        $this->generateCertificate($this->procedure->id);

        $this->dispatch('toast', type: 'success', message: 'Trámite completado exitosamente');
    }
    public function completeSignatureStep()
    {
        // Verificar que tenga al menos una firma
        if ($this->procedure->signatures->count() === 0) {
            $this->dispatch('toast', type: 'error', message: 'Debe agregar al menos una firma');
            return;
        }

        // Cambiar al siguiente estado (completed)
        $this->procedure->updateStatus(Procedure::STATUS_COMPLETED, auth()->id(), 'Documento firmado por todas las autoridades');
        $this->procedure->update(['completed_at' => now()]);

        $this->dispatch('toast', type: 'success', message: 'Firmas completadas, trámite finalizado');
        $this->dispatch('toast-and-redirect', type: 'success', message: 'Firmas completadas, trámite finalizado', route: route('procedures.index'));
        // return $this->redirectRoute('procedures.index');
    }

    public function completeProcedure()
    {
        $this->procedure->updateStatus(Procedure::STATUS_COMPLETED, auth()->id(), 'Trámite completado');
        $this->procedure->update(['completed_at' => now()]);

        // session()->flash('success', 'Trámite completado exitosamente');
        $this->dispatch('toast', type: 'success', message: 'Trámite completado exitosamente');
        return $this->redirectRoute('procedures.index');
    }

    public function render()
    {
        $pendingPayment = Payment::where('procedure_id', $this->procedure->id)
            ->where('is_verified', false)
            ->first();

        return view('livewire.procedures.procedure-workflow', [
            'pendingPayment' => $pendingPayment,
        ]);
    }
}
