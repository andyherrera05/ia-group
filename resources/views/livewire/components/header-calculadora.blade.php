{{-- 
    ============================================================
    COMPONENTE: Header de Calculadora
    ============================================================
    
    DESCRIPCIÓN:
    Header sticky con logo, título y botón de volver.
    Se usa en todas las calculadoras.
    
    PERSONALIZACIÓN:
    Cambiar el título pasando el parámetro $titulo
    
    USO:
    @include('livewire.components.header-calculadora')
    ============================================================
--}}

<header class="bg-white/5 backdrop-blur-xl border-b border-yellow-500/20 sticky top-0 z-50 shadow-2xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-6">
        <div class="flex items-center justify-between">
            {{-- Logo y nombre de la empresa --}}
            <a href="/" class="flex items-center space-x-4 group">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-yellow-500/10 border-2 border-yellow-500/30 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                    <img src="/images/logo.png" alt="IA Groups Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-widest text-yellow-500">IA GROUPS</h1>
            </a>
            
            {{-- Botón volver --}}
            <a href="/" class="text-yellow-400 hover:text-yellow-300 font-medium transition-colors flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Volver</span>
            </a>
        </div>
    </div>
</header>
