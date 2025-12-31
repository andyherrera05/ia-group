{{-- 
    ============================================================
    COMPONENTE: Buscador de Producto con Autocompletado
    ============================================================
    
    DESCRIPCIÓN:
    Buscador inteligente de productos del arancel con:
    - Autocompletado mientras se escribe
    - Búsqueda por sinónimos (vaca -> bovino)
    - Búsqueda por código HS
    
    VARIABLES LIVEWIRE REQUERIDAS:
    - $searchProducto: string - Término de búsqueda
    - $productosSugeridos: array - Lista de sugerencias
    - $showProductoDropdown: bool - Mostrar/ocultar dropdown
    - $productoSeleccionado: array|null - Producto elegido
    - $codigoHS: string - Código HS seleccionado
    - $descripcionProducto: string - Descripción del producto
    
    USO:
    @include('livewire.calculadora-impuestos.buscador-producto')
    ============================================================
--}}

<div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl">
    {{-- Título del buscador --}}
    <h3 class="text-yellow-500 font-bold mb-4 text-lg uppercase tracking-widest flex items-center">
        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
        </svg>
        Buscar Producto / Código HS
    </h3>
    <p class="text-gray-400 text-sm mb-4">
        Escribe el nombre del producto o su código HS para encontrar la tasa arancelaria correcta.
        <span class="text-yellow-400">Tip: puedes buscar "vaca" y encontrará "bovino".</span>
    </p>

    {{-- Campo de búsqueda --}}
    <div class="relative" x-data="{ open: @entangle('showProductoDropdown') }">
        <div class="relative">
            <span class="absolute left-4 top-3.5 text-yellow-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="searchProducto"
                placeholder="Ej: bovino, 0201.10, carne de res, vaca, laptop..."
                class="w-full bg-black/40 border-2 border-yellow-500/30 text-white pl-12 pr-4 py-3.5 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all placeholder-gray-500"
                autocomplete="off"
                @click.away="$wire.cerrarDropdownProducto()"
            >
            @if($searchProducto)
                <button 
                    wire:click="$set('searchProducto', '')"
                    class="absolute right-4 top-3.5 text-gray-400 hover:text-white transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            @endif
        </div>

        {{-- Dropdown de sugerencias --}}
        @if($showProductoDropdown && count($productosSugeridos) > 0)
            <div class="absolute z-[9999] w-full mt-2 bg-gray-900 backdrop-blur-xl border-2 border-yellow-500/30 rounded-xl shadow-2xl max-h-80 overflow-y-auto" style="background-color: #000">
                @foreach($productosSugeridos as $producto)
                    <button
                        wire:click="seleccionarProducto('{{ $producto['codigo_hs'] }}', '{{ addslashes($producto['descripcion']) }}', {{ $producto['arancel'] }})"
                        class="w-full px-4 py-3 text-left hover:bg-yellow-500/10 transition-colors border-b border-white/5 last:border-0 group"
                    >
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <span class="text-yellow-400 font-mono text-sm font-bold">{{ $producto['codigo_hs'] }}</span>
                                <p class="text-gray-300 text-sm mt-1 group-hover:text-white transition-colors">
                                    {{ Str::limit($producto['descripcion'], 60) }}
                                </p>
                            </div>
                            <div class="ml-3 flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold 
                                    @if($producto['arancel'] == 0) bg-green-500/20 text-green-400
                                    @elseif($producto['arancel'] <= 10) bg-blue-500/20 text-blue-400
                                    @elseif($producto['arancel'] <= 20) bg-yellow-500/20 text-yellow-400
                                    @else bg-red-500/20 text-red-400
                                    @endif">
                                    {{ $producto['arancel'] }}% GA
                                </span>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Producto seleccionado --}}
    @if($productoSeleccionado)
        <div class="mt-4 p-4 bg-gradient-to-r from-green-500/10 to-emerald-500/10 border-2 border-green-500/30 rounded-xl">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-2">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-green-400 font-bold text-sm uppercase tracking-wide">Producto Seleccionado</span>
                    </div>
                    <p class="text-yellow-400 font-mono text-sm font-bold">{{ $codigoHS }}</p>
                    <p class="text-white text-sm mt-1">{{ $descripcionProducto }}</p>
                </div>
                <div class="ml-4 text-right">
                    <span class="text-2xl font-black text-green-400">{{ $productoSeleccionado['arancel'] }}%</span>
                    <p class="text-xs text-gray-400">Gravamen Arancelario</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Ayuda de búsqueda --}}
    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-2">
        <div class="text-center p-2 bg-white/5 rounded-lg">
            <p class="text-xs text-gray-400">Por nombre</p>
            <p class="text-yellow-400 text-xs font-mono">carne bovino</p>
        </div>
        <div class="text-center p-2 bg-white/5 rounded-lg">
            <p class="text-xs text-gray-400">Por sinónimo</p>
            <p class="text-yellow-400 text-xs font-mono">vaca, chancho</p>
        </div>
        <div class="text-center p-2 bg-white/5 rounded-lg">
            <p class="text-xs text-gray-400">Por código HS</p>
            <p class="text-yellow-400 text-xs font-mono">0201.10</p>
        </div>
        <div class="text-center p-2 bg-white/5 rounded-lg">
            <p class="text-xs text-gray-400">Por capítulo</p>
            <p class="text-yellow-400 text-xs font-mono">leche, queso</p>
        </div>
    </div>
</div>
