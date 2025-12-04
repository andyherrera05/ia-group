<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - IA GROUPS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-black h-screen overflow-hidden text-white">

    <!-- Contenedor principal sin scroll -->
    <div class="flex h-screen">
        
        <!-- Columna izquierda: Imagen de logística -->
        <div class="hidden lg:block w-1/2 relative">
            <img src="{{ asset('images/logistica.jpg') }}" alt="Logística global" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-br from-black/40 via-black/20 to-black/40"></div>
            
            <!-- Botón Volver flotante -->
            <a href="{{ route('home') }}" class="absolute top-8 left-8 z-10 flex items-center px-4 py-2 bg-black/40 backdrop-blur-sm border border-yellow-500/30 rounded-xl text-yellow-400 hover:text-yellow-300 hover:bg-black/50 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver al Inicio
            </a>

            <!-- Logo overlay en imagen -->
            <div class="absolute top-8 left-8 pt-16">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-black/40 backdrop-blur-sm border border-yellow-500/30 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black tracking-widest text-yellow-500">IA GROUPS</h1>
                        <p class="text-xs text-white/80 tracking-wide">LOGÍSTICA SIN LÍMITES</p>
                    </div>
                </div>
            </div>

            <!-- Texto de bienvenida en imagen -->
            <div class="absolute bottom-12 left-8 right-12">
                <h2 class="text-4xl font-black text-white mb-3 leading-tight">
                    CONECTAMOS EL <span class="text-yellow-500">MUNDO</span>
                </h2>
                <p class="text-lg text-white/90 leading-relaxed">
                    Soluciones inteligentes de transporte para tus importaciones. Tu carga, nuestro compromiso.
                </p>
            </div>
        </div>

        <!-- Columna derecha: Formulario -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-12 relative bg-black/50 backdrop-blur-sm">
            
            <!-- Botón Volver en móvil -->
            <a href="{{ route('home') }}" class="lg:hidden absolute top-6 left-6 z-10 flex items-center px-3 py-2 bg-black/40 backdrop-blur-sm border border-yellow-500/30 rounded-lg text-yellow-400 hover:text-yellow-300 hover:bg-black/50 transition-all">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>

            <!-- Formulario centrado -->
            <div class="w-full max-w-md bg-white/5 backdrop-blur-xl border border-yellow-500/20 p-8 sm:p-10 rounded-2xl shadow-2xl">
                
                <!-- Header del formulario -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-black text-white mb-2">
                        BIENVENIDO <span class="text-yellow-500">DE NUEVO</span>
                    </h2>
                    <p class="text-gray-400 text-sm">
                        Accede a tu panel de gestión logística
                    </p>
                </div>

                <form class="space-y-6" action="#" method="POST">
                    @csrf
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-yellow-500 font-bold mb-2 text-sm uppercase tracking-wide">
                            Correo Electrónico
                        </label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            required 
                            class="w-full bg-black/40 border border-yellow-500/30 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all placeholder-gray-500" 
                            placeholder="tu@empresa.com"
                        >
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-yellow-500 font-bold mb-2 text-sm uppercase tracking-wide">
                            Contraseña
                        </label>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            class="w-full bg-black/40 border border-yellow-500/30 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all placeholder-gray-500" 
                            placeholder="••••••••"
                        >
                    </div>

                    <!-- Opciones -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" class="h-4 w-4 bg-black/50 border-yellow-500/30 text-yellow-500 focus:ring-yellow-500 rounded">
                            <span class="ml-2 text-sm text-gray-400">Recordarme</span>
                        </label>
                        <a href="#" class="text-sm text-yellow-500 hover:text-yellow-400 font-medium transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <!-- Botón submit -->
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-yellow-500 to-yellow-400 hover:from-yellow-400 hover:to-yellow-300 text-black font-black py-4 px-8 text-lg uppercase tracking-wider transition-all transform hover:scale-105 shadow-xl hover:shadow-yellow-500/50 rounded-xl"
                    >
                        ACCEDER AL PANEL
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-yellow-500/20"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-black/30 text-gray-500 uppercase tracking-wide">O continúa con</span>
                    </div>
                </div>

                <!-- Google Login -->
                <div class="grid grid-cols-1 gap-4">
                    <button class="flex items-center justify-center px-4 py-3 border border-yellow-500/30 hover:border-yellow-500 bg-black/30 hover:bg-black/50 transition-all group rounded-xl">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        </svg>
                        <span class="ml-2 text-sm text-gray-400 group-hover:text-yellow-500 font-medium transition-colors">Google</span>
                    </button>
                </div>

                <!-- Sign Up Link -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-400">
                        ¿Eres nuevo en IA GROUPS? 
                        <a href="{{ url('/register') }}" class="text-yellow-500 hover:text-yellow-400 font-bold transition-colors">
                            Crea tu cuenta ahora
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>