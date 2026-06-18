<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Preinscripcion - Escuela de Idiomas</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-20">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-slate-700 rounded-xl shadow-md p-8">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold">Preinscripcion Escuela de Idiomas</h1>
                    <p class="mt-2">Completa el formulario para recibir informacion</p>
                </div>

                @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
                @endif

                <form wire:submit="save">
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium mb-1">Nombre completo</label>
                            <input type="text" wire:model="full_name" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('full_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Correo electronico</label>
                            <input type="email" wire:model="email" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Telefono</label>
                            <input type="text" wire:model="phone" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Numero de identificacion</label>
                            <input type="text" wire:model="identification_number" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('identification_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Programa de interes</label>
                            <select wire:model="program_interest" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-700">
                                <option value="">Seleccionar...</option>
                                <option value="english">Ingles</option>
                                <option value="french">Frances</option>
                                <option value="german">Aleman</option>
                                <option value="portuguese">Portugues</option>
                                <option value="italian">Italiano</option>
                                <option value="mandarin">Mandin</option>
                            </select>
                            @error('program_interest') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Mensaje adicional</label>
                            <textarea wire:model="message" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('message') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Captcha: Cuanto es {{ $captcha_text }}?</label>
                            <div class="flex gap-2">
                                <input type="text" wire:model="captcha" class="flex-1 px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <button type="button" wire:click="generateCaptcha" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </div>
                            @error('captcha') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                            Enviar Preinscripcion
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @livewireScripts
</body>
</html>