<div>
    <div class="bg-slate-700 rounded-xl shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold">Trámites para Revisión</h2>
        </div>

        <div class="p-6">
            <div class="flex flex-wrap gap-4 mb-6">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar estudiante..." class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div>
                    <select wire:model.live="statusFilter" class="px-4 py-2 border rounded-lg bg-slate-700">
                        <option value="">Todos los estados</option>
                        <option value="reception">Recepción</option>
                        <option value="secretary">Secretaría</option>
                        <option value="academic_review">Revisión Académica</option>
                        <option value="observation">Observación</option>
                    </select>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($procedures as $procedure)
                <div class="border rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-lg">{{ $procedure->student_name }}</h3>
                            <p class="text-sm text-gray-400">RUT: {{ $procedure->student_identification }}</p>
                            <p class="text-sm">Tipo: {{ ucfirst(__($procedure->certificate_type)) }}</p>
                            <p class="text-sm">Programa: {{ ucfirst(__($procedure->program)) }} - Período {{ $procedure->study_period }}</p>
                            <span class="inline-block mt-2 px-2 py-1 text-xs rounded-full 
                                @if($procedure->status === 'reception') bg-gray-100 text-gray-800
                                @elseif($procedure->status === 'secretary') bg-blue-100 text-blue-800
                                @elseif($procedure->status === 'academic_review') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst(__($procedure->status)) }}
                            </span>
                        </div>
                        <a href="{{ route('procedures.workflow', $procedure) }}" class="px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800">
                            Revisar
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $procedures->links() }}
            </div>
        </div>
    </div>
</div>