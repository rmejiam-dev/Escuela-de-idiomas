<?php

namespace App\Http\Controllers;

use App\Models\Procedure;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function download($procedureId)
    {
        $procedure = Procedure::with('signatures')->findOrFail($procedureId);

        return view('pdf.certificate', ['procedure' => $procedure]);
    }
    // public function downloadCertificate($id)
    // {
    //     $procedure = Procedure::with('signatures')->findOrFail($id);
    //     $fileName = 'certificates/certificado_' . $procedure->id . '_' . date('Ymd_His') . '.pdf';
        
    //     $procedure->update([
    //         'certificate_file_path' => $fileName
    //     ]);

    //     $pdf = Pdf::loadView('pdf.certificate', [
    //         'procedure' => $procedure
    //     ]);

    //     Storage::disk('public')->put($fileName, $pdf->output());
    //     return $pdf->download('certificado_' . $procedure->id . '.pdf');
    // }
}
