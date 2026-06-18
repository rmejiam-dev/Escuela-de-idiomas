<div>
    <div class="bg-slate-700 rounded-xl shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold">Pagos Registrados</h2>
        </div>

        <div class="p-6">
            <div class="flex flex-wrap gap-4 mb-6">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar estudiante..."
                        class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div>
                    <select wire:model.live="verifiedFilter" class="px-4 py-2 border rounded-lg bg-slate-700">
                        <option value="">Todos</option>
                        <option value="1">Verificados</option>
                        <option value="0">Pendientes</option>
                    </select>
                </div>
                <div>
                    <select wire:model.live="perPage" class="px-4 py-2 border rounded-lg bg-slate-700">
                        <option value="10">10 por página</option>
                        <option value="25">25 por página</option>
                        <option value="50">50 por página</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-gray-200">
                    <thead class="bg-slate-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Monto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Método</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Referencia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($payments as $payment)
                            <tr class="hover:bg-slate-800">
                                <td class="px-6 py-4">${{ number_format($payment->amount, 2) }}</td>
                                <td class="px-6 py-4">{{ ucfirst(__(str_replace('_', ' ', $payment->payment_method))) }}
                                </td>
                                <td class="px-6 py-4 text-sm">{{ $payment->reference_number }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full {{ $payment->is_verified ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $payment->is_verified ? 'Verificado' : 'Pendiente' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $payment->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <!-- Ver comprobante -->
                                        @if ($payment->receipt_path)
                                            <a href="{{ Storage::url($payment->receipt_path) }}" target="_blank"
                                                class="text-blue-400 hover:text-blue-300 transition"
                                                title="Ver comprobante">
                                                <i class="bi bi-eye text-lg"></i>
                                            </a>
                                        
                                        @endif

                                        <!-- Ver workflow del trámite -->
                                        <a href="{{ route('procedures.workflow', $payment->procedure_id) }}"
                                            class="text-purple-400 hover:text-purple-300 transition"
                                            title="Ver flujo del trámite">
                                            <i class="bi bi-diagram-3 text-lg"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
