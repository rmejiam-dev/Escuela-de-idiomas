<div>
    <div class="bg-slate-700 rounded-xl shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold">{{ $procedureId ? 'Editar Trámite' : 'Nuevo Trámite' }}</h2>
        </div>

        <form wire:submit="save" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2">Tipo de certificado *</label>
                    <select wire:model="certificate_type" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-700">
                        <option value="">Seleccionar...</option>
                        <option value="academic_record">Registro Académico</option>
                        <option value="language_certificate">Certificado de Idioma</option>
                        <option value="study_certificate">Constancia de Estudios</option>
                    </select>
                    @error('certificate_type')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Nombre del estudiante *</label>
                    <input type="text" wire:model="student_name"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('student_name')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Cédula del estudiante *</label>
                    <input type="text" wire:model="student_identification"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('student_identification')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Fecha de nacimiento *</label>
                    <input type="date" wire:model="birth_date"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 ">
                    @error('birth_date')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Programa *</label>
                    <select wire:model="program"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-700">
                        <option value="">Seleccionar...</option>
                        <option value="english">Inglés</option>
                        <option value="french">Francés</option>
                        <option value="german">Alemán</option>
                        <option value="portuguese">Portugués</option>
                        <option value="italian">Italiano</option>
                    </select>
                    @error('program')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Comprobante de pago *</label>
                    <input type="file" wire:model="payment_receipt" accept="image/jpeg,image/png,application/pdf"
                        class="w-full px-4 py-2 border rounded-lg bg-slate-800 text-white file:mr-2 file:py-1 file:px-3 file:rounded-lg file:bg-slate-600 file:text-white file:border-0 hover:file:bg-slate-500">

                    @error('payment_receipt')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                    @if ($payment_receipt && is_string($payment_receipt))
                        @php
                            $fileName = basename($payment_receipt);
                        @endphp
                        <div class="mb-3 p-3 bg-slate-800 rounded-lg border border-slate-600 flex items-center gap-3">
                            <i class="bi bi-file-earmark-text text-blue-400 text-xl"></i>
                            <div class="flex-1">
                                <p class="text-sm text-white">{{ $fileName }}</p>
                                <p class="text-xs text-slate-400">Archivo actual</p>
                            </div>
                            <a href="{{ Storage::url($payment_receipt) }}" target="_blank"
                                class="text-blue-400 hover:text-blue-300 text-sm">
                                Ver <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                <a href="{{ route('procedures.index') }}"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-500">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800">
                    {{ $procedureId ? 'Actualizar' : 'Crear Trámite' }}
                </button>
            </div>
        </form>
    </div>
</div>
