<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificar Certificado - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full p-8">
            <div class="text-center mb-6">
                <i class="bi bi-check-circle text-green-500 text-6xl"></i>
                <h1 class="text-2xl font-bold text-gray-800 mt-2">Certificado Válido</h1>
                <p class="text-gray-600">Este documento ha sido verificado oficialmente</p>
            </div>
            
            <div class="border-t border-gray-200 pt-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500">Estudiante</p>
                        <p class="font-medium">{{ $procedure->student_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Identificación</p>
                        <p class="font-medium">{{ $procedure->student_identification }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tipo de certificado</p>
                        <p class="font-medium">{{ ucfirst(__(str_replace('_', ' ', $procedure->certificate_type))) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Programa</p>
                        <p class="font-medium">{{ ucfirst(__(str_replace('_', ' ', $procedure->program))) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Fecha de emisión</p>
                        <p class="font-medium">{{ $procedure->completed_at ? $procedure->completed_at->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Hash</p>
                        <p class="font-medium break-words whitespace-normal">{{ substr($procedure->document_hash, 0, 16) }}...{{ substr($procedure->document_hash, -16) }}</p>

                        
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Firmas oficiales</h3>
                <div class="flex flex-wrap gap-4">
                    @foreach($procedure->signatures as $signature)
                    <div class="text-center">
                        <div class="w-32 h-12 bg-gray-100 rounded flex items-center justify-center mb-1">
                            @if($signature->signature_image_path && file_exists(storage_path('app/public/' . $signature->signature_image_path)))
                                <img src="{{ asset('storage/' . $signature->signature_image_path) }}" class="max-h-10 max-w-full">
                            @else
                                <i class="bi bi-pen text-gray-400 text-xl"></i>
                            @endif
                        </div>
                        <p class="text-xs font-medium">{{ $signature->signer_name }}</p>
                        <p class="text-xs text-gray-500">{{ $signature->signer_position }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="mt-6 text-center text-xs text-gray-500">
                <i class="bi bi-shield-check"></i> Documento verificado electrónicamente
            </div>
        </div>
    </div>
</body>
</html>