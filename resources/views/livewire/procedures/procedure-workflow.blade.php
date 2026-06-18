<div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-slate-700 rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Información del Trámite</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-400">Estudiante</p>
                        <p class="font-medium">{{ $procedure->student_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">RUT</p>
                        <p class="font-medium">{{ $procedure->student_identification }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Tipo de certificado</p>
                        <p class="font-medium">{{ ucfirst(__(str_replace('_', ' ', $procedure->certificate_type))) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Programa</p>
                        <p class="font-medium">{{ ucfirst(__(str_replace('_', ' ', $procedure->program))) }}</p>
                    </div>
                    @if ($procedure->final_grades_average)
                        <div>
                            <p class="text-sm text-gray-400">Promedio final</p>
                            <p class="font-medium">{{ $procedure->final_grades_average }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-slate-700 rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-100 dark:text-gray-300 flex items-center gap-2">
                        <i class="bi bi-diagram-3 text-blue-500 text-2xl"></i>
                        Flujo del Trámite
                    </h3>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-green-500 shadow-sm"></div>
                            <span class="text-xs text-gray-600 dark:text-gray-400 font-medium">Completado</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div
                                class="w-3 h-3 rounded-full bg-blue-500 shadow-sm ring-2 ring-blue-200 dark:ring-blue-900">
                            </div>
                            <span class="text-xs text-gray-600 dark:text-gray-400 font-medium">Actual</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-gray-300 dark:bg-gray-600"></div>
                            <span class="text-xs text-gray-600 dark:text-gray-400 font-medium">Pendiente</span>
                        </div>
                    </div>
                </div>

                @php
                    $steps = [
                        'secretary' => ['label' => 'Secretaría', 'icon' => 'bi-building'],
                        'finance' => ['label' => 'Finanzas', 'icon' => 'bi-currency-dollar'],
                        'academic_review' => ['label' => 'Revisión Académica', 'icon' => 'bi-mortarboard'],
                        'signature' => ['label' => 'Firma Digital', 'icon' => 'bi-pen'],
                        'completed' => ['label' => 'Completado', 'icon' => 'bi-check2-circle'],
                    ];
                    $statuses = array_keys($steps);
                    $currentIndex = array_search($procedure->status, $statuses);
                    $progressPercent = round((($currentIndex + 1) / count($steps)) * 100);
                @endphp

                <div class="flex flex-col gap-6">
                    @php
                        $isObserved = $procedure->status === 'observation';
                        $statuses = array_keys($steps);

                        // Determinar el índice actual basado en el estado
                        if ($isObserved) {
                            // Si está observado, buscar el último paso con fecha de aprobación
                            $currentIndex = -1;
                            foreach ($statuses as $index => $stepKey) {
                                if ($stepKey === 'secretary' && $procedure->secretary_approved_at) {
                                    $currentIndex = $index;
                                } elseif (
                                    $stepKey === 'finance' &&
                                    isset($procedure->finance_approved_at) &&
                                    $procedure->finance_approved_at
                                ) {
                                    $currentIndex = $index;
                                } elseif ($stepKey === 'academic_review' && $procedure->academic_reviewed_at) {
                                    $currentIndex = $index;
                                } elseif ($stepKey === 'signature' && $procedure->signed_at) {
                                    $currentIndex = $index;
                                } elseif ($stepKey === 'completed' && $procedure->completed_at) {
                                    $currentIndex = $index;
                                }
                            }
                        } else {
                            $currentIndex = array_search($procedure->status, $statuses);
                        }

                        $progressPercent = $currentIndex >= 0 ? round((($currentIndex + 1) / count($steps)) * 100) : 0;
                    @endphp

                    @if ($isObserved)
                        <div class="bg-yellow-900/50 border-l-4 border-yellow-500 p-4 rounded-lg mb-2">
                            <div class="flex items-start gap-3">
                                <i class="bi bi-exclamation-triangle-fill text-yellow-500 text-xl mt-0.5"></i>
                                <div class="flex-1">
                                    <h4 class="text-yellow-400 font-semibold">TRÁMITE EN OBSERVACIÓN</h4>
                                    @if ($procedure->observed_from_stage)
                                        <p class="text-yellow-300/70 text-xs">
                                            <i class="bi bi-arrow-right"></i>
                                            Volverá a:
                                            {{ ucfirst(__(str_replace('_', ' ', $procedure->observed_from_stage))) }}
                                        </p>
                                    @endif
                                    <p class="text-yellow-300 text-sm mt-1">
                                        {{ $procedure->observations ?? 'Se requiere corregir información' }}</p>
                                    <div class="flex flex-wrap gap-4 mt-2 text-xs text-yellow-400/70">
                                        <span><i class="bi bi-clock"></i>
                                            {{ \Carbon\Carbon::parse($procedure->updated_at)->format('d/m/Y - h:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @foreach ($steps as $stepKey => $step)
                        @php
                            $stepIndex = array_search($stepKey, $statuses);

                            // Determinar si el paso está completado (tiene fecha de aprobación)
                            $hasDate = false;
                            if ($stepKey === 'secretary' && $procedure->secretary_approved_at) {
                                $hasDate = true;
                            } elseif (
                                $stepKey === 'finance' &&
                                isset($procedure->finance_approved_at) &&
                                $procedure->finance_approved_at
                            ) {
                                $hasDate = true;
                            } elseif ($stepKey === 'academic_review' && $procedure->academic_reviewed_at) {
                                $hasDate = true;
                            } elseif ($stepKey === 'signature' && $procedure->signed_at) {
                                $hasDate = true;
                            } elseif ($stepKey === 'completed' && $procedure->completed_at) {
                                $hasDate = true;
                            }

                            // Si está observado, el paso con fecha de aprobación está completado
                            $isCompleted = $hasDate;

                            // Si es 'completed', solo está completado si el trámite está completado
                            if ($stepKey === 'completed') {
                                $isCompleted = $procedure->status === 'completed';
                            }

                            // Es actual solo si NO está observado Y es el estado actual Y no está completado
                            $isCurrent =
                                !$isObserved &&
                                $procedure->status === $stepKey &&
                                $stepKey !== 'completed' &&
                                !$hasDate;

                            // Caso especial para 'completed': nunca es actual
                            if ($stepKey === 'completed') {
                                $isCurrent = false;
                            }
                        @endphp

                        <div class="flex items-start gap-4">
                            <!-- Círculo -->
                            <div class="flex-shrink-0 flex items-center justify-center w-9 h-9 rounded-full shadow-md transition-all duration-300"
                                style="background-color: {{ $isCompleted ? '#22c55e' : ($isCurrent ? '#2563eb' : '#9ca3af') }};
                {{ $isCurrent ? 'box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3);' : '' }}">

                                @if ($isCompleted)
                                    <i class="bi bi-check-lg text-white text-sm"></i>
                                @else
                                    <i class="bi {{ $step['icon'] }} text-white text-sm"></i>
                                @endif
                            </div>

                            <!-- Contenido -->
                            <div
                                class="flex-1 bg-slate-800 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 p-4">
                                <div class="flex flex-wrap justify-between items-center gap-2">
                                    <h4 class="text-base font-bold dark:text-white"
                                        style="color: {{ $isCompleted ? '#22c55e' : ($isCurrent ? '#2563eb' : '#6b7280') }}">
                                        {{ $step['label'] }}
                                    </h4>

                                    @if ($isCompleted)
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                                            <i class="bi bi-check-circle-fill mr-1"></i> COMPLETADO
                                        </span>
                                    @elseif($isCurrent)
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300">
                                            <i class="bi bi-hourglass-split mr-1"></i> EN CURSO
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-200 dark:bg-gray-600 text-gray-400 dark:text-gray-400">
                                            <i class="bi bi-clock mr-1"></i> PENDIENTE
                                        </span>
                                    @endif
                                </div>

                                @php
                                    $dateField = null;
                                    if ($stepKey === 'secretary' && $procedure->secretary_approved_at) {
                                        $dateField = $procedure->secretary_approved_at;
                                    } elseif (
                                        $stepKey === 'finance' &&
                                        isset($procedure->finance_approved_at) &&
                                        $procedure->finance_approved_at
                                    ) {
                                        $dateField = $procedure->finance_approved_at;
                                    } elseif ($stepKey === 'academic_review' && $procedure->academic_reviewed_at) {
                                        $dateField = $procedure->academic_reviewed_at;
                                    } elseif ($stepKey === 'signature' && $procedure->signed_at) {
                                        $dateField = $procedure->signed_at;
                                    } elseif ($stepKey === 'completed' && $procedure->completed_at) {
                                        $dateField = $procedure->completed_at;
                                    }
                                @endphp

                                @if ($dateField)
                                    <div class="mt-3 pt-2">
                                        <div
                                            class="inline-flex items-center gap-1.5 text-xs bg-slate-700 px-3 py-1.5 rounded-full shadow-sm">
                                            <i class="bi bi-calendar-check text-green-500 text-sm"></i>
                                            <span class="text-gray-300">
                                                {{ \Carbon\Carbon::parse($dateField)->format('d/m/Y - h:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Barra de progreso -->
                <div class="mt-8 pt-5 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Progreso del trámite</span>
                        <span
                            class="text-sm font-bold px-3 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300">{{ $progressPercent }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden shadow-inner">
                        <div class="h-2.5 rounded-full transition-all duration-500"
                            style="width: {{ $progressPercent }}%; background: linear-gradient(90deg, #2563eb, #22c55e);">
                        </div>
                    </div>
                </div>
            </div>


            <div class="bg-slate-700 rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Historial de Movimientos</h3>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach ($procedure->histories()->latest()->get() as $history)
                        <div class="border-l-4 border-blue-400 pl-3 py-2 bg-slate-800">
                            <p class="text-sm font-medium">{{ ucfirst(__(str_replace('_', ' ', $history->action))) }}
                            </p>
                            <p class="text-xs text-gray-400">{{ $history->created_at->format('d/m/Y H:i') }} -
                                {{ $history->user->name }}</p>
                            @if ($history->comments)
                                <p class="text-sm text-gray-500 mt-1">{{ $history->comments }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="space-y-6">
            <!-- ===== SECRETARÍA ===== -->
            @if ($procedure->status === 'secretary' && auth()->user()->can('review procedures'))
                <div class="bg-slate-700 rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4">Secretaría</h3>
                    <p class="text-sm text-gray-400 mb-4">Verifique la documentación del estudiante</p>
                    <button wire:click="approveSecretary"
                        class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Aprobar y enviar a Finanzas
                    </button>
                    <hr class="my-4">
                    <h4 class="font-medium mb-2">Enviar a Observación</h4>
                    <textarea wire:model="observation" rows="2" class="w-full px-4 py-2 border rounded-lg bg-slate-800 text-white"
                        placeholder="Motivo de la observación..."></textarea>
                    @error('observation')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                    <button wire:click="rejectToObservation"
                        class="w-full mt-2 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                        Enviar a Observación
                    </button>
                </div>
            @endif

            <!-- ===== FINANZAS ===== -->
            @if ($procedure->status === 'finance' && auth()->user()->can('verify payments'))
                <div class="bg-slate-700 rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4">Finanzas</h3>
                    <p class="text-sm text-gray-400 mb-4">Verifique el comprobante de pago</p>
                    <input type="number" wire:model="payment_amount" step="0.01"
                        class="w-full px-4 py-2 border rounded-lg mb-3 bg-slate-800 text-white" placeholder="Monto">
                    <select wire:model="payment_method"
                        class="w-full px-4 py-2 border rounded-lg mb-3 bg-slate-800 text-white">
                        <option value="">Método de pago</option>
                        <option value="credit_card">Tarjeta de crédito</option>
                        <option value="debit_card">Tarjeta de débito</option>
                        <option value="bank_transfer">Transferencia bancaria</option>
                        <option value="cash">Efectivo</option>
                    </select>
                    <div class="flex flex-col gap-2">
                        <button wire:click="viewFile({{ $procedure->id }})"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Ver comprobante
                        </button>
                        @error('payment_amount')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                        @error('payment_method')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                        <button wire:click="approveFinance"
                            class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Verificar Pago y enviar a Académica
                        </button>
                    </div>

                    <hr class="my-4">
                    <h4 class="font-medium mb-2">Enviar a Observación</h4>
                    <textarea wire:model="observation" rows="2" class="w-full px-4 py-2 border rounded-lg bg-slate-800 text-white"
                        placeholder="Motivo de la observación..."></textarea>
                    <button wire:click="rejectToObservation"
                        class="w-full mt-2 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                        Enviar a Observación
                    </button>
                </div>
            @endif

            <!-- ===== REVISIÓN ACADÉMICA ===== -->
            @if ($procedure->status === 'academic_review' && auth()->user()->can('review academic'))
                <div class="bg-slate-700 rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-2">Revisión Académica</h3>

                    @if ($procedure->certificate_type === 'language_certificate' || $procedure->certificate_type === 'study_certificate')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Períodos cursados y aprobados
                            </label>
                            <input type="number" wire:model="approved_periods"
                                class="w-full px-4 py-2 border rounded-lg bg-slate-800 text-white" placeholder="Ej: 4"
                                min="1" max="12">
                            @error('approved_periods')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror

                            @if ($approved_periods)
                                <p class="text-xs text-green-400 mt-1">
                                    <i class="bi bi-check-circle"></i> Se certificarán {{ $approved_periods }}
                                    períodos
                                </p>
                            @endif
                        </div>
                    @endif

                    @if ($procedure->certificate_type === 'academic_record')
                        <div class="mt-4 pt-4 border-t border-gray-600">
                            <div class="mb-4 p-4 bg-slate-800 rounded-lg">
                                <p class="text-sm text-gray-300 mb-2">
                                    <i class="bi bi-file-earmark-excel"></i> Cargar notas desde Excel
                                </p>
                                <p class="text-xs text-gray-400 mb-3">
                                    Formato: Columna A = Período, Columna B = Nota (1.0 - 5.0)
                                </p>
                                <input type="file" wire:model="grades_file"
                                    class="w-full px-3 py-2 border rounded-lg bg-slate-700 text-white text-sm">
                                @error('grades_file')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                                <button wire:click="uploadGrades({{ $procedure->id }})"
                                    class="mt-2 px-3 py-1 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                                    <i class="bi bi-upload"></i> Cargar Notas
                                </button>

                                @php
                                    $gradesData = $procedure->grades_data ?? null;
                                    $gradesCount = is_array($gradesData['grades'] ?? null)
                                        ? count($gradesData['grades'])
                                        : 0;
                                    $gradesAverage = $gradesData['average'] ?? 0;
                                @endphp

                                @if ($gradesCount > 0)
                                    <div class="mt-3 p-2 bg-green-900 rounded-lg">
                                        <p class="text-xs text-green-300">
                                            <i class="bi bi-check-circle"></i> {{ $gradesCount }} notas cargadas -
                                            Promedio: {{ number_format($procedure->final_grades_average, 2) }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <hr class="my-4 border-gray-600">
                        </div>
                    @endif
                    @if ($procedure->certificate_type === 'language_certificate')
                        <div>
                            <p class="text-sm text-gray-300 mb-2">Ingrese el promedio final</p>
                            <input type="number" wire:model="final_grades_average" step="0.01" min="0"
                                max="5" class="w-full px-4 py-2 border rounded-lg mb-3 bg-slate-800 text-white"
                                placeholder="Promedio final (0-5)">
                            @error('final_grades_average')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <button wire:click="approveAcademic"
                        class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        @if ($procedure->certificate_type === 'study_certificate')
                            Registrar períodos y enviar a Firmas
                        @else
                            Registrar períodos y promedio, enviar a Firmas
                        @endif
                    </button>
                </div>
            @endif

            <!-- ===== FIRMAS ===== -->
            @if ($procedure->status === 'signature' && auth()->user()->can('sign procedures'))
                <div class="bg-slate-700 rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4">Firmas Digitales</h3>

                    @if ($procedure->signatures->count() > 0)
                        <div class="mb-4 space-y-2">
                            <p class="text-sm text-gray-400">Firmas registradas:</p>
                            @foreach ($procedure->signatures as $signature)
                                <div class="bg-slate-800 rounded-lg p-2 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium">{{ $signature->signer_name }}</p>
                                        <p class="text-xs text-gray-400">{{ $signature->signer_position }}</p>
                                    </div>
                                    <i class="bi bi-check-circle-fill text-green-500"></i>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="border-t border-gray-600 pt-4">
                        <h4 class="font-medium mb-3">Agregar nueva firma</h4>
                        <input type="text" wire:model="signer_name"
                            class="w-full px-4 py-2 border rounded-lg mb-2 bg-slate-800 text-white"
                            placeholder="Nombre del firmante">
                        <input type="text" wire:model="signer_position"
                            class="w-full px-4 py-2 border rounded-lg mb-2 bg-slate-800 text-white"
                            placeholder="Cargo">
                        <input type="file" wire:model="signature_image" accept="image/*"
                            class="w-full px-4 py-2 border rounded-lg mb-3 bg-slate-800 text-white">
                        <button wire:click="signDocument"
                            class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 mb-3">
                            Agregar Firma
                        </button>
                    </div>

                    @if ($procedure->signatures->count() > 0)
                        <button wire:click="completeSignatures"
                            class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Finalizar Firmas y Completar Trámite
                        </button>
                    @endif
                </div>
            @endif

            <!-- ===== OBSERVACIÓN ===== -->
            @if ($procedure->status === 'observation')
                @if (auth()->user()->can('review procedures') || auth()->user()->can('review own procedures'))
                    <div class="bg-slate-700 rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4">Observación</h3>
                        <p class="text-sm text-gray-300 mb-4"><strong>Motivo:</strong>
                            {{ $procedure->observations }}</p>

                        @if ($procedure->observed_from_stage)
                            <p class="text-sm text-yellow-400/70 mb-4">
                                <i class="bi bi-arrow-right"></i>
                                Volverá a: {{ ucfirst(__(str_replace('_', ' ', $procedure->observed_from_stage))) }}
                            </p>
                        @endif

                        <div class="flex flex-col gap-3">
                            <button wire:click="approveFromObservation"
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                <i class="bi bi-check2"></i> Corregir y Continuar
                            </button>

                            @canany(['edit procedures', 'edit own procedures'])
                                <a href="{{ route('procedures.edit', $procedure->id) }}"
                                    class="w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    <i class="bi bi-pencil"></i> Editar Trámite
                                </a>
                            @endcanany
                        </div>
                    </div>
                @endif
            @endif

            <!-- ===== COMPLETADO ===== -->
            @if ($procedure->status === 'completed')
                @role('admin')
                    <div class="bg-slate-700 rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4">Volver a cargar notas</h3>
                        <div class="mb-4 p-4 bg-slate-800 rounded-lg">
                            <p class="text-sm text-gray-300 mb-2">
                                <i class="bi bi-file-earmark-excel"></i> Cargar notas desde Excel
                            </p>
                            <p class="text-xs text-gray-400 mb-3">
                                Formato: Columna A = Período, Columna B = Nota (1.0 - 5.0)
                            </p>
                            <input type="file" wire:model="grades_file"
                                class="w-full px-3 py-2 border rounded-lg bg-slate-700 text-white text-sm">
                            @error('grades_file')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                            <button wire:click="uploadGrades({{ $procedure->id }})"
                                class="mt-2 px-3 py-1 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                                <i class="bi bi-upload"></i> Cargar Notas
                            </button>

                            @if (session()->has('grades_data_' . $procedure->id))
                                <div class="mt-3 p-2 bg-green-900 rounded-lg">
                                    <p class="text-xs text-green-300">
                                        <i class="bi bi-check-circle"></i> Notas cargadas -
                                        Promedio: {{ session()->get('grades_average_' . $procedure->id) }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endrole

                <!-- Completado para admin -->
                @role('admin')
                    <div class="bg-slate-700 rounded-xl shadow-md p-6 text-center">
                        <i class="bi bi-check2-circle text-5xl text-green-600 mb-3"></i>
                        <p class="text-gray-400 mb-4">Trámite completado exitosamente</p>
                        @if ($procedure->certificate_file_path)
                            <button wire:click="downloadCertificate({{ $procedure->id }})"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-700 text-gray-100 rounded-lg hover:bg-blue-800 transition">
                                <i class="bi bi-download"></i> Descargar Certificado
                            </button>
                            <button wire:click="delete({{ $procedure->id }})"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-red-700 text-gray-100 rounded-lg hover:bg-red-800 transition">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        @else
                            <button wire:click="generateCertificate({{ $procedure->id }})"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-700 text-gray-100 rounded-lg hover:bg-blue-800 transition">
                                <i class="bi bi-file-earmark-plus-fill"></i> Generar Certificado
                            </button>
                        @endif
                    </div>
                @endrole

                <!-- Completado para otros roles -->
                @if (auth()->user()->can('review own procedures') && !auth()->user()->hasRole('admin'))
                    <div class="bg-slate-700 rounded-xl shadow-md p-6 text-center">
                        <i class="bi bi-check2-circle text-5xl text-green-600 mb-3"></i>
                        <p class="text-gray-400 mb-4">Trámite completado exitosamente</p>
                        @if ($procedure->certificate_file_path)
                            <button wire:click="downloadCertificate({{ $procedure->id }})"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-700 text-gray-100 rounded-lg hover:bg-blue-800 transition">
                                <i class="bi bi-download"></i> Descargar Certificado
                            </button>
                        @endif
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('open-file', (data) => {
            window.open(data.url, '_blank');
        });
    });
</script>
