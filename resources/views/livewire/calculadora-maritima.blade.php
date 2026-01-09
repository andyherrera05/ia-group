<div class="min-h-screen bg-gradient-to-br from-gray-950 via-black to-gray-900 text-white overflow-x-hidden">
    <!-- Background Effects - Subtle -->
    <div class="fixed inset-0 opacity-5 pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-yellow-500/30 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-amber-500/30 rounded-full blur-3xl animate-pulse"
            style="animation-delay: 1s;"></div>
    </div>

    <!-- Header - Glassmorphism CON LOGO CORREGIDO -->
    <header class="bg-white/5 backdrop-blur-xl border-b border-yellow-500/20 sticky top-0 z-50 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-6">
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center space-x-4 group">
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-yellow-500/10 border-2 border-yellow-500/30 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                        <img src="/images/logo.png" alt="IA Groups Logo" class="w-full h-full object-contain">
                        <svg class="w-7 h-7 text-yellow-500 absolute opacity-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-black tracking-widest text-yellow-500">IA GROUPS</h1>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Logística Internacional</p>
                    </div>
                </a>
                <a href="/"
                    class="text-yellow-400 hover:text-yellow-300 font-medium transition-all flex items-center space-x-2 group">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Volver</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">

        <!-- Title Section -->
        <div class="text-center mb-12">
            <h2
                class="text-4xl sm:text-5xl lg:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-amber-400 to-yellow-500 mb-3">
                CALCULADORA <span>MARÍTIMA</span>
            </h2>
            <p class="text-gray-300 text-lg max-w-2xl mx-auto leading-relaxed">
                Cotiza envíos marítimos internacionales en tiempo real
            </p>
            <div class="w-24 h-1 bg-gradient-to-r from-yellow-500 to-amber-500 mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- Messages -->
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

        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <!-- Formulario con pestañas -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Navegación de pestañas (más limpia con bucle) -->
                <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-2 shadow-xl">
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ([
                        'lcl' => ['title' => 'LCL', 'subtitle' => 'Carga Suelta'],
                        'fcl' => ['title' => 'FCL', 'subtitle' => 'Contenedor'],
                        // 'uld' => ['title' => 'ULD', 'subtitle' => 'Aéreo'],
                        ] as $key => $tab)
                        <button wire:click="setTipoCarga('{{ $key }}')"
                            wire:key="tab-{{ $key }}"
                            class="group relative overflow-hidden px-5 py-4 rounded-xl font-bold transition-all duration-300
                               {{ $tipoCarga === $key
                                   ? 'bg-gradient-to-br from-yellow-500 to-amber-500 text-black shadow-lg shadow-yellow-500/30 scale-105'
                                   : 'bg-white/5 text-gray-300 hover:bg-white/10 hover:text-yellow-400' }}">
                            <span class="relative block text-center z-10">
                                <span class="text-base sm:text-lg">{{ $tab['title'] }}</span>
                                <span
                                    class="block text-xs font-normal mt-0.5 {{ $tipoCarga === $key ? 'text-black/70' : 'text-gray-400' }}">
                                    {{ $tab['subtitle'] }}
                                </span>
                            </span>

                            @if ($tipoCarga === $key)
                            <div class="absolute inset-0 bg-white/20 blur-xl scale-150 animate-pulse"></div>
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Contenido de las pestañas con transición suave -->
                <div class="min-h-96">
                    <div wire:key="tab-lcl"
                        class="{{ $tipoCarga === 'lcl' ? 'block' : 'hidden' }} transition-all duration-500">
                        @include('livewire.calculadora-maritima.lcl')
                    </div>

                    <div wire:key="tab-fcl"
                        class="{{ $tipoCarga === 'fcl' ? 'block' : 'hidden' }} transition-all duration-500">
                        @include('livewire.calculadora-maritima.fcl')
                    </div>

                    <div wire:key="tab-uld"
                        class="{{ $tipoCarga === 'uld' ? 'block' : 'hidden' }} transition-all duration-500">
                        @include('livewire.calculadora-maritima.uld')
                    </div>
                </div>
            </div>
            <div class="lg:sticky lg:top-24">
                <div
                    class="bg-white/5 backdrop-blur-xl border-2 border-white/10 rounded-2xl p-6 shadow-2xl transition-all duration-500 hover:border-yellow-500/40">

                    <h2
                        class="text-2xl font-black text-yellow-500 mb-6 uppercase tracking-widest flex items-center">
                        <svg class="w-7 h-7 mr-3 text-yellow-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Resultado
                    </h2>

                    @if ($resultado !== null && $mostrarDesglose)
                    <!-- Tarjeta principal del total -->
                    <div id="resultado-final"
                        class="bg-gradient-to-br from-yellow-500/10 to-amber-500/10 border-2 border-yellow-500 rounded-xl p-6 mb-6 text-center hover:shadow-2xl hover:shadow-yellow-500/30 transition-all duration-500">
                        <p class="text-xs font-bold text-yellow-400 uppercase tracking-widest mb-2">Total
                            Estimado</p>
                        <p class="text-5xl font-black text-yellow-400 leading-tight">
                            ${{ number_format($resultado, 2) }}
                        </p>
                        <p class="text-xs text-gray-400 mt-2">USD - Incluye impuestos</p>
                    </div>
                    @include('livewire.partials.resultado')

                    <!-- Desglose de costos con Accordion -->
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
                        $isDetailed = (str_contains($concepto, '─') ||
                        str_contains($concepto, '├─') ||
                        str_contains($concepto, '└─') ||
                        str_starts_with($trimmedConcepto, 'Subtotal')) &&
                        !str_contains($trimmedConcepto, 'Gestión Portuaria');

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
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-300 text-sm font-medium">{{ trim($concepto) }}</span>
                                    @if(in_array(trim($concepto), ['Despacho', 'Agencia despachante', 'Impuestos']))
                                    <span class="px-1.5 py-0.5 rounded font-bold bg-green-500/20 text-green-400 border border-green-500/30" style="font-size:9px">TC OF</span>
                                    @else
                                    <span class="px-1.5 py-0.5 rounded font-bold bg-blue-500/20 text-blue-400 border border-blue-500/30" style="font-size:9px">TC BLUE</span>
                                    @endif
                                </div>
                                <span class="font-bold text-white text-sm">
                                    {{ is_numeric($valor) ? '$' . number_format($valor, 2) : $valor }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @if (count($detailedItems) > 0)
                        <button @click="showDetailed = !showDetailed"
                            class="w-full flex items-center justify-between py-3 px-4 bg-yellow-500/10 border border-yellow-500/30 rounded-xl text-yellow-500 hover:bg-yellow-500/20 transition-all group">
                            <span class="text-sm font-bold uppercase tracking-wider">Ver Desglose Detallado de Flete</span>
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

                    <!-- Botón PDF -->
                    <div class="mt-6">
                        <button wire:click="descargarPDF" wire:loading.attr="disabled"
                            class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-4 px-6 rounded-xl transition-all transform hover:scale-105 shadow-xl shadow-red-600/40 flex items-center justify-center space-x-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Descargar Cotización en PDF</span>
                        </button>
                    </div>

                    <!-- Pregunta interactiva -->
                    @if ($mostrarPregunta && $resultado !== null)
                    <div class="mt-8 bg-gradient-to-br border-2 border-yellow-500/50 rounded-xl p-6 shadow-2xl">
                        @if ($respuestaUsuario === null)
                        <h3 class="text-xl font-black text-yellow-400 text-center mb-6">¿Te gusta este precio?</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <button wire:click="responder('si')"
                                class="bg-green-600 hover:bg-green-500 text-white font-black p-2 rounded-xl transition-all transform hover:scale-105 shadow-lg shadow-green-600/30 uppercase text-sm">
                                Sí, ¡me encanta!
                            </button>
                            <button wire:click="responder('no')"
                                class="bg-red-600 hover:bg-red-500 text-white font-black py-4 rounded-xl transition-all transform hover:scale-105 shadow-lg shadow-red-600/30 uppercase text-sm">
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
                                $contactos = [
                                ['area' => 'Auction', 'num' => '59164580634', 'color' => 'yellow'],
                                ['area' => 'Academy', 'num' => '59164700293', 'color' => 'yellow'],
                                ['area' => 'Imports & Exports', 'num' => '59172976032', 'color' => 'yellow'],
                                ['area' => 'Agente de carga', 'num' => '5974518652', 'color' => 'yellow'],
                                ['area' => 'Negocios', 'num' => '59164583783', 'color' => 'yellow'],
                                ['area' => 'IA Groups', 'num' => '59172981315', 'color' => 'yellow'],
                                ];
                                shuffle($contactos);
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
                    @else
                    <!-- Estado vacío -->
                    <div class="text-center py-20">
                        <div
                            class="w-24 h-24 mx-auto mb-6 bg-yellow-500/10 border-2 border-dashed border-yellow-500/30 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-yellow-500/40" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-gray-400 font-medium">Completa los datos para ver tu cotización
                            instantánea</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Info Box -->
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
                        Estos cálculos son estimaciones basadas en tarifas estándar. El precio final puede variar según
                        disponibilidad, tipo de contenedor y condiciones especiales del servicio.
                        <strong class="text-yellow-500">Contacta con nuestro equipo</strong> para una cotización
                        oficial y personalizada.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('scroll-to-result', () => {
            setTimeout(() => {
                const element = document.getElementById('resultado-final');
                if (element) {
                    element.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }, 200);
        });
    });
</script>