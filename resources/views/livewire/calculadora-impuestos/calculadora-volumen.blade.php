{{-- 
    ============================================================
    COMPONENTE: Calculadora de Peso y Volumen
    ============================================================
    
    DESCRIPCIÓN:
    Calculadora completa que permite:
    - Calcular volumen a partir de dimensiones (cm)
    - Calcular peso volumétrico
    - Auto-rellenar campos del formulario CIF
    
    VARIABLES LIVEWIRE REQUERIDAS:
    - $largo, $ancho, $alto: Dimensiones en cm
    - $volumen: Volumen en M³
    - $peso: Peso bruto en KG
    - $cantidadBultos: Cantidad de bultos/cajas
    - $pesoVolumetrico: Peso volumétrico calculado
    
    USO:
    @include('livewire.calculadora-impuestos.calculadora-volumen')
    ============================================================
--}}

<div class="bg-white/5 backdrop-blur-xl border border-purple-500/20 rounded-2xl p-6 shadow-xl">
    {{-- Título de la sección --}}
    <h3 class="text-purple-400 font-bold mb-4 text-lg uppercase tracking-widest flex items-center">
        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        </svg>
        📦 Calculadora de Carga
    </h3>
    <p class="text-gray-400 text-sm mb-6">Ingresa las dimensiones y peso de tu mercancía para calcular automáticamente el volumen y peso volumétrico</p>

    {{-- Grid de campos --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
        {{-- Campo: Cantidad de Bultos --}}
        <div>
            <label class="block text-xs font-medium text-gray-400 mb-2">Cantidad de Bultos</label>
            <input type="number" wire:model.live="cantidadBultos" placeholder="1" min="1"
                class="w-full px-3 py-2.5 bg-black/30 border border-purple-500/30 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all text-sm">
        </div>
        
        {{-- Campo: Largo --}}
        <div>
            <label class="block text-xs font-medium text-gray-400 mb-2">Largo (cm)</label>
            <input type="number" wire:model.live="largo" wire:change="calcularVolumen" placeholder="100"
                class="w-full px-3 py-2.5 bg-black/30 border border-purple-500/30 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all text-sm">
        </div>
        
        {{-- Campo: Ancho --}}
        <div>
            <label class="block text-xs font-medium text-gray-400 mb-2">Ancho (cm)</label>
            <input type="number" wire:model.live="ancho" wire:change="calcularVolumen" placeholder="80"
                class="w-full px-3 py-2.5 bg-black/30 border border-purple-500/30 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all text-sm">
        </div>
        
        {{-- Campo: Alto --}}
        <div>
            <label class="block text-xs font-medium text-gray-400 mb-2">Alto (cm)</label>
            <input type="number" wire:model.live="alto" wire:change="calcularVolumen" placeholder="60"
                class="w-full px-3 py-2.5 bg-black/30 border border-purple-500/30 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all text-sm">
        </div>
    </div>

    {{-- Segunda fila: Peso Bruto --}}
    <div class="grid grid-cols-2 gap-4 mb-4">
        {{-- Campo: Peso Bruto --}}
        <div>
            <label class="block text-xs font-medium text-gray-400 mb-2">Peso Bruto Total (KG)</label>
            <input type="number" wire:model.live="peso" step="0.01" placeholder="500"
                class="w-full px-3 py-2.5 bg-black/30 border border-purple-500/30 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all text-sm">
            <p class="text-xs text-gray-500 mt-1">Peso real de la mercancía con embalaje</p>
        </div>
        
        {{-- Campo: Volumen Total (calculado o manual) --}}
        <div>
            <label class="block text-xs font-medium text-gray-400 mb-2">Volumen Total (M³)</label>
            <input type="number" wire:model.live="volumen" step="0.001" placeholder="0.480"
                class="w-full px-3 py-2.5 bg-black/30 border border-purple-500/30 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all text-sm">
            <p class="text-xs text-gray-500 mt-1">Se calcula automáticamente o ingresa manualmente</p>
        </div>
    </div>
    
    {{-- Resultados calculados --}}
    @if($volumen > 0 || $peso > 0)
    <div class="mt-4 p-4 bg-gradient-to-r from-purple-500/10 to-indigo-500/10 border border-purple-500/30 rounded-xl">
        <h4 class="text-purple-300 text-sm font-bold mb-3 uppercase tracking-wider">📊 Datos Calculados</h4>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            {{-- Volumen --}}
            @if($volumen > 0)
            <div class="text-center p-3 bg-black/30 rounded-lg">
                <p class="text-xs text-gray-400 mb-1">Volumen</p>
                <p class="text-lg font-bold text-purple-400">{{ number_format($volumen, 3) }}</p>
                <p class="text-xs text-gray-500">M³</p>
            </div>
            @endif
            
            {{-- Peso Volumétrico --}}
            @if($volumen > 0)
            @php
                $pesoVol = $volumen * 500; // Factor estándar: m³ × 500
            @endphp
            <div class="text-center p-3 bg-black/30 rounded-lg">
                <p class="text-xs text-gray-400 mb-1">Peso Volumétrico</p>
                <p class="text-lg font-bold text-indigo-400">{{ number_format($pesoVol, 2) }}</p>
                <p class="text-xs text-gray-500">KG</p>
            </div>
            @endif
            
            {{-- Peso Bruto --}}
            @if($peso > 0)
            <div class="text-center p-3 bg-black/30 rounded-lg">
                <p class="text-xs text-gray-400 mb-1">Peso Bruto</p>
                <p class="text-lg font-bold text-green-400">{{ number_format($peso, 2) }}</p>
                <p class="text-xs text-gray-500">KG</p>
            </div>
            @endif
            
            {{-- Peso a Cobrar --}}
            @if($volumen > 0 && $peso > 0)
            @php
                $pesoCobrar = max($peso, $pesoVol);
            @endphp
            <div class="text-center p-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                <p class="text-xs text-yellow-400 mb-1">Peso a Cobrar</p>
                <p class="text-lg font-bold text-yellow-400">{{ number_format($pesoCobrar, 2) }}</p>
                <p class="text-xs text-gray-500">KG (el mayor)</p>
            </div>
            @endif
        </div>
        
        {{-- Info sobre GA si hay producto seleccionado --}}
        @if($productoSeleccionado)
        <div class="mt-4 p-3 bg-green-500/10 border border-green-500/30 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-green-400 uppercase tracking-wider font-bold">Gravamen Arancelario Aplicado</p>
                    <p class="text-gray-300 text-sm mt-1">{{ Str::limit($descripcionProducto, 40) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-black text-green-400">{{ $productoSeleccionado['arancel'] }}%</p>
                    <p class="text-xs text-gray-500">GA</p>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- Fórmulas explicativas --}}
    <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
        <div class="p-2 bg-white/5 rounded-lg">
            <p class="text-gray-500">📐 <strong class="text-purple-400">Volumen:</strong> (L × A × H) ÷ 1,000,000</p>
        </div>
        <div class="p-2 bg-white/5 rounded-lg">
            <p class="text-gray-500">⚖️ <strong class="text-indigo-400">Peso Vol:</strong> Volumen M³ × 500</p>
        </div>
        <div class="p-2 bg-white/5 rounded-lg">
            <p class="text-gray-500">📦 <strong class="text-yellow-400">Facturable:</strong> MAX(Peso Real, Peso Vol)</p>
        </div>
    </div>
</div>
