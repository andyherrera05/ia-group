<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logística y Transporte - IA GROUPS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background Effects -->
    <div class="fixed inset-0 opacity-10">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-yellow-500 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-teal-500 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <div class="relative z-10 max-w-2xl mx-auto px-4 text-center">
        <!-- Logo -->
        <a href="/" class="inline-block mb-8">
            <div class="flex items-center justify-center space-x-3">
                <div class="w-16 h-16 bg-yellow-500/10 border-2 border-yellow-500/30 rounded-lg flex items-center justify-center">
                    <svg class="w-10 h-10 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold tracking-widest text-yellow-500">IA GROUPS</h1>
            </div>
        </a>

        <!-- Icon -->
        <div class="mb-8">
            <div class="w-24 h-24 mx-auto bg-gradient-to-br from-teal-500/20 to-teal-600/10 border-2 border-teal-500/40 rounded-2xl flex items-center justify-center animate-bounce">
                <svg class="w-12 h-12 text-teal-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                </svg>
            </div>
        </div>

        <!-- Title -->
        <h2 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-6 leading-tight">
            Logística y <br/>
            <span class="bg-gradient-to-r from-teal-400 via-cyan-400 to-teal-500 bg-clip-text text-transparent">Transporte</span>
        </h2>

        <!-- Message -->
        <div class="bg-gradient-to-br from-yellow-500/10 to-amber-500/5 border border-yellow-500/30 rounded-xl p-8 mb-8">
            <div class="flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                <h3 class="text-2xl font-bold text-yellow-500">Estamos trabajando en ello</h3>
            </div>
            <p class="text-gray-300 text-lg leading-relaxed">
                Estamos preparando información detallada sobre nuestros servicios de optimización logística, 
                gestión de cadenas de suministro y transporte internacional multimodal.
            </p>
        </div>

        <!-- Back Button -->
        <a href="/" class="inline-flex items-center space-x-2 bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-400 hover:to-amber-400 text-black font-bold py-4 px-8 rounded-lg transition-all transform hover:scale-105 shadow-lg shadow-yellow-500/30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Volver al Inicio</span>
        </a>

        <!-- Progress Indicator -->
        <div class="mt-12">
            <p class="text-sm text-gray-500 mb-3">Progreso del desarrollo</p>
            <div class="w-full bg-gray-800 rounded-full h-2">
                <div class="bg-gradient-to-r from-teal-500 to-cyan-500 h-2 rounded-full animate-pulse" style="width: 50%"></div>
            </div>
        </div>
    </div>
</body>
</html>
