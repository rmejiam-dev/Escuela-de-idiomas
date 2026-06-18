<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Escuela de Idiomas') }}</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-900">
    
    <!-- Topbar público -->
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
                @if(Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="text-blue-400 hover:text-blue-300 transition flex items-center gap-1 text-sm">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="text-blue-400 hover:text-blue-300 transition flex items-center gap-1 text-sm">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Iniciar Sesión</span>
                        </a>                       
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main>
        {{ $slot }}
    </main>

    @livewireScripts
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>