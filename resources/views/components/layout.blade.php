@props(['title' => 'IA GROUPS'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - IA GROUPS</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-yellow-500/10 rounded-lg flex items-center justify-center">
                            <img src="{{ asset('images/logo.png') }}" alt="IA Groups Logo" class="w-full h-full object-contain">
                        </div>
                        <span class="text-xl font-bold text-yellow-500">IA GROUPS</span>
                    </a>
                </div>
                <nav class="hidden md:flex space-x-6">
                    <a href="/" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition">
                        Inicio
                    </a>
                    <a href="/maritimo" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition">
                        Marítimo
                    </a>
                    <a href="/aereo" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition">
                        Aéreo
                    </a>
                    <a href="/terrestre" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition">
                        Terrestre
                    </a>
                    <a href="/impuestos" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition">
                        Impuestos
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-sm text-gray-500">
                © {{ date('Y') }} LogiQuote - Sistema de Cotizaciones Logísticas
            </p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
