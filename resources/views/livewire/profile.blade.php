<div>
    <div class="max-w-4xl mx-auto">
        <!-- Información del perfil -->
        <div class="bg-slate-800 rounded-xl shadow-md mb-6 border border-slate-700">
            <div class="p-6 border-b border-slate-700">
                <div class="flex items-center gap-2">
                    <i class="bi bi-person-circle text-2xl text-blue-400"></i>
                    <h2 class="text-xl font-semibold text-white">Mi Perfil</h2>
                </div>
            </div>
            
            <form wire:submit="updateProfile" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Nombre completo</label>
                        <input type="text" wire:model="name" 
                            class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-white placeholder-slate-400">
                        @error('name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Correo electrónico</label>
                        <input type="email" wire:model="email" 
                            class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-white placeholder-slate-400">
                        @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Teléfono</label>
                        <input type="text" wire:model="phone" 
                            class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-white placeholder-slate-400"
                            placeholder="+56 9 1234 5678">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">RUT</label>
                        <input type="text" wire:model="identification_number" disabled
                            class="w-full px-4 py-2 bg-slate-800 border border-slate-600 rounded-lg text-slate-400 cursor-not-allowed">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-300 mb-1">Dirección</label>
                        <textarea wire:model="address" rows="2" 
                            class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-white placeholder-slate-400"
                            placeholder="Calle, número, ciudad"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                        <i class="bi bi-save"></i>
                        Actualizar Perfil
                    </button>
                </div>
            </form>
        </div>

        <!-- Cambiar contraseña -->
        <div class="bg-slate-800 rounded-xl shadow-md border border-slate-700">
            <div class="p-6 border-b border-slate-700">
                <div class="flex items-center gap-2">
                    <i class="bi bi-shield-lock text-2xl text-blue-400"></i>
                    <h2 class="text-xl font-semibold text-white">Cambiar Contraseña</h2>
                </div>
            </div>
            
            <form wire:submit="updatePassword" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Contraseña actual</label>
                        <input type="password" wire:model="current_password" 
                            class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-white placeholder-slate-400"
                            placeholder="Ingrese su contraseña actual">
                        @error('current_password') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Nueva contraseña</label>
                        <input type="password" wire:model="new_password" 
                            class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-white placeholder-slate-400"
                            placeholder="Mínimo 8 caracteres">
                        @error('new_password') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Confirmar nueva contraseña</label>
                        <input type="password" wire:model="new_password_confirmation" 
                            class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-white placeholder-slate-400"
                            placeholder="Repita su nueva contraseña">
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                        <i class="bi bi-key"></i>
                        Cambiar Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>