<div class="text-gray-200">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 ">
        <div class="bg-slate-700 rounded-full shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-12 h-12 bg-blue-500 rounded-full">
                    <i class="bi bi-people text-xl text-blue-800"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-100">Usuarios</p>
                    <p class="text-2xl text-gray-300 font-bold">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        <div class="bg-slate-700 rounded-full shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-12 h-12 bg-green-500 rounded-full">
                    <i class="bi bi-files text-2xl text-green-800"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm ">Total Trámites</p>
                    <p class="text-2xl text-gray-300 font-bold">{{ $totalProcedures }}</p>
                </div>
            </div>
        </div>

        <div class="bg-slate-700 rounded-full shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-12 h-12 bg-yellow-500 rounded-full">
                    <i class="bi bi-clock-history text-2xl text-yellow-800"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm ">Pendientes</p>
                    <p class="text-2xl text-gray-300 font-bold">{{ $pendingProcedures }}</p>
                </div>
            </div>
        </div>

        <div class="bg-slate-700 rounded-full shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-12 h-12 bg-purple-500 rounded-full">
                    <i class="bi bi-check2-circle text-2xl text-purple-800"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm ">Completados</p>
                    <p class="text-2xl text-gray-300 font-bold">{{ $completedProcedures }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-slate-700 rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Trámites por Mes ({{ date('Y') }})
            </h3>

            @php
                $maxCount = max($monthlyData);
                $maxHeight = 200;
            @endphp

            <div class="h-64 flex items-end space-x-2">
                @foreach ($monthlyData as $month => $count)
                    @php
                        $barHeight = $maxCount > 0 ? max(4, ($count / $maxCount) * $maxHeight) : 4;
                    @endphp
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-blue-500 rounded-t transition-all hover:bg-blue-600"
                            style="height: {{ $barHeight }}px;"></div>
                        <span
                            class="text-xs text-gray-600 dark:text-gray-400 mt-2">{{ __(date('M', mktime(0, 0, 0, $month, 1))) }}</span>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-slate-700 rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4">Estado de Trámites</h3>
            <div class="space-y-3">
                @foreach ($statusCounts as $status => $count)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="capitalize">{{  __(str_replace('_', ' ', $status)) }}</span>
                            <span class="font-semibold">{{ $count }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-800 h-2 rounded-full"
                                style="width: {{ $totalProcedures > 0 ? ($count / $totalProcedures) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="bg-slate-700 rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Trámites Recientes</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium  uppercase">Solicitante</th>
                        <th class="px-6 py-3 text-left text-xs font-medium  uppercase">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium  uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium  uppercase">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($recentProcedures as $procedure)
                        <tr class="hover:bg-slate-800">
                            <td class="px-6 py-4">{{ $procedure->student_name }}</td>
                            <td class="px-6 py-4">{{ ucfirst(__(str_replace('_', ' ', $procedure->certificate_type))) }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-xs rounded-full 
                                {{ $procedure->status === 'completed' ? 'bg-green-500 text-green-800' : 'bg-yellow-500 text-yellow-800' }}">
                                    {{ ucfirst(__(str_replace('_', ' ', $procedure->status))) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $procedure->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
