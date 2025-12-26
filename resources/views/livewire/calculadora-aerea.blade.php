<div class="min-h-screen bg-gradient-to-br from-gray-950 via-black to-gray-900 text-white overflow-x-hidden">
    <!-- Background Effects - Subtle -->
    <div class="fixed inset-0 opacity-5 pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-yellow-500/30 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-amber-500/30 rounded-full blur-3xl animate-pulse"
            style="animation-delay: 1s;"></div>
    </div>

    <header class="bg-white/5 backdrop-blur-xl border-b border-yellow-500/20 sticky top-0 z-50 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-6">
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center space-x-4 group">
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-yellow-500/10 border-2 border-yellow-500/30 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                        <img src="/images/logo.png" alt="IA Groups Logo" class="w-full h-full object-contain">
                        <svg class="w-7 h-7 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z" />
                        </svg>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-widest text-yellow-500">IA GROUPS</h1>
                </a>
                <a href="/"
                    class="text-yellow-400 hover:text-yellow-300 font-medium transition-colors flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Volver</span>
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
        <div class="text-center mb-12">
            <h2
                class="text-4xl sm:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-amber-400 to-yellow-500 mb-4">
                CALCULADORA <span class="text-yellow-500">AÉREA</span>
            </h2>
            <p class="text-gray-300 text-lg max-w-2xl mx-auto leading-relaxed">Calcula el costo de tus envíos aéreos
                express</p>
            <div class="w-24 h-1 bg-gradient-to-r from-yellow-500 to-amber-500 mx-auto mt-4 rounded-full"></div>
        </div>

        @if (session()->has('success'))
        <div
            class="bg-yellow-500/10 border-l-4 border-yellow-500 text-yellow-500 px-6 py-4 rounded-xl mb-8 backdrop-blur-sm">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif
        @if (session()->has('error'))
        <div
            class="bg-red-500/10 border-l-4 border-red-500 text-red-400 px-6 py-4 rounded-xl mb-8 backdrop-blur-sm">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">

                {{-- <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl transition-all duration-300 hover:border-yellow-500/30">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="block text-yellow-500 font-bold text-xs uppercase tracking-widest">Aeropuerto Origen</label>
                            <input type="text" wire:model="origen" placeholder="Ej: JFK - Nueva York"
                                class="w-full bg-black/30 border-2 border-white/10 text-white px-4 py-3.5 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all placeholder-gray-600">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-yellow-500 font-bold text-xs uppercase tracking-widest">Aeropuerto Destino</label>
                            <input type="text" wire:model="destino" placeholder="Ej: EZE - Buenos Aires"
                                class="w-full bg-black/30 border-2 border-white/10 text-white px-4 py-3.5 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all placeholder-gray-600">
                        </div>
                    </div>
                </div> --}}

                <div
                    class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl transition-all duration-300 hover:border-yellow-500/30">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="block text-yellow-500 font-bold mb-2 text-xs uppercase tracking-widest">Valor
                                Mercancía (USD)</label>
                            <input type="number" wire:model="valorMercancia" step="1" placeholder="2000"
                                class="w-full bg-black/30 border-2 border-white/10 text-white px-4 py-3.5 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-yellow-500 font-bold text-xs uppercase tracking-widest">Peso (kg)
                                *</label>
                            <input type="number" wire:model="peso" step="1" placeholder="100"
                                class="w-full bg-black/30 border-2 border-white/10 text-white px-4 py-3.5 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all">
                        </div>
                    </div>
                    <p class="text-yellow-500/70 text-xs mt-4 pl-1">* Se cobrará por peso volumétrico si es mayor
                        (factor 166 kg/m³)</p>
                </div>

                <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl transition-all duration-300 hover:border-yellow-500/30"
                    x-data="{ tab: 'dimensiones' }">
                    <h3 class="text-yellow-500 font-bold mb-4 text-xs uppercase tracking-widest">Calculadora Volumen
                    </h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label class="block text-gray-400 text-xs">Largo (cm)</label>
                            <input type="number" wire:model.live="largo" placeholder="50"
                                class="w-full bg-black/30 border-2 border-white/10 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-yellow-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-gray-400 text-xs">Ancho (cm)</label>
                            <input type="number" wire:model.live="ancho" placeholder="40"
                                class="w-full bg-black/30 border-2 border-white/10 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-yellow-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-gray-400 text-xs">Alto (cm)</label>
                            <input type="number" wire:model.live="alto" placeholder="30"
                                class="w-full bg-black/30 border-2 border-white/10 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-yellow-500 transition-all">
                        </div>
                    </div>
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
                <div
                    class="bg-white/5 backdrop-blur-xl border-2 border-white/10 rounded-2xl p-6 shadow-2xl transition-all duration-300 hover:border-yellow-500/30 sticky top-24">
                    <h2 class="text-2xl font-black text-yellow-500 mb-6 uppercase tracking-widest">Resultado</h2>

                    @if ($resultado !== null)
                    <div
                        class="bg-gradient-to-br from-yellow-500/10 to-amber-500/10 border-2 border-yellow-500 rounded-xl p-6 mb-6 text-center transition-all hover:shadow-yellow-500/20 hover:shadow-xl">
                        <p class="text-sm font-bold text-yellow-400 mb-2 uppercase tracking-widest">Total Estimado
                        </p>
                        <p class="text-5xl font-black text-yellow-400">${{ $resultado }}</p>
                        <p class="text-xs text-gray-400 mt-2">USD</p>
                    </div>

                    @if (count($desglose) > 0)
                    <div class="space-y-4" x-data="{ showDetailed: false }">
                        <h3
                            class="font-bold text-yellow-500 uppercase text-xs tracking-widest border-b border-yellow-500/20 pb-3">
                            Resumen de Cotización
                        </h3>

                        @php
                        $mainItems = [];
                        $detailedItems = [];
                        foreach ($desglose as $concepto => $valor) {
                        $trimmedConcepto = trim($concepto);
                        $isDetailed = str_contains($concepto, '─') ||
                        str_contains($concepto, '├─') ||
                        str_contains($concepto, '└─') ||
                        str_starts_with($trimmedConcepto, 'Subtotal');

                        if ($isDetailed) {
                        $detailedItems[$concepto] = $valor;
                        } else {
                        $mainItems[$concepto] = $valor;
                        }
                        }
                        @endphp

                        <!-- Items Principales -->
                        <div class="space-y-2">
                            @foreach ($mainItems as $concepto => $valor)
                            <div class="flex justify-between items-center py-2 px-4 bg-white/5 rounded-lg border border-white/5">
                                <span class="text-gray-300 text-sm font-medium">{{ trim($concepto) }}</span>
                                <span class="font-bold text-white text-sm">
                                    {{ is_numeric($valor) ? '$' . number_format($valor, 2) : $valor }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @if (count($detailedItems) > 0)
                        <button @click="showDetailed = !showDetailed"
                            class="w-full flex items-center justify-between py-3 px-4 bg-yellow-500/10 border border-yellow-500/30 rounded-xl text-yellow-500 hover:bg-yellow-500/20 transition-all group">
                            <span class="text-sm font-bold uppercase tracking-wider">Ver Desglose Detallado</span>
                            <svg class="w-5 h-5 transform transition-transform duration-300" :class="showDetailed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Sección Detallada (Accordion) -->
                        <div x-show="showDetailed"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            class="space-y-1 pl-4 border-l-2 border-yellow-500/20 mt-2">
                            @foreach ($detailedItems as $concepto => $valor)
                            @php
                            $isHeader = str_contains($concepto, '─') && !str_contains($concepto, '├─') && !str_contains($concepto, '└─');
                            $isSubtotal = str_contains($concepto, 'Subtotal');
                            @endphp

                            @if ($isHeader)
                            <div class="pt-4 pb-1">
                                <span class="text-yellow-500/70 text-[10px] font-black uppercase tracking-widest">{{ ltrim($concepto, '─ ') }}</span>
                            </div>
                            @else
                            <div class="flex justify-between items-center py-1.5 px-3 {{ $isSubtotal ? 'bg-white/5 rounded-md border-t border-white/10 mt-1 mb-2' : '' }}">
                                <span class="{{ $isSubtotal ? 'text-white font-bold text-xs' : 'text-gray-400 text-[11px]' }}">
                                    {{ ltrim($concepto, ' ├─└─') }}
                                </span>
                                @if ($valor !== null)
                                <span class="{{ $isSubtotal ? 'text-yellow-400 font-bold text-xs' : 'text-gray-300 text-[11px]' }}">
                                    {{ is_numeric($valor) ? '$' . number_format($valor, 2) : $valor }}
                                </span>
                                @endif
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif
                    @else
                    <div class="text-center py-16">
                        <div
                            class="w-20 h-20 mx-auto mb-6 bg-yellow-500/5 border-2 border-yellow-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-yellow-500/30" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">Completa el formulario para generar tu
                            cotización</p>
                    </div>
                    @endif

                    {{-- Pregunta interactiva sobre el precio --}}
                    @if ($mostrarPregunta && $resultado !== null)
                    <div class="mt-8 bg-gradient-to-br from-yellow-500/10 to-amber-500/10 border-2 border-yellow-500 rounded-xl p-5 animate-pulse"
                        style="animation-duration: 2s;">
                        @if ($respuestaUsuario === null)
                        {{-- Pregunta inicial --}}
                        <h3 class="text-xl font-black text-yellow-500 text-center mb-5">
                            ¿Te gusta el precio?
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            <button wire:click="responder('si')"
                                class="bg-green-500 hover:bg-green-400 text-white font-black py-3 px-4 rounded-lg transition-all transform hover:scale-105 shadow-lg shadow-green-500/30 uppercase text-sm">
                                Sí, ¡me encanta!
                            </button>
                            <button wire:click="responder('no')"
                                class="bg-red-500 hover:bg-red-400 text-white font-black py-3 px-4 rounded-lg transition-all transform hover:scale-105 shadow-lg shadow-red-500/30 uppercase text-sm">
                                No, muy caro
                            </button>
                        </div>
                        @else
                        <div class="text-center space-y-6">
                            @if ($respuestaUsuario === 'si')
                            <div class="animate-bounce"><span class="text-green-400 text-5xl font-black italic">¡PERFECTO!</span></div>
                            <p class="text-lg font-bold text-gray-200">Nuestros especialistas están listos para ayudarte:</p>
                            @else
                            <div class="animate-pulse"><span class="text-yellow-400 text-5xl font-black italic">TRANQUILO</span></div>
                            <p class="text-lg font-bold text-gray-200">¡Podemos ajustarlo! Habla con un experto:</p>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4 text-left">
                                @php
                                $contactos =
                                ['area' => 'Auction', 'num' => '59164580634', 'color' => 'yellow'],
                                ['area' => 'Academy', 'num' => '59164700293', 'color' => 'yellow'],
                                ['area' => 'Imports & Exports', 'num' => '59172976032', 'color' => 'yellow'],
                                ['area' => 'Agemte de carga', 'num' => '5974518652', 'color' => 'yellow'],
                                ['area' => 'Negocios', 'num' => '59164583783', 'color' => 'yellow'],
                                ['area' => 'IA Groups', 'num' => '59172981315', 'color' => 'yellow'],
                                @endphp

                                @foreach($contactos as $c)
                                @php
                                $mensajeTexto = "Hola " . $c['area'] . "! Vengo de la cotización de $" . number_format($resultado, 2) . " USD";
                                $urlWebWa = "https://web.whatsapp.com/send?phone=" . $c['num'] . "&text=" . urlencode($mensajeTexto);
                                @endphp
                                <a href="{{ $urlWebWa }}"
                                    target="_blank"
                                    class="flex items-center justify-between bg-gray-800/50 p-3 rounded-lg border border-gray-700 hover:border-{{ $c['color'] }}-500 transition-all group">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-400 uppercase tracking-widest">{{ $c['area'] }}</span>
                                        <span class="text-white font-bold">+{{ $c['num'] }}</span>
                                    </div>
                                </a>
                                @endforeach
                            </div>

                            <p class="text-xs text-gray-500 pt-4 italic">Haz clic en el área correspondiente para una atención personalizada.</p>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div
            class="mt-16 bg-gradient-to-r from-yellow-500/10 to-amber-500/10 border-l-4 border-yellow-500 rounded-xl p-6 backdrop-blur-sm">
            <div class="flex items-start space-x-4">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-yellow-500 font-bold mb-2 uppercase tracking-widest text-sm">Nota Importante</h4>
                    <p class="text-gray-300 text-sm leading-relaxed">
                        Los cálculos son estimaciones basados en tarifas estándar. El precio final puede variar según
                        disponibilidad, aerolínea y condiciones especiales del servicio.
                        <strong class="text-yellow-500">Contacta con nuestro equipo</strong> para una cotización
                        oficial y personalizada.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>