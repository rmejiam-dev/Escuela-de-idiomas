<div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-slate-700 rounded-xl shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold">Roles</h2>
                </div>

                <div class="p-6">
                    {{-- <div class="mb-6">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar rol..." 
                               class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div> --}}

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-slate-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Usuarios</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Permisos</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($roles as $role)
                                    <tr class="hover:bg-slate-800">
                                        <td class="px-6 py-4 font-medium">
                                            {{ ucfirst(__(str_replace('_', ' ', $role->name))) }}</td>
                                        <td class="px-6 py-4 flex justify-center">
                                            <span class="bg-blue-700 px-1 rounded">{{ $role->users()->count() }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($role->permissions->take(3) as $perm)
                                                    <span
                                                        class="px-2 py-1 text-xs bg-gray-600 rounded-full">{{ ucfirst(__(str_replace('_', ' ', $perm->name))) }}</span>
                                                @endforeach
                                                @if ($role->permissions->count() > 3)
                                                    <span
                                                        class="px-2 py-1 text-xs bg-gray-600 rounded-full">+{{ $role->permissions->count() - 3 }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 space-x-2">                                            
                                            @if ($role->name !== 'admin')
                                                <button wire:click="delete({{ $role->id }})"
                                                    wire:confirm="¿Eliminar este rol?"
                                                    class="text-red-600 hover:text-red-900">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-slate-700 rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">{{ 'Nuevo Rol' }}</h3>

                <form wire:submit="save">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Nombre del rol *</label>
                        <input type="text" wire:model="name"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        @error('name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Permisos</label>
                        <div class="border rounded-lg p-3 max-h-60 overflow-y-auto space-y-2">
                            {{-- @foreach ($permissions as $permission)
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}" 
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm">{{ucfirst( __(str_replace('_', ' ', $permission->name)))}}</span>
                            </label>
                            @endforeach --}}
                            @foreach ($permissions as $permission)
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="selectedPermissions"
                                        value="{{ $permission->name }}"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span
                                        class="ml-2 text-sm">{{ ucfirst(__(str_replace('_', ' ', $permission->name))) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            {{  'Crear' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
