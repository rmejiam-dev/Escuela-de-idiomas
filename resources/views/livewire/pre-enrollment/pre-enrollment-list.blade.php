<div>
    <div class="bg-slate-700 rounded-xl shadow-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Preinscripciones</h2>
                <a href="{{ route('pre-enrollment.public') }}" target="_blank"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="bi bi-plus-circle"></i> Formulario Público
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="flex flex-wrap gap-4 mb-6">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Buscar por nombre, email o cédula..."
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select wire:model.live="status"
                        class="px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-700">
                        <option value="">Todos los estados</option>
                        <option value="pending">Pendiente</option>
                        <option value="contacted">Contactado</option>
                        <option value="enrolled">Inscrito</option>
                        <option value="rejected">Rechazado</option>
                    </select>
                </div>
                <div>
                    <select wire:model.live="perPage"
                        class="px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-700">
                        <option value="10">10 por página</option>
                        <option value="25">25 por página</option>
                        <option value="50">50 por página</option>
                        <option value="100">100 por página</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-gray-200">
                    <thead class="bg-slate-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Teléfono</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Programa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">IP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($preEnrollments as $preEnrollment)
                            <tr class="hover:bg-slate-800">
                                <td class="px-6 py-4 font-medium">{{ $preEnrollment->full_name }}</td>
                                <td class="px-6 py-4">{{ $preEnrollment->email }}</td>
                                <td class="px-6 py-4">{{ $preEnrollment->phone }}</td>
                                <td class="px-6 py-4">{{ ucfirst($preEnrollment->program_interest) }}</td>
                                <td class="px-6 py-4 text-sm">{{ $preEnrollment->request_ip }}</td>
                                <td class="px-6 py-4">
                                    <select wire:change="updateStatus({{ $preEnrollment->id }}, $event.target.value)"
                                        class="text-sm rounded-lg px-2 py-1 border focus:ring-blue-500 bg-slate-700">
                                        <option value="pending"
                                            {{ $preEnrollment->status == 'pending' ? 'selected' : '' }}>Pendiente
                                        </option>
                                        <option value="contacted"
                                            {{ $preEnrollment->status == 'contacted' ? 'selected' : '' }}>Contactado
                                        </option>
                                        <option value="enrolled"
                                            {{ $preEnrollment->status == 'enrolled' ? 'selected' : '' }}>Inscrito
                                        </option>
                                        <option value="rejected"
                                            {{ $preEnrollment->status == 'rejected' ? 'selected' : '' }}>Rechazado
                                        </option>
                                    </select>
                                </td>
                                <td class="px-6 py-4">{{ $preEnrollment->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <button wire:click="delete({{ $preEnrollment->id }})"
                                        wire:confirm="¿Eliminar esta preinscripción?"
                                        class="text-red-600 hover:text-red-900">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">No hay preinscripciones
                                    registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> <!-- Cierre del div overflow-x-auto -->
            
            <!-- Paginador fuera de la tabla y del overflow-x-auto -->
            <div class="mt-6">
                {{ $preEnrollments->links() }}
            </div>
        </div> <!-- Cierre del div p-6 -->
    </div> <!-- Cierre del div bg-slate-700 -->
</div>