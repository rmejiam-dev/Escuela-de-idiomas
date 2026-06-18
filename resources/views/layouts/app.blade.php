<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Escuela de Idiomas') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @livewireStyles
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-slate-900">

    <livewire:navigation />
    <livewire:sidebar />
    <main class="pt-16 ml-20">
        <div class="p-6">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-900 border border-green-700 text-green-200 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-900 border border-red-700 text-red-200 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>

    @livewireScripts
</body>

</html>
