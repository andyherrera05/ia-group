<div class="min-h-screen bg-gradient-to-br from-gray-950 via-black to-gray-900 text-white overflow-x-hidden">
    <!-- Background Effects - Subtle -->
    <div class="fixed inset-0 opacity-5 pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-yellow-500/30 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-amber-500/30 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <header class="bg-white/5 backdrop-blur-xl border-b border-yellow-500/20 sticky top-0 z-50 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-6">
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center space-x-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-yellow-500/10 border-2 border-yellow-500/30 rounded-lg flex items-center justify-center">
                        <img src="/images/logo.png" alt="IA Groups Logo" class="w-full h-full object-contain">
                        <svg class="w-7 h-7 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                        </svg>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-widest text-yellow-500">IA GROUPS</h1>
                </a>
                <a href="/" class="text-yellow-400 hover:text-yellow-300 font-medium transition-colors flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Volver</span>
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
        <div class="text-center mb-12">
            <h2 class="text-4xl sm:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-amber-400 to-yellow-500 mb-4">
                CALCULADORA <span class="text-yellow-500">TERRESTRE</span>
            </h2>
            <p class="text-gray-300 text-lg max-w-2xl mx-auto leading-relaxed">Calcula el costo de tus envíos terrestres</p>
            <div class="w-24 h-1 bg-gradient-to-r from-yellow-500 to-amber-500 mx-auto mt-4 rounded-full"></div>
        </div>

        @if (session()->has('success'))
            <div class="bg-yellow-500/10 border-l-4 border-yellow-500 text-yellow-500 px-6 py-4 rounded-xl mb-8 backdrop-blur-sm">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-500/10 border-l-4 border-red-500 text-red-400 px-6 py-4 rounded-xl mb-8 backdrop-blur-sm">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl transition-all duration-300 hover:border-yellow-500/50 hover:shadow-yellow-500/10">
                    <label class="block text-yellow-500 font-bold mb-4 text-sm uppercase tracking-widest">Tipo de Servicio</label>
                    <div class="grid grid-cols-3 gap-4">
                        <button wire:click="$set('tipoCarga', 'lcl')"
                            class="group relative overflow-hidden px-4 py-4 border-2 rounded-xl font-bold transition-all transform hover:scale-105 {{ $tipoCarga === 'lcl' ? 'border-yellow-500 bg-gradient-to-br from-yellow-500/20 to-amber-500/20 text-yellow-400 shadow-lg shadow-yellow-500/20' : 'border-white/10 text-gray-300 hover:border-yellow-500/50 hover:text-yellow-400' }}">
                            <span class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-amber-500 opacity-0 group-hover:opacity-10 transition-opacity"></span>
                            <span class="relative block text-center">
                                <span class="text-lg">PARCIAL</span>
                                <span class="block text-xs font-normal mt-1 text-gray-400">Carga compartida</span>
                            </span>
                        </button>
                        <button wire:click="$set('tipoCarga', 'fcl')"
                            class="group relative overflow-hidden px-4 py-4 border-2 rounded-xl font-bold transition-all transform hover:scale-105 {{ $tipoCarga === 'fcl' ? 'border-yellow-500 bg-gradient-to-br from-yellow-500/20 to-amber-500/20 text-yellow-400 shadow-lg shadow-yellow-500/20' : 'border-white/10 text-gray-300 hover:border-yellow-500/50 hover:text-yellow-400' }}">
                            <span class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-amber-500 opacity-0 group-hover:opacity-10 transition-opacity"></span>
                            <span class="relative block text-center">
                                <span class="text-lg">COMPLETO</span>
                                <span class="block text-xs font-normal mt-1 text-gray-400">Camión completo</span>
                            </span>
                        </button>
                        <button wire:click="$set('tipoCarga', 'uld')"
                            class="group relative overflow-hidden px-4 py-4 border-2 rounded-xl font-bold transition-all transform hover:scale-105 {{ $tipoCarga === 'uld' ? 'border-yellow-500 bg-gradient-to-br from-yellow-500/20 to-amber-500/20 text-yellow-400 shadow-lg shadow-yellow-500/20' : 'border-white/10 text-gray-300 hover:border-yellow-500/50 hover:text-yellow-400' }}">
                            <span class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-amber-500 opacity-0 group-hover:opacity-10 transition-opacity"></span>
                            <span class="relative block text-center">
                                <span class="text-lg">PALLETS</span>
                                <span class="block text-xs font-normal mt-1 text-gray-400">Paletizado</span>
                            </span>
                        </button>
                    </div>
                </div>

                <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl transition-all duration-300 hover:border-yellow-500/30">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="block text-yellow-500 font-bold text-xs uppercase tracking-widest">Ciudad Origen</label>
                            <input type="text" wire:model="origen" placeholder="Ej: Buenos Aires"
                                class="w-full bg-black/30 border-2 border-white/10 text-white px-4 py-3.5 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all placeholder-gray-600">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-yellow-500 font-bold text-xs uppercase tracking-widest">Ciudad Destino</label>
                            <input type="text" wire:model="destino" placeholder="Ej: Córdoba"
                                class="w-full bg-black/30 border-2 border-white/10 text-white px-4 py-3.5 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all placeholder-gray-600">
                        </div>
                    </div>
                </div>

                <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl transition-all duration-300 hover:border-yellow-500/30">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="space-y-2">
                            <label class="block text-yellow-500 font-bold text-xs uppercase tracking-widest">Peso (kg) *</label>
                            <input type="number" wire:model="peso" step="0.01" placeholder="500"
                                class="w-full bg-black/30 border-2 border-white/10 text-white px-4 py-3.5 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-yellow-500 font-bold text-xs uppercase tracking-widest">Volumen (m³) *</label>
                            <input type="number" wire:model="volumen" step="0.001" placeholder="1.5"
                                class="w-full bg-black/30 border-2 border-white/10 text-white px-4 py-3.5 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-yellow-500 font-bold text-xs uppercase tracking-widest">Distancia (km) *</label>
                            <input type="number" wire:model="distancia" step="1" placeholder="700"
                                class="w-full bg-black/30 border-2 border-white/10 text-white px-4 py-3.5 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all">
                        </div>
                    </div>
                    <p class="text-yellow-500/70 text-xs mt-4 pl-1">* Se usa el mayor entre peso y peso volumétrico</p>
                </div>

                <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl transition-all duration-300 hover:border-yellow-500/30">
                    <h3 class="text-yellow-500 font-bold mb-4 text-xs uppercase tracking-widest">Calculadora Volumen</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label class="block text-gray-400 text-xs">Largo (cm)</label>
                            <input type="number" wire:model="largo" wire:change="calcularVolumen" placeholder="100"
                                class="w-full bg-black/30 border-2 border-white/10 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-yellow-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-gray-400 text-xs">Ancho (cm)</label>
                            <input type="number" wire:model="ancho" wire:change="calcularVolumen" placeholder="80"
                                class="w-full bg-black/30 border-2 border-white/10 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-yellow-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-gray-400 text-xs">Alto (cm)</label>
                            <input type="number" wire:model="alto" wire:change="calcularVolumen" placeholder="60"
                                class="w-full bg-black/30 border-2 border-white/10 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-yellow-500 transition-all">
                        </div>
                    </div>
                </div>

                <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl transition-all duration-300 hover:border-yellow-500/30">
                    <label class="block text-yellow-500 font-bold mb-2 text-xs uppercase tracking-widest">Valor Mercancía (USD)</label>
                    <input type="number" wire:model="valorMercancia" step="0.01" placeholder="3000"
                        class="w-full bg-black/30 border-2 border-white/10 text-white px-4 py-3.5 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all">
                    <p class="text-yellow-500/70 text-xs mt-3 pl-1">Para calcular el seguro de la carga</p>
                </div>

                <div class="flex space-x-4">
                    <button wire:click="calcular" 
                        class="flex-1 bg-gradient-to-r from-yellow-500 via-amber-500 to-yellow-500 hover:from-yellow-400 hover:via-amber-400 hover:to-yellow-400 text-black font-black py-4 px-6 text-lg uppercase tracking-wider transition-all transform hover:scale-105 hover:-translate-y-1 shadow-xl hover:shadow-yellow-500/50 rounded-xl">
                        CALCULAR
                    </button>
                    <button wire:click="limpiar" 
                        class="bg-white/5 border-2 border-white/10 hover:border-yellow-500 text-gray-300 hover:text-yellow-400 font-bold py-4 px-6 uppercase transition-all transform hover:scale-105 rounded-xl">
                        LIMPIAR
                    </button>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white/5 backdrop-blur-xl border-2 border-white/10 rounded-2xl p-6 shadow-2xl transition-all duration-300 hover:border-yellow-500/30 sticky top-24">
                    <h2 class="text-2xl font-black text-yellow-500 mb-6 uppercase tracking-widest">Resultado</h2>
                    
                    @if ($resultado !== null)
                        <div class="bg-gradient-to-br from-yellow-500/10 to-amber-500/10 border-2 border-yellow-500 rounded-xl p-6 mb-6 text-center transition-all hover:shadow-yellow-500/20 hover:shadow-xl">
                            <p class="text-sm font-bold text-yellow-400 mb-2 uppercase tracking-widest">Total Estimado</p>
                            <p class="text-5xl font-black text-yellow-400">${{ $resultado }}</p>
                            <p class="text-xs text-gray-400 mt-2">USD</p>
                        </div>
                        
                        @if (count($desglose) > 0)
                            <div class="space-y-3">
                                <h3 class="font-bold text-yellow-500 uppercase text-sm tracking-widest mb-4 border-b border-yellow-500/20 pb-3">Desglose:</h3>
                                @foreach ($desglose as $concepto => $valor)
                                    <div class="flex justify-between items-center py-3 border-b border-white/5 hover:bg-white/5 px-3 rounded-lg transition-all">
                                        <span class="text-gray-300 text-sm font-medium">{{ $concepto }}</span>
                                        <span class="font-bold text-white text-sm">
                                            @if (is_numeric($valor))
                                                {{ $valor }}
                                            @else
                                                ${{ $valor }}
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="text-center py-16">
                            <div class="w-20 h-20 mx-auto mb-6 bg-yellow-500/5 border-2 border-yellow-500/20 rounded-full flex items-center justify-center">
                                <svg class="w-10 h-10 text-yellow-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">Completa el formulario para generar tu cotización</p>
                        </div>
                    @endif
                    
                    {{-- Pregunta interactiva sobre el precio --}}
                    @if ($mostrarPregunta && $resultado !== null)
                        <div class="mt-8 bg-gradient-to-br from-yellow-500/10 to-amber-500/10 border-2 border-yellow-500 rounded-xl p-5 animate-pulse" style="animation-duration: 2s;">
                            @if ($respuestaUsuario === null)
                                {{-- Pregunta inicial --}}
                                <h3 class="text-xl font-black text-yellow-500 text-center mb-5">
                                    ¿Te gusta el precio?
                                </h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <button wire:click="responder('si')" 
                                            class="bg-green-500 hover:bg-green-400 text-white font-black py-3 px-4 rounded-lg transition-all transform hover:scale-105 shadow-lg shadow-green-500/30 uppercase text-sm">
                                        😊 SÍ
                                    </button>
                                    <button wire:click="responder('no')" 
                                            class="bg-red-500 hover:bg-red-400 text-white font-black py-3 px-4 rounded-lg transition-all transform hover:scale-105 shadow-lg shadow-red-500/30 uppercase text-sm">
                                        😕 NO
                                    </button>
                                </div>
                            @else
                                {{-- Respuesta basada en la elección --}}
                                <div class="text-center space-y-4">
                                    @if ($respuestaUsuario === 'si')
                                        <svg class="w-16 h-16 mx-auto text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <h3 class="text-lg font-black text-green-500 mb-3">¡Perfecto!</h3>
                                        <p class="text-gray-300 text-sm mb-6">Contáctanos para confirmar tu envío</p>
                                    @else
                                        <svg class="w-16 h-16 mx-auto text-yellow-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <h3 class="text-lg font-black text-yellow-500 mb-3">¡Podemos mejorarlo!</h3>
                                        <p class="text-gray-300 text-sm mb-6">Hablemos de tu necesidad específica</p>
                                    @endif
                                    
                                    <a href="https://wa.me/59164700293?text=Hola !%20Vi%20el%20precio%20del%20envío%20terrestre%20de%20${{ $resultado }}%20y%20me%20gustaría%20más%20información." 
                                       target="_blank"
                                       class="inline-flex items-center space-x-3 bg-green-600 hover:bg-green-500 text-white font-black py-3 px-6 rounded-lg transition-all transform hover:scale-105 shadow-xl shadow-green-600/40">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                        </svg>
                                        <span>Contáctanos</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-16 bg-gradient-to-r from-yellow-500/10 to-amber-500/10 border-l-4 border-yellow-500 rounded-xl p-6 backdrop-blur-sm">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-yellow-500 font-bold mb-2 uppercase tracking-widest text-sm">Nota Importante</h4>
                    <p class="text-gray-300 text-sm leading-relaxed">
                        Los peajes son estimados basados en rutas estándar. El precio final puede variar según disponibilidad, tipo de vehículo y condiciones especiales del servicio.
                        <strong class="text-yellow-500">Contacta con nuestro equipo</strong> para una cotización oficial y personalizada.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>