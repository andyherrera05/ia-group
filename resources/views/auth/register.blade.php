<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - IA GROUPS</title>
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
            <a href="{{ url('/login') }}" class="absolute top-8 left-8 z-10 flex items-center px-4 py-2 bg-black/40 backdrop-blur-sm border border-yellow-500/30 rounded-xl text-yellow-400 hover:text-yellow-300 hover:bg-black/50 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>

            <!-- Logo y texto -->
            <div class="absolute bottom-12 left-8 right-12">
                <h2 class="text-4xl font-black text-white mb-3 leading-tight">
                    CONECTAMOS EL <span class="text-yellow-500">MUNDO</span>
                </h2>
                <p class="text-lg text-white/90 leading-relaxed">
                    Únete a nuestra red de importaciones. Gestión inteligente de carga.
                </p>
            </div>
        </div>

        <!-- Columna derecha: Formulario de Registro -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative bg-black/50 backdrop-blur-sm overflow-y-auto">
            
            <!-- Botón Volver en móvil -->
            <a href="{{ url('/login') }}" class="lg:hidden absolute top-4 left-4 z-10 flex items-center px-3 py-2 bg-black/40 backdrop-blur-sm border border-yellow-500/30 rounded-lg text-yellow-400 hover:text-yellow-300 hover:bg-black/50 transition-all">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>

            <!-- Formulario con altura máxima -->
            <div class="w-full max-w-md bg-white/5 backdrop-blur-xl border border-yellow-500/20 p-6 sm:p-8 rounded-2xl shadow-2xl my-auto">
                
                <!-- Header -->
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-black text-white mb-2">
                        CREA TU <span class="text-yellow-500">CUENTA</span>
                    </h2>
                    <p class="text-gray-400 text-sm">
                        Comienza a gestionar tus importaciones
                    </p>
                </div>

                <form class="space-y-4" action="#" method="POST">
                    @csrf
                    
                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-yellow-500 font-bold mb-2 text-xs uppercase tracking-wide">
                            Nombre Completo
                        </label>
                        <input 
                            id="name" 
                            name="name" 
                            type="text" 
                            required 
                            class="w-full bg-black/40 border border-yellow-500/30 text-white px-4 py-3 rounded-lg focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all placeholder-gray-500 text-sm" 
                            placeholder="Juan Pérez"
                        >
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-yellow-500 font-bold mb-2 text-xs uppercase tracking-wide">
                            Correo Electrónico
                        </label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            required 
                            class="w-full bg-black/40 border border-yellow-500/30 text-white px-4 py-3 rounded-lg focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all placeholder-gray-500 text-sm" 
                            placeholder="tu@empresa.com"
                        >
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="phone" class="block text-yellow-500 font-bold mb-2 text-xs uppercase tracking-wide">
                            Teléfono
                        </label>
                        <input 
                            id="phone" 
                            name="phone" 
                            type="tel" 
                            class="w-full bg-black/40 border border-yellow-500/30 text-white px-4 py-3 rounded-lg focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all placeholder-gray-500 text-sm" 
                            placeholder="+56 9 1234 5678"
                        >
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label for="password" class="block text-yellow-500 font-bold mb-2 text-xs uppercase tracking-wide">
                            Contraseña
                        </label>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            class="w-full bg-black/40 border border-yellow-500/30 text-white px-4 py-3 rounded-lg focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all placeholder-gray-500 text-sm" 
                            placeholder="••••••••"
                        >
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div>
                        <label for="password_confirmation" class="block text-yellow-500 font-bold mb-2 text-xs uppercase tracking-wide">
                            Confirmar Contraseña
                        </label>
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            required 
                            class="w-full bg-black/40 border border-yellow-500/30 text-white px-4 py-3 rounded-lg focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all placeholder-gray-500 text-sm" 
                            placeholder="••••••••"
                        >
                    </div>

                    <!-- Botón submit -->
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-yellow-500 to-yellow-400 hover:from-yellow-400 hover:to-yellow-300 text-black font-black py-3 px-8 text-base uppercase tracking-wider transition-all transform hover:scale-105 shadow-lg hover:shadow-yellow-500/50 rounded-lg"
                    >
                        CREAR CUENTA
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-yellow-500/20"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="px-3 bg-black/30 text-gray-500 tracking-wide">O regístrate con</span>
                    </div>
                </div>

                <!-- Google Register -->
                <div class="grid grid-cols-1 gap-3">
                    <button class="flex items-center justify-center px-4 py-3 border border-yellow-500/30 hover:border-yellow-500 bg-black/30 hover:bg-black/50 transition-all group rounded-lg">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        </svg>
                        <span class="ml-2 text-sm text-gray-400 group-hover:text-yellow-500 font-medium transition-colors">Google</span>
                    </button>
                </div>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-400">
                        ¿Ya tienes cuenta? 
                        <a href="{{ url('/login') }}" class="text-yellow-500 hover:text-yellow-400 font-bold transition-colors">
                            Inicia sesión aquí
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>