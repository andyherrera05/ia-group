<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nosotros - IA GROUPS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-black min-h-screen text-white overflow-x-hidden">
    <!-- Header -->
    <header class="bg-white/5 backdrop-blur-xl border-b border-yellow-500/20 sticky top-0 z-50 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-yellow-500 to-amber-600 flex items-center justify-center font-black text-black text-xl sm:text-2xl shadow-lg shadow-yellow-500/50 rounded-lg">
                        <img src="{{ asset('images/logo.png') }}" alt="IA GROUPS" class="w-full h-full object-contain">
                    </div>
                    <span class="text-xl sm:text-2xl font-black bg-gradient-to-r from-yellow-500 to-amber-500 bg-clip-text text-transparent">
                        IA GROUPS
                    </span>
                </div>

                <nav class="hidden lg:flex space-x-8">
                    <a href="{{ route('home') }}" class="text-yellow-400 hover:text-yellow-300 font-medium transition-colors">Inicio</a>
                    <a href="{{ route('nosotros') }}" class="text-yellow-400 hover:text-yellow-300 font-medium transition-colors">Nosotros</a>
                    <a href="{{ route('home') }}#servicios" class="text-yellow-400 hover:text-yellow-300 font-medium transition-colors">Servicios</a>
                </nav>
                
                <div class="flex items-center gap-3 sm:gap-4">
                    <button id="mobileMenuBtn" class="lg:hidden text-yellow-500 hover:text-yellow-400 transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Overlay -->
    <div id="mobileMenu" class="fixed inset-0 bg-black/95 backdrop-blur-lg z-50 lg:hidden hidden">
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-between p-4 border-b border-yellow-500/20">
                <h2 class="text-xl font-bold text-yellow-500">MENÚ</h2>
                <button id="closeMobileMenu" class="text-yellow-500 hover:text-yellow-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <nav class="flex flex-col p-6 space-y-6">
                <a href="{{ route('home') }}" class="text-2xl text-yellow-400 hover:text-yellow-300 font-bold transition-colors mobile-menu-link">Inicio</a>
                <a href="{{ route('nosotros') }}" class="text-2xl text-yellow-400 hover:text-yellow-300 font-bold transition-colors mobile-menu-link">Nosotros</a>
                <a href="{{ route('home') }}#servicios" class="text-2xl text-yellow-400 hover:text-yellow-300 font-bold transition-colors mobile-menu-link">Servicios</a>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-b from-gray-900 via-black to-gray-900 py-20 sm:py-32 lg:py-40 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-full blur-3xl float-animation"></div>
            <div class="absolute bottom-1/4 left-1/4 w-96 h-96 bg-gradient-to-tl from-orange-500 to-yellow-600 rounded-full blur-3xl float-animation" style="animation-delay: -3s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <span class="text-yellow-500 font-bold text-sm sm:text-base tracking-widest uppercase">Conócenos</span>
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-white mt-4 mb-6 leading-tight">
                    QUIÉNES <span class="bg-gradient-to-r from-yellow-500 via-amber-500 to-yellow-600 bg-clip-text text-transparent">SOMOS</span>
                </h1>
                <p class="text-lg sm:text-xl text-gray-400 max-w-3xl mx-auto">
                    Más que una empresa de logística, somos tu socio estratégico en comercio global
                </p>
            </div>
        </div>
    </section>

    <!-- Nuestra Misión -->
    <section class="relative bg-gradient-to-b from-black via-gray-900 to-black py-16 sm:py-24 lg:py-32 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-gradient-to-tl from-amber-500 to-yellow-600 rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div>
                    <span class="text-amber-500 font-bold text-sm sm:text-base tracking-widest uppercase">Nuestra Misión</span>
                    <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white mt-4 mb-6 sm:mb-8 leading-tight">
                        Redefiniendo el<br/>
                        <span class="bg-gradient-to-r from-yellow-500 via-amber-500 to-yellow-600 bg-clip-text text-transparent">Comercio Global</span>
                    </h2>
                    <p class="text-base sm:text-lg text-gray-300 mb-6 leading-relaxed">
                        En IA GROUPS, transformamos la complejidad logística en simplicidad operativa. 
                        Con más de una década conectando continentes, ofrecemos soluciones integrales 
                        de transporte que combinan tecnología de punta con experiencia humana.
                    </p>
                    <p class="text-base sm:text-lg text-gray-400 leading-relaxed">
                        Desde el aire hasta el mar, desde la carretera hasta tu almacén, gestionamos 
                        cada etapa de tu cadena de suministro con precisión milimétrica. Nuestro sistema 
                        automatizado de cotizaciones te permite tomar decisiones informadas en tiempo real, 
                        optimizando costos sin sacrificar velocidad ni seguridad.
                    </p>
                </div>
                
                <!-- Dashboard Charts -->
                <div class="grid grid-cols-1 gap-6">
                    <!-- Chart 1: Envíos por Mes (Bar Chart) -->
                    <div class="bg-gradient-to-br from-yellow-500/10 to-yellow-600/5 border border-yellow-500/30 p-6 hover:border-yellow-500 transition-all group">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold text-yellow-500 uppercase tracking-wide">Envíos por Mes</h3>
                            <span class="text-2xl font-black text-yellow-500">+32%</span>
                        </div>
                        <div class="flex items-end justify-between h-32 gap-2">
                            <div class="flex-1 bg-gradient-to-t from-amber-500/30 to-yellow-500/20 hover:from-amber-500 hover:to-yellow-500 transition-all cursor-pointer group-hover:scale-105" style="height: 45%;">
                                <div class="h-full flex items-end justify-center pb-1">
                                    <span class="text-xs font-bold text-yellow-500">45</span>
                                </div>
                            </div>
                            <div class="flex-1 bg-gradient-to-t from-amber-500/30 to-yellow-500/20 hover:from-amber-500 hover:to-yellow-500 transition-all cursor-pointer group-hover:scale-105" style="height: 60%;">
                                <div class="h-full flex items-end justify-center pb-1">
                                    <span class="text-xs font-bold text-yellow-500">60</span>
                                </div>
                            </div>
                            <div class="flex-1 bg-gradient-to-t from-orange-500/30 to-amber-500/20 hover:from-orange-500 hover:to-amber-500 transition-all cursor-pointer group-hover:scale-105" style="height: 78%;">
                                <div class="h-full flex items-end justify-center pb-1">
                                    <span class="text-xs font-bold text-amber-500">78</span>
                                </div>
                            </div>
                            <div class="flex-1 bg-gradient-to-t from-amber-500/30 to-yellow-500/20 hover:from-amber-500 hover:to-yellow-500 transition-all cursor-pointer group-hover:scale-105" style="height: 55%;">
                                <div class="h-full flex items-end justify-center pb-1">
                                    <span class="text-xs font-bold text-yellow-500">55</span>
                                </div>
                            </div>
                            <div class="flex-1 bg-gradient-to-t from-yellow-500/30 to-amber-500/20 hover:from-yellow-500 hover:to-amber-500 transition-all cursor-pointer group-hover:scale-105" style="height: 92%;">
                                <div class="h-full flex items-end justify-center pb-1">
                                    <span class="text-xs font-bold text-yellow-500">92</span>
                                </div>
                            </div>
                            <div class="flex-1 bg-gradient-to-t from-orange-500 to-amber-500 transition-all cursor-pointer group-hover:scale-105" style="height: 100%;">
                                <div class="h-full flex items-end justify-center pb-1">
                                    <span class="text-xs font-bold text-black">120</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-gray-500">
                            <span>Jun</span>
                            <span>Jul</span>
                            <span>Ago</span>
                            <span>Sep</span>
                            <span>Oct</span>
                            <span>Nov</span>
                        </div>
                    </div>

                    <!-- Chart 2: Distribución por Tipo de Transporte -->
                    <div class="bg-gradient-to-br from-yellow-500/10 to-yellow-600/5 border border-yellow-500/30 p-6 hover:border-yellow-500 transition-all group">
                        <h3 class="text-sm font-bold text-yellow-500 uppercase tracking-wide mb-4">Distribución de Servicios</h3>
                        <div class="space-y-3">
                            <!-- Marítimo -->
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-300">Marítimo</span>
                                    <span class="text-amber-500 font-bold">42%</span>
                                </div>
                                <div class="h-2 bg-black/50 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-yellow-500 to-amber-500 transition-all duration-1000 group-hover:w-full" style="width: 42%"></div>
                                </div>
                            </div>
                            <!-- Aéreo -->
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-300">Aéreo</span>
                                    <span class="text-orange-500 font-bold">28%</span>
                                </div>
                                <div class="h-2 bg-black/50 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-amber-500 to-orange-500 transition-all duration-1000 group-hover:w-full" style="width: 28%"></div>
                                </div>
                            </div>
                            <!-- Terrestre -->
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-300">Terrestre</span>
                                    <span class="text-yellow-500 font-bold">22%</span>
                                </div>
                                <div class="h-2 bg-black/50 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-yellow-600 to-amber-500 transition-all duration-1000 group-hover:w-full" style="width: 22%"></div>
                                </div>
                            </div>
                            <!-- Consultoría -->
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-300">Gestión Aduanera</span>
                                    <span class="text-orange-500 font-bold">8%</span>
                                </div>
                                <div class="h-2 bg-black/50 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-orange-500 to-yellow-500 transition-all duration-1000 group-hover:w-full" style="width: 8%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-yellow-500/10 to-yellow-600/5 border border-yellow-500/30 p-4 hover:border-yellow-500 transition-all group">
                            <div class="text-3xl sm:text-4xl font-black text-yellow-500 mb-2">98%</div>
                            <div class="text-xs sm:text-sm text-gray-400 uppercase tracking-wide">Satisfacción</div>
                        </div>
                        <div class="bg-gradient-to-br from-yellow-500/10 to-yellow-600/5 border border-yellow-500/30 p-4 hover:border-yellow-500 transition-all group">
                            <div class="text-3xl sm:text-4xl font-black text-amber-500 mb-2">150+</div>
                            <div class="text-xs sm:text-sm text-gray-400 uppercase tracking-wide">Clientes Activos</div>
                        </div>
                        <div class="bg-gradient-to-br from-yellow-500/10 to-yellow-600/5 border border-yellow-500/30 p-4 hover:border-yellow-500 transition-all group">
                            <div class="text-3xl sm:text-4xl font-black text-orange-500 mb-2">24/7</div>
                            <div class="text-xs sm:text-sm text-gray-400 uppercase tracking-wide">Atención Continua</div>
                        </div>
                        <div class="bg-gradient-to-br from-yellow-500/10 to-yellow-600/5 border border-yellow-500/30 p-4 hover:border-yellow-500 transition-all group">
                            <div class="text-3xl sm:text-4xl font-black text-yellow-600 mb-2">5M+</div>
                            <div class="text-xs sm:text-sm text-gray-400 uppercase tracking-wide">Toneladas Enviadas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- En Qué Nos Diferenciamos -->
    <section class="relative bg-gradient-to-b from-gray-900 via-black to-gray-900 py-16 sm:py-24 lg:py-32 overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-1/3 left-1/3 w-96 h-96 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-12 sm:mb-16">
                <span class="text-yellow-500 font-bold text-sm sm:text-base tracking-widest uppercase">Nuestros Diferenciadores</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-white mt-4">
                    ¿Por Qué Elegir <span class="bg-gradient-to-r from-yellow-500 via-amber-500 to-yellow-600 bg-clip-text text-transparent">IA GROUPS?</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Card 01 -->
                <div class="group relative bg-gradient-to-br from-gray-800/50 to-black/50 backdrop-blur-sm border border-yellow-500/20 p-6 sm:p-8 hover:border-yellow-500 transition-all duration-500 hover:shadow-2xl hover:shadow-yellow-500/20">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-500 to-transparent transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                    
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 rounded-full border-4 border-yellow-500/30 flex items-center justify-center text-2xl font-black text-yellow-500 group-hover:border-yellow-500 transition-colors">
                            01
                        </div>
                        <div class="ml-4 w-0 h-0 border-t-8 border-t-transparent border-b-8 border-b-transparent border-l-12 border-l-yellow-500/30 group-hover:border-l-yellow-500 transition-colors"></div>
                    </div>
                    
                    <p class="text-gray-300 text-sm leading-relaxed group-hover:text-white transition-colors">
                        Generamos y desarrollamos negocios internacionales para empresas, facilitando todas sus operaciones de importación y exportación.
                    </p>
                </div>

                <!-- Card 02 -->
                <div class="group relative bg-gradient-to-br from-gray-800/50 to-black/50 backdrop-blur-sm border border-yellow-500/20 p-6 sm:p-8 hover:border-yellow-500 transition-all duration-500 hover:shadow-2xl hover:shadow-yellow-500/20">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-500 to-transparent transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                    
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 rounded-full border-4 border-yellow-500/30 flex items-center justify-center text-2xl font-black text-yellow-500 group-hover:border-yellow-500 transition-colors">
                            02
                        </div>
                        <div class="ml-4 w-0 h-0 border-t-8 border-t-transparent border-b-8 border-b-transparent border-l-12 border-l-yellow-500/30 group-hover:border-l-yellow-500 transition-colors"></div>
                    </div>
                    
                    <p class="text-gray-300 text-sm leading-relaxed group-hover:text-white transition-colors">
                        Trabajamos con las herramientas de la economía colaborativa, alianzas estratégicas e intercambio de recursos.
                    </p>
                </div>

                <!-- Card 03 -->
                <div class="group relative bg-gradient-to-br from-gray-800/50 to-black/50 backdrop-blur-sm border border-yellow-500/20 p-6 sm:p-8 hover:border-yellow-500 transition-all duration-500 hover:shadow-2xl hover:shadow-yellow-500/20">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-500 to-transparent transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                    
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 rounded-full border-4 border-yellow-500/30 flex items-center justify-center text-2xl font-black text-yellow-500 group-hover:border-yellow-500 transition-colors">
                            03
                        </div>
                        <div class="ml-4 w-0 h-0 border-t-8 border-t-transparent border-b-8 border-b-transparent border-l-12 border-l-yellow-500/30 group-hover:border-l-yellow-500 transition-colors"></div>
                    </div>
                    
                    <p class="text-gray-300 text-sm leading-relaxed group-hover:text-white transition-colors">
                        Asesoramos, gestionamos, generamos soluciones en negocios internacionales. Con HQ en Argentina, hacia el mundo.
                    </p>
                </div>

                <!-- Card 04 -->
                <div class="group relative bg-gradient-to-br from-gray-800/50 to-black/50 backdrop-blur-sm border border-yellow-500/20 p-6 sm:p-8 hover:border-yellow-500 transition-all duration-500 hover:shadow-2xl hover:shadow-yellow-500/20">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-500 to-transparent transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                    
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 rounded-full border-4 border-yellow-500/30 flex items-center justify-center text-2xl font-black text-yellow-500 group-hover:border-yellow-500 transition-colors">
                            04
                        </div>
                        <div class="ml-4 w-0 h-0 border-t-8 border-t-transparent border-b-8 border-b-transparent border-l-12 border-l-yellow-500/30 group-hover:border-l-yellow-500 transition-colors"></div>
                    </div>
                    
                    <p class="text-gray-300 text-sm leading-relaxed group-hover:text-white transition-colors">
                        Damos solución a las deficiencias en tiempos, comunicación, financiación, pagos, seguimiento de operaciones, gestión administrativa y requerimientos legales.
                    </p>
                </div>

                <!-- Card 05 -->
                <div class="group relative bg-gradient-to-br from-gray-800/50 to-black/50 backdrop-blur-sm border border-yellow-500/20 p-6 sm:p-8 hover:border-yellow-500 transition-all duration-500 hover:shadow-2xl hover:shadow-yellow-500/20">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-500 to-transparent transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                    
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 rounded-full border-4 border-yellow-500/30 flex items-center justify-center text-2xl font-black text-yellow-500 group-hover:border-yellow-500 transition-colors">
                            05
                        </div>
                        <div class="ml-4 w-0 h-0 border-t-8 border-t-transparent border-b-8 border-b-transparent border-l-12 border-l-yellow-500/30 group-hover:border-l-yellow-500 transition-colors"></div>
                    </div>
                    
                    <p class="text-gray-300 text-sm leading-relaxed group-hover:text-white transition-colors">
                        Buscamos que todos nuestros clientes tengan la información correcta y los medios seguros para llevar adelante cada transacción.
                    </p>
                </div>

                <!-- Card 06 -->
                <div class="group relative bg-gradient-to-br from-gray-800/50 to-black/50 backdrop-blur-sm border border-yellow-500/20 p-6 sm:p-8 hover:border-yellow-500 transition-all duration-500 hover:shadow-2xl hover:shadow-yellow-500/20">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-500 to-transparent transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                    
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 rounded-full border-4 border-yellow-500/30 flex items-center justify-center text-2xl font-black text-yellow-500 group-hover:border-yellow-500 transition-colors">
                            06
                        </div>
                        <div class="ml-4 w-0 h-0 border-t-8 border-t-transparent border-b-8 border-b-transparent border-l-12 border-l-yellow-500/30 group-hover:border-l-yellow-500 transition-colors"></div>
                    </div>
                    
                    <p class="text-gray-300 text-sm leading-relaxed group-hover:text-white transition-colors">
                        Tercerizamos el departamento de comercio exterior de empresas de diversos rubros y áreas de actividad. Búsqueda y armado integral de negocios.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black border-t border-yellow-500/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-12">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-yellow-500/10 rounded-lg flex items-center justify-center">
                            <img src="images/logo.png" alt="IA Groups Logo" class="w-full h-full object-contain">
                        </div>
                        <h3 class="text-xl font-bold text-yellow-500">IA GROUPS</h3>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Conectando el mundo a través de soluciones logísticas inteligentes. Tu socio en comercio internacional.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-yellow-500 font-bold mb-4 uppercase tracking-wide text-sm">Enlaces Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-yellow-500 transition-colors text-sm">Inicio</a></li>
                        <li><a href="{{ route('nosotros') }}" class="text-gray-400 hover:text-yellow-500 transition-colors text-sm">Nosotros</a></li>
                        <li><a href="#servicios" class="text-gray-400 hover:text-yellow-500 transition-colors text-sm">Servicios</a></li>
                    </ul>
                </div>
                
                <!-- Services -->
                <div>
                    <h4 class="text-yellow-500 font-bold mb-4 uppercase tracking-wide text-sm">Servicios</h4>
                    <ul class="space-y-2">
                        <li><a href="/maritimo" class="text-gray-400 hover:text-yellow-500 transition-colors text-sm">Transporte Marítimo</a></li>
                        <li><a href="/aereo" class="text-gray-400 hover:text-yellow-500 transition-colors text-sm">Transporte Aéreo</a></li>
                        <li><a href="/terrestre" class="text-gray-400 hover:text-yellow-500 transition-colors text-sm">Transporte Terrestre</a></li>
                        <li><a href="/impuestos" class="text-gray-400 hover:text-yellow-500 transition-colors text-sm">Gestión de Impuestos</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h4 class="text-yellow-500 font-bold mb-4 uppercase tracking-wide text-sm">Contacto</h4>
                    <ul class="space-y-3">
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-yellow-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-gray-400 text-sm">info@iagroups.com</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-yellow-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span class="text-gray-400 text-sm">+591 64700457</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-yellow-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-gray-400 text-sm">Tarija, Bolivia</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-yellow-500/20 mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <p class="text-gray-500 text-sm">
                        © 2025 IA GROUPS. Todos los derechos reservados.
                    </p>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
    
    <script>
        // Mobile Menu System
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuLinks = document.querySelectorAll('.mobile-menu-link');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
            setTimeout(() => {
                mobileMenu.style.opacity = '1';
            }, 10);
        });
        
        closeMobileMenu.addEventListener('click', () => {
            mobileMenu.style.opacity = '0';
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
            }, 300);
        });
        
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.style.opacity = '0';
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);
            });
        });
    </script>
</body>
</html>
