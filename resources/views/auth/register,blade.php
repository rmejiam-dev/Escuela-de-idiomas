<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro - Escuela de Idiomas</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @livewireStyles
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12">
        <div class="bg-white rounded-xl shadow-md p-8 w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Registro</h1>
                <p class="text-gray-600 mt-2">Crear cuenta como estudiante</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border rounded-lg" required>
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border rounded-lg" required>
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Número de identificación</label>
                        <input type="text" name="identification_number" value="{{ old('identification_number') }}" class="w-full px-4 py-2 border rounded-lg" required>
                        @error('identification_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                        <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg" required>
                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>
                    <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium mt-6">
                        Registrarse
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800">¿Ya tienes cuenta? Inicia sesión</a>
            </div>
        </div>
    </div>
    @livewireScripts
</body>
</html>