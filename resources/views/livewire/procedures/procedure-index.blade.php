<div>
    <div class="bg-slate-700 rounded-xl shadow-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Trámites</h2>
                @can('create procedures')
                    <a href="{{ route('procedures.create') }}"
                        class="px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800">
                        <i class="bi bi-plus-circle"></i> Nuevo Trámite
                    </a>
                @endcan
            </div>
        </div>

        <div class="p-6">
            <div class="flex flex-wrap gap-4 mb-6">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Buscar por nombre, cédula o tipo..."
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select wire:model.live="status"
                        class="px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-700">
                        <option value="">Todos los estados</option>
                        <option value="secretary">Secretaría</option>
                        <option value="finance">Finanzas</option>
                        <option value="academic_review">Revisión Académica</option>
                        <option value="signature">Firma</option>
                        <option value="observation">Observación</option>
                        <option value="completed">Completado</option>
                    </select>
                </div>
                <div>
                    <select wire:model.live="perPage"
                        class="px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-700">
                        <option value="10">10 por página</option>
                        <option value="25">25 por página</option>
                        <option value="50">50 por página</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-slate-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium">Estudiante</th>
                            <th class="px-6 py-3 text-left text-xs font-medium">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium">Programa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($procedures as $procedure)
                            <tr class="hover:bg-slate-800">
                                {{-- onclick="window.location='{{ route('procedures.workflow', $procedure) }}'"> --}}
                                <td class="px-6 py-4">
                                    <div class="font-medium">{{ $procedure->student_name }}</div>
                                    <div class="text-xs text-gray-400">
                                        {{ 'RUT: ' . $procedure->student_identification }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ ucfirst(__(str_replace('_', ' ', $procedure->certificate_type))) }}</td>
                                <td class="px-6 py-4">{{ ucfirst(__(str_replace('_', ' ', $procedure->program))) }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full 
                                @if ($procedure->status === 'completed') bg-green-100 text-green-800
                                @elseif($procedure->status === 'observation') bg-red-100 text-red-800
                                @elseif($procedure->status === 'reception') bg-gray-100 text-gray-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst(__(str_replace('_', ' ', $procedure->status))) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $procedure->received_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 space-x-2">

                                    <!-- Editar -->
                                    @if ($procedure->status === 'observation')                                        
                                    @if (auth()->user()->can('edit procedures') ||
                                            (auth()->user()->can('edit own procedures') &&
                                                $procedure->user_id === auth()->id() ))
                                        <a href="{{ route('procedures.edit', $procedure->id) }}"
                                            class="text-yellow-500 hover:text-yellow-400 transition"
                                            title="Editar trámite">
                                            <i class="bi bi-pencil-square text-lg"></i>
                                        </a>
                                    @endif
                                    @endif

                                    <!-- Workflow -->
                                    @canany(['review procedures', 'view all procedures', 'review own procedures'])
                                        <a href="{{ route('procedures.workflow', $procedure->id) }}"
                                            class="text-blue-500 hover:text-blue-400 transition"
                                            title="Ver flujo del trámite">
                                            <i class="bi bi-diagram-3 text-lg"></i>
                                        </a>
                                    @endcanany

                                    <!-- Descargar -->
                                    @if ($procedure->status === 'completed')
                                        <button wire:click="downloadCertificate({{ $procedure->id }})"
                                            class="text-green-500 hover:text-green-400 transition"
                                            title="Descargar certificado">
                                            <i class="bi bi-download text-lg"></i>
                                        </button>
                                    @endif

                                    {{-- <div x-data="{ tooltip: false }" class="relative inline-block">
                                        <a href="{{ route('procedures.workflow', $procedure) }}"
                                            @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                            class="text-blue-600 hover:text-blue-900">
                                            <i class="bi bi-diagram-3 text-lg"></i>
                                        </a>
                                        <div x-show="tooltip" x-cloak
                                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap z-50"
                                            style="display: none;">
                                            Ver flujo del trámite
                                        </div>
                                    </div>

                                    @if ($procedure->status === 'completed')
                                        <div x-data="{ tooltip: false }" class="relative inline-block">
                                            <button wire:click="downloadCertificate({{ $procedure->id }})"
                                                @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                                class="text-green-600 hover:text-green-900">
                                                <i class="bi bi-download text-lg"></i>
                                            </button>
                                            <div x-show="tooltip" x-cloak
                                                class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap z-50"
                                                style="display: none;">
                                                Descargar certificado
                                            </div>
                                        </div>
                                    @endif --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay trámites
                                    registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> <!-- ← Cierre correcto del div overflow-x-auto -->

            <div class="mt-6">
                {{ $procedures->links() }}
            </div>
        </div>
    </div>
</div>
