<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Escuela de Idiomas</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-900">
    
    <!-- Topbar -->
    <nav class="fixed top-0 left-0 right-0 z-30 bg-slate-800 shadow-lg">
        <div class="px-6 py-3 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="bi bi-book-half text-2xl text-blue-400"></i>
                <span class="text-white font-semibold text-lg">{{ config('app.name', 'Escuela de Idiomas') }}</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('pre-enrollment.public') }}"
                   class="text-slate-300 hover:text-white transition flex items-center gap-1 text-sm">
                    <i class="bi bi-journal-text"></i>
                    <span>Preinscripción</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="min-h-screen flex items-center justify-center py-12 pt-24">
        <div class="bg-slate-800 rounded-xl shadow-2xl p-8 w-full max-w-md border border-slate-700">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-person-circle text-3xl text-blue-400"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">Iniciar Sesión</h1>
                <p class="text-slate-400 mt-2">Ingresa a tu cuenta</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Correo electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                            class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-white placeholder-slate-400"
                            placeholder="ejemplo@escuela.cl" required>
                        @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Contraseña</label>
                        <input type="password" name="password" 
                            class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-white placeholder-slate-400"
                            placeholder="••••••••" required>
                        @error('password') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-slate-600 bg-slate-700 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-slate-400">Recordarme</span>
                        </label>
                    </div>
                    <button type="submit" 
                        class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center justify-center gap-2">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Ingresar
                    </button>
                </div>
            </form>            
        </div>
    </div>

    @livewireScripts
</body>
</html>