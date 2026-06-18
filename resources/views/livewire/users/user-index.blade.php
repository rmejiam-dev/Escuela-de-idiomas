<div>
    <div class="bg-slate-700 rounded-xl shadow-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Usuarios</h2>
                <a href="{{ route('users.create') }}"
                    class="px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800">
                    <i class="bi bi-person-plus"></i> Nuevo Usuario
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
                    <select wire:model.live="role"
                        class="px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-700">
                        <option value="">Todos los roles</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst(__(str_replace('_', ' ', $role->name))) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select wire:model.live="status"
                        class="px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-700">
                        <option value="">Todos los estados</option>
                        <option value="active">Activos</option>
                        <option value="inactive">Inactivos</option>
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
                <table class=" min-w-full divide-y divide-gray-200">
                    <thead class="bg-slate-800">
                        <tr>
                            <th class="p-3 text-left text-xs font-medium uppercase">Nombre</th>
                            <th class="p-3 text-left text-xs font-medium uppercase">Email</th>
                            <th class="p-3 text-left text-xs font-medium uppercase">Cédula</th>
                            <th class="p-3 text-left text-xs font-medium uppercase">Roles</th>
                            <th class="p-3 text-left text-xs font-medium uppercase">Estado</th>
                            <th class="p-3 text-left text-xs font-medium uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-800">
                                <td class="px-6 py-4">{{ $user->name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">{{ $user->identification_number }}</td>
                                <td class="px-6 py-4">
                                    @forelse($user->roles as $role)
                                        <span class="inline-block px-2 py-1 text-xs bg-slate-900 rounded-full">
                                            {{ ucfirst(__(str_replace('_', ' ', $role->name))) }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-500">Sin rol asignado</span>
                                    @endforelse
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center">
                                        <div class="relative group">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" wire:change="toggleStatus({{ $user->id }})"
                                                    {{ $user->is_active ? 'checked' : '' }} class="sr-only peer">
                                                <div
                                                    class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                                </div>
                                            </label>
                                            <div
                                                class="absolute bottom-full mb-2 hidden group-hover:block bg-gray-800 text-white text-xs rounded px-2 py-1 whitespace-nowrap">
                                                {{ $user->is_active ? 'Click para desactivar' : 'Click para activar' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- Editar -->
                                        <div class="relative group">
                                            <a href="{{ route('users.edit', $user) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-full text-gray-600 hover:bg-gray-200 transition-all duration-200">
                                                <i class="bi bi-pencil text-base text-blue-500"></i>
                                            </a>
                                            <div
                                                class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded-md opacity-0 group-hover:opacity-100 transition-all duration-200 whitespace-nowrap pointer-events-none shadow-lg">
                                                <i class="bi bi-pencil mr-1 text-xs"></i>
                                                Editar usuario
                                                <div
                                                    class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1 border-4 border-transparent border-t-gray-800">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Eliminar -->
                                        <div class="relative group">
                                            <button wire:click="delete({{ $user->id }})"
                                                wire:confirm="¿Eliminar este usuario?"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-full text-gray-600  hover:bg-gray-200 transition-all duration-200">
                                                <i class="bi bi-trash text-base text-red-500"></i>
                                            </button>
                                            <div
                                                class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded-md opacity-0 group-hover:opacity-100 transition-all duration-200 whitespace-nowrap pointer-events-none shadow-lg">
                                                <i class="bi bi-trash mr-1 text-xs"></i>
                                                Eliminar usuario
                                                <div
                                                    class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1 border-4 border-transparent border-t-gray-800">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay usuarios
                                    registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
