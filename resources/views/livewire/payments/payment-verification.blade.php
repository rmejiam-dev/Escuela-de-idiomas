<div>
    <div class="bg-slate-700 rounded-xl shadow-md">
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-xl font-semibold text-white">Verificar Pagos Pendientes</h2>
        </div>

        <div class="p-6">
            <div class="flex flex-wrap gap-4 mb-6">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" wire:model.live.debounce.300ms="search" 
                           placeholder="Buscar por nombre o cédula..." 
                           class="w-full px-4 py-2 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-800 text-white">
                </div>
                <div>
                    <select wire:model.live="perPage" class="px-4 py-2 border border-gray-600 rounded-lg bg-slate-800 text-white">
                        <option value="10">10 por página</option>
                        <option value="25">25 por página</option>
                        <option value="50">50 por página</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-slate-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Estudiante</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Cédula</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Monto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Método</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Referencia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Fecha pago</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Comprobante</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($payments as $payment)
                        <tr x-data="{ 
                            showModal: false, 
                            modalType: '',
                            loading: false
                        }" class="hover:bg-slate-800">
                            <td class="px-6 py-4 font-medium text-white">{{ $payment->procedure->student_name }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ $payment->procedure->student_identification }}</td>
                            <td class="px-6 py-4 text-gray-300">${{ number_format($payment->amount, 2) }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ $payment->payment_method }}</td>
                            <td class="px-6 py-4 text-sm text-gray-300">{{ $payment->reference_number }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">
                                @if($payment->receipt_path)
                                    <a href="{{ Storage::url($payment->receipt_path) }}" target="_blank" 
                                       class="text-blue-400 hover:text-blue-300">
                                        <i class="bi bi-file-earmark-text"></i> Ver
                                    </a>
                                @else
                                    <span class="text-gray-500">Sin comprobante</span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5 whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <button @click="showModal = true; modalType = 'verify'"
                                            class="px-2 py-0.5 border border-green-600 text-green-400 text-xs rounded hover:bg-green-900 hover:text-white transition">
                                        Verificar
                                    </button>
                                    
                                    <button @click="showModal = true; modalType = 'reject'"
                                            class="px-2 py-0.5 border border-red-600 text-red-400 text-xs rounded hover:bg-red-900 hover:text-white transition">
                                        Rechazar
                                    </button>
                                </div>

                                <!-- Modal -->
                                <div x-show="showModal" 
                                     x-cloak
                                     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60"
                                     style="display: none;">
                                    
                                    <div class="bg-gray-800 rounded-xl shadow-2xl max-w-md w-full mx-4 border border-gray-700">
                                        <div class="p-6">
                                            <div class="flex items-center justify-center mb-4">
                                                <div class="w-16 h-16 rounded-full flex items-center justify-center"
                                                     :class="modalType === 'verify' ? 'bg-green-900' : 'bg-red-900'">
                                                    <i class="bi text-3xl text-white"
                                                       :class="modalType === 'verify' ? 'bi-check-circle' : 'bi-exclamation-triangle'"></i>
                                                </div>
                                            </div>
                                            
                                            <h3 class="text-lg font-semibold text-center text-white mb-2"
                                                x-text="modalType === 'verify' ? 'Verificar Pago' : 'Rechazar Pago'"></h3>
                                            
                                            <p class="text-center text-gray-300 mb-6"
                                                x-text="modalType === 'verify' ? '¿Estás seguro de que deseas verificar este pago?' : '¿Estás seguro de que deseas rechazar este pago?'">
                                            </p>
                                            
                                            <div class="flex justify-end gap-3">
                                                <button @click="showModal = false"
                                                        class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition">
                                                    Cancelar
                                                </button>
                                                <button @click="
                                                    loading = true;
                                                    showModal = false;
                                                    if(modalType === 'verify') {
                                                        Livewire.dispatch('verify', { id: {{ $payment->id }} });
                                                    } else {
                                                        Livewire.dispatch('reject', { id: {{ $payment->id }} });
                                                    }
                                                "
                                                        class="px-4 py-2 text-white rounded-lg transition"
                                                        :class="modalType === 'verify' ? 'bg-green-800 hover:bg-green-900' : 'bg-red-800 hover:bg-red-900'">
                                                    <span x-text="modalType === 'verify' ? 'Verificar' : 'Rechazar'"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-400">
                                No hay pagos pendientes por verificar
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>