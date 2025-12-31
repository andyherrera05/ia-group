{{-- 
    ============================================================
    COMPONENTE: Formulario CIF (FOB + Flete + Seguro)
    ============================================================
    
    DESCRIPCIÓN:
    Formulario principal con campos para calcular el valor CIF:
    - Valor FOB (Mercancía)
    - Valor Flete (manual o % del FOB)
    - Valor Seguro (manual o % del FOB)
    
    VARIABLES LIVEWIRE REQUERIDAS:
    - $valorMercancia: float - Valor FOB en USD
    - $valorFlete: float - Costo del flete en USD
    - $valorSeguro: float - Costo del seguro en USD
    - $calcularFleteAuto: bool - Calcular flete como % del FOB
    - $calcularSeguroAuto: bool - Calcular seguro como % del FOB
    - $porcentajeFlete: float - % para flete automático (default 5%)
    - $porcentajeSeguro: float - % para seguro automático (default 2%)
    
    USO:
    @include('livewire.calculadora-impuestos.formulario-cif')
    ============================================================
--}}

<div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl">
    {{-- Título del formulario --}}
    <h3 class="text-yellow-500 font-bold mb-4 text-lg uppercase tracking-widest flex items-center">
        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
        </svg>
        Valores para Cálculo CIF
    </h3>
    <p class="text-gray-400 text-sm mb-6">Ingresa los valores en USD para calcular los impuestos de importación</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        {{-- Campo: Valor Mercancía (FOB) --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2 flex items-center">
                <svg class="w-4 h-4 mr-2 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                Valor FOB (Mercancía) *
            </label>
            <div class="relative">
                <span class="absolute left-4 top-3.5 text-yellow-500 font-bold">$</span>
                <input type="number" wire:model.live="valorMercancia" step="0.01" placeholder="10000"
                    class="w-full bg-black/40 border-2 border-yellow-500/30 text-white pl-10 pr-4 py-3.5 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all placeholder-gray-500" required>
            </div>
            <p class="text-xs text-gray-500 mt-1">
                <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                Valor de la mercancía en origen (Free On Board)
            </p>
        </div>

        {{-- Campo: Valor Flete --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2 flex items-center justify-between">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                    </svg>
                    Valor Flete
                </span>
                <label class="flex items-center text-xs cursor-pointer">
                    <input type="checkbox" wire:model.live="calcularFleteAuto" class="mr-1 accent-blue-500">
                    <span class="text-blue-400">Auto {{ $porcentajeFlete }}% FOB</span>
                </label>
            </label>
            @if($calcularFleteAuto && $valorMercancia)
                <div class="w-full bg-blue-500/20 border-2 border-blue-500/30 text-blue-400 px-4 py-3.5 rounded-xl font-bold">
                    $ {{ number_format(floatval($valorMercancia) * ($porcentajeFlete / 100), 2) }}
                    <span class="text-xs text-blue-300 ml-2">({{ $porcentajeFlete }}% de FOB)</span>
                </div>
            @else
                <div class="relative">
                    <span class="absolute left-4 top-3.5 text-blue-400 font-bold">$</span>
                    <input type="number" wire:model="valorFlete" step="0.01" placeholder="500"
                        class="w-full bg-black/40 border-2 border-blue-500/30 text-white pl-10 pr-4 py-3.5 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all placeholder-gray-500"
                        {{ $calcularFleteAuto ? 'disabled' : '' }}>
                </div>
            @endif
            <p class="text-xs text-gray-500 mt-1">
                Costo del transporte internacional
            </p>
        </div>

        {{-- Campo: Valor Seguro --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2 flex items-center justify-between">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Valor Seguro
                </span>
                <label class="flex items-center text-xs cursor-pointer">
                    <input type="checkbox" wire:model.live="calcularSeguroAuto" class="mr-1 accent-green-500">
                    <span class="text-green-400">Auto {{ $porcentajeSeguro }}% FOB</span>
                </label>
            </label>
            @if($calcularSeguroAuto && $valorMercancia)
                <div class="w-full bg-green-500/20 border-2 border-green-500/30 text-green-400 px-4 py-3.5 rounded-xl font-bold">
                    $ {{ number_format(floatval($valorMercancia) * ($porcentajeSeguro / 100), 2) }}
                    <span class="text-xs text-green-300 ml-2">({{ $porcentajeSeguro }}% de FOB)</span>
                </div>
            @else
                <div class="relative">
                    <span class="absolute left-4 top-3.5 text-green-400 font-bold">$</span>
                    <input type="number" wire:model="valorSeguro" step="0.01" placeholder="200"
                        class="w-full bg-black/40 border-2 border-green-500/30 text-white pl-10 pr-4 py-3.5 rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all placeholder-gray-500"
                        {{ $calcularSeguroAuto ? 'disabled' : '' }}>
                </div>
            @endif
            <p class="text-xs text-gray-500 mt-1">
                Seguro de transporte (opcional)
            </p>
        </div>

        {{-- Campo: Tipo de Cambio --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2 flex items-center">
                <svg class="w-4 h-4 mr-2 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                </svg>
                Tipo de Cambio (Bs/USD)
            </label>
            <div class="relative">
                <span class="absolute left-4 top-3.5 text-purple-400 font-bold">Bs</span>
                <input type="number" wire:model="tipoCambio" step="0.01" placeholder="6.96"
                    class="w-full bg-black/40 border-2 border-purple-500/30 text-white pl-12 pr-4 py-3.5 rounded-xl focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all placeholder-gray-500">
            </div>
            <p class="text-xs text-gray-500 mt-1">
                Tipo de cambio oficial Bolivia (default: 6.96)
            </p>
        </div>
    </div>

    {{-- Fórmula CIF y Resumen --}}
    <div class="mt-6 p-4 bg-gradient-to-r from-yellow-500/5 to-amber-500/5 border border-yellow-500/20 rounded-xl">
        <div class="flex items-center justify-center space-x-3 text-sm mb-3">
            <span class="text-gray-400">CIF =</span>
            <span class="px-3 py-1 bg-yellow-500/20 rounded-lg text-yellow-400 font-bold">FOB</span>
            <span class="text-gray-400">+</span>
            <span class="px-3 py-1 bg-blue-500/20 rounded-lg text-blue-400 font-bold">Flete</span>
            <span class="text-gray-400">+</span>
            <span class="px-3 py-1 bg-green-500/20 rounded-lg text-green-400 font-bold">Seguro</span>
        </div>
        
        @if($valorMercancia)
            @php
                $fob = floatval($valorMercancia);
                $flete = $calcularFleteAuto ? $fob * ($porcentajeFlete / 100) : floatval($valorFlete ?: 0);
                $seguro = $calcularSeguroAuto ? $fob * ($porcentajeSeguro / 100) : floatval($valorSeguro ?: 0);
                $cif = $fob + $flete + $seguro;
                $cifBs = $cif * floatval($tipoCambio ?: 6.96);
            @endphp
            <div class="grid grid-cols-2 gap-4 text-center">
                <div class="p-3 bg-black/30 rounded-lg">
                    <p class="text-xs text-gray-400 mb-1">CIF en USD</p>
                    <p class="text-xl font-black text-yellow-400">${{ number_format($cif, 2) }}</p>
                </div>
                <div class="p-3 bg-black/30 rounded-lg">
                    <p class="text-xs text-gray-400 mb-1">CIF en Bolivianos</p>
                    <p class="text-xl font-black text-purple-400">Bs {{ number_format($cifBs, 2) }}</p>
                </div>
            </div>
        @endif
    </div>
    
    {{-- Info sobre tasas --}}
    <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
        <div class="p-2 bg-white/5 rounded-lg text-center">
            <p class="text-gray-500">Flete Sugerido</p>
            <p class="text-blue-400 font-bold">5% FOB</p>
        </div>
        <div class="p-2 bg-white/5 rounded-lg text-center">
            <p class="text-gray-500">Seguro Sugerido</p>
            <p class="text-green-400 font-bold">2% FOB</p>
        </div>
        <div class="p-2 bg-white/5 rounded-lg text-center">
            <p class="text-gray-500">IVA Bolivia</p>
            <p class="text-red-400 font-bold">14.94%</p>
        </div>
        <div class="p-2 bg-white/5 rounded-lg text-center">
            <p class="text-gray-500">TC Oficial</p>
            <p class="text-purple-400 font-bold">Bs 6.96</p>
        </div>
    </div>
</div>
