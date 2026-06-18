<div>
    <!-- Tarjetas de resumen -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-slate-800 rounded-xl shadow-md p-5 border border-slate-700 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400">Usuarios</p>
                    <p class="text-2xl font-bold text-white">{{ $totalUsers }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-900 rounded-full flex items-center justify-center">
                    <i class="bi bi-people text-xl text-blue-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 rounded-xl shadow-md p-5 border border-slate-700 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400">Trámites</p>
                    <p class="text-2xl font-bold text-white">{{ $totalProcedures }}</p>
                </div>
                <div class="w-12 h-12 bg-green-900 rounded-full flex items-center justify-center">
                    <i class="bi bi-files text-xl text-green-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 rounded-xl shadow-md p-5 border border-slate-700 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400">Preinscripciones</p>
                    <p class="text-2xl font-bold text-white">{{ $totalPreEnrollments }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-900 rounded-full flex items-center justify-center">
                    <i class="bi bi-journal-text text-xl text-yellow-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 rounded-xl shadow-md p-5 border border-slate-700 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400">Ingresos {{ $year }}</p>
                    <p class="text-2xl font-bold text-green-400">${{ number_format($yearlyRevenue, 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-900 rounded-full flex items-center justify-center">
                    <i class="bi bi-currency-dollar text-xl text-green-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-900 to-blue-800 rounded-xl shadow-md p-5 border border-blue-700 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-200">Ingresos Totales</p>
                    <p class="text-2xl font-bold text-white">${{ number_format($totalRevenue, 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-700 rounded-full flex items-center justify-center">
                    <i class="bi bi-bank text-xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Trámites por mes -->
        <div class="bg-slate-800 rounded-xl shadow-md p-6 border border-slate-700">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-white">Trámites por Mes</h3>
                <select wire:model.live="year" class="px-3 py-1.5 border border-slate-600 rounded-lg text-sm bg-slate-700 text-white">
                    @for ($i = date('Y') - 4; $i <= date('Y'); $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            @php
                $maxCount = max($monthlyData) ?: 1;
                $maxHeight = 200;
                $scaleFactor = $maxHeight / $maxCount;
            @endphp
            <div class="h-64 flex items-end space-x-2">
                @foreach ($monthlyData as $month => $count)
                    <div class="flex-1 flex flex-col items-center group">
                        <div class="w-full bg-blue-500 rounded-t transition-all hover:bg-blue-600 relative"
                            style="height: {{ max($count * $scaleFactor, 4) }}px">
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                {{ $count }}
                            </div>
                        </div>
                        <span class="text-xs text-slate-400 mt-2">{{ __(date('M', mktime(0, 0, 0, $month, 1))) }}</span>
                        <span class="text-xs font-bold text-white">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Ingresos Mensuales -->
        <div class="bg-slate-800 rounded-xl shadow-md p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-white mb-6">Ingresos Mensuales ({{ $year }})</h3>
            @php
                $maxRevenue = max($monthlyRevenue) ?: 1;
                $maxHeight = 200;
                $scaleFactor = $maxHeight / $maxRevenue;
            @endphp
            <div class="h-64 flex items-end space-x-2">
                @foreach ($monthlyRevenue as $month => $amount)
                    <div class="flex-1 flex flex-col items-center group">
                        <div class="w-full bg-green-500 rounded-t transition-all hover:bg-green-600 relative"
                            style="height: {{ max($amount * $scaleFactor, 4) }}px">
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                ${{ number_format($amount, 0) }}
                            </div>
                        </div>
                        <span class="text-xs text-slate-400 mt-2">{{ __(date('M', mktime(0, 0, 0, $month, 1))) }}</span>
                        <span class="text-xs font-bold text-white">${{ number_format($amount, 0) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Distribución por Estado -->
        <div class="bg-slate-800 rounded-xl shadow-md p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-white mb-4">Distribución por Estado</h3>
            <div class="space-y-3">
                @foreach ($statusDistribution as $status => $count)
                    @php
                        $percent = $totalProcedures > 0 ? round(($count / $totalProcedures) * 100) : 0;
                        $colors = [
                            'Secretaría' => 'blue',
                            'Finanzas' => 'indigo',
                            'Revisión Académica' => 'purple',
                            'Firma' => 'pink',
                            'Observación' => 'yellow',
                            'Completado' => 'green',
                        ];
                        $color = $colors[$status] ?? 'gray';
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-300">{{ $status }}</span>
                            <span class="font-semibold text-white">{{ $count }} ({{ $percent }}%)</span>
                        </div>
                        <div class="w-full bg-slate-700 rounded-full h-2">
                            <div class="bg-{{ $color }}-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Demanda por Programa -->
        <div class="bg-slate-800 rounded-xl shadow-md p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-white mb-4">Demanda por Programa</h3>
            <div class="space-y-3">
                @foreach ($programDemand as $program => $count)
                    @php $percent = $totalProcedures > 0 ? round(($count / $totalProcedures) * 100) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="capitalize text-slate-300">{{ __($program) }}</span>
                            <span class="font-semibold text-white">{{ $count }} ({{ $percent }}%)</span>
                        </div>
                        <div class="w-full bg-slate-700 rounded-full h-2">
                            <div class="bg-teal-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tipo de Certificado -->
        <div class="bg-slate-800 rounded-xl shadow-md p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-white mb-4">Tipo de Certificado</h3>
            <div class="space-y-3">
                @foreach ($certificateTypes as $type => $count)
                    @php $percent = $totalProcedures > 0 ? round(($count / $totalProcedures) * 100) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-300">{{ ucfirst(__(str_replace('_', ' ', $type))) }}</span>
                            <span class="font-semibold text-white">{{ $count }} ({{ $percent }}%)</span>
                        </div>
                        <div class="w-full bg-slate-700 rounded-full h-2">
                            <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Información Financiera Destacada -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-gradient-to-r from-slate-800 to-slate-800 rounded-xl shadow-md p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <i class="bi bi-pie-chart text-blue-400"></i>
                Resumen Financiero
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b border-slate-700 pb-3">
                    <span class="text-slate-300">Ingresos Totales Verificados</span>
                    <span class="font-bold text-2xl text-green-400">${{ number_format($totalRevenue, 0) }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-700 pb-3">
                    <span class="text-slate-300">Ingresos {{ $year }}</span>
                    <span class="font-bold text-xl text-green-300">${{ number_format($yearlyRevenue, 0) }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-700 pb-3">
                    <span class="text-slate-300">Pagos Pendientes de Verificar</span>
                    <span class="font-bold text-red-400">${{ number_format($pendingPayments, 0) }}</span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-slate-300">Tiempo promedio de finalización</span>
                    <span class="font-bold text-blue-400 text-lg">{{ $averageCompletionTime }} días</span>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 rounded-xl shadow-md p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <i class="bi bi-graph-up text-purple-400"></i>
                Métricas Clave
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-4 bg-slate-700 rounded-lg">
                    <p class="text-2xl font-bold text-white">{{ $totalProcedures }}</p>
                    <p class="text-xs text-slate-400">Total Trámites</p>
                </div>
                <div class="text-center p-4 bg-slate-700 rounded-lg">
                    <p class="text-2xl font-bold text-white">{{ $totalUsers }}</p>
                    <p class="text-xs text-slate-400">Usuarios Registrados</p>
                </div>
                <div class="text-center p-4 bg-slate-700 rounded-lg">
                    @php $completionRate = $totalProcedures > 0 ? round(($statusDistribution['Completado'] ?? 0) / $totalProcedures * 100) : 0; @endphp
                    <p class="text-2xl font-bold text-green-400">{{ $completionRate }}%</p>
                    <p class="text-xs text-slate-400">Tasa de Completado</p>
                </div>
                <div class="text-center p-4 bg-slate-700 rounded-lg">
                    <p class="text-2xl font-bold text-yellow-400">{{ $averageCompletionTime }}</p>
                    <p class="text-xs text-slate-400">Días Promedio</p>
                </div>
            </div>
        </div>
    </div>
</div>