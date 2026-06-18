<div>
    <div class="bg-slate-700 rounded-xl shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold">{{ $userId ? 'Editar Usuario' : 'Nuevo Usuario' }}</h2>
        </div>

        <form wire:submit="save" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2">Nombre completo *</label>
                    <input type="text" wire:model="name"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Email *</label>
                    <input type="email" wire:model="email"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Número de identificación (RUT) *</label>
                    <input type="text" wire:model="identification_number" placeholder="12345678-9"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('identification_number')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Teléfono</label>
                    <input type="text" wire:model="phone"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('phone')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Dirección</label>
                    <textarea wire:model="address" rows="2"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                    @error('address')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">
                        Contraseña {{ $userId ? '(dejar en blanco para mantener)' : '*' }}
                    </label>
                    <input type="password" wire:model="password"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('password')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">
                        Confirmar contraseña {{ $userId ? '(solo si cambia contraseña)' : '*' }}
                    </label>
                    <input type="password" wire:model="password_confirmation"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Estado</label>
                    <div class="flex items-center">
                        <button type="button" wire:click="toggleStatus"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-700"
                            :class="{
                                'bg-blue-600': {{ $is_active ? 'true' : 'false' }},
                                'bg-gray-300': !{{ $is_active ? 'true' : 'false' }}
                            }">
                            <span class="sr-only">Activar/Desactivar usuario</span>
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                                :class="{
                                    'translate-x-6': {{ $is_active ? 'true' : 'false' }},
                                    'translate-x-1': !{{ $is_active ? 'true' : 'false' }}
                                }">
                            </span>
                        </button>
                        <span class="ml-3 text-sm">
                            <span x-text="{{ $is_active ? 'true' : 'false' }} ? 'Activo' : 'Inactivo'"></span>
                        </span>
                    </div>
                    @error('is_active')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Roles *</label>
                    <div class="space-y-2 border rounded-lg p-3 max-h-40 overflow-y-auto">
                        @foreach ($roles as $role)
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="selectedRoles" value="{{ $role->name }}"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm">{{ ucfirst($role->name) }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedRoles')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                <a href="{{ route('users.index') }}"
                    class="px-4 py-2 border rounded-lg hover:bg-slate-800">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    {{ $userId ? 'Actualizar' : 'Crear' }}
                </button>
            </div>
        </form>
    </div>
</div>
