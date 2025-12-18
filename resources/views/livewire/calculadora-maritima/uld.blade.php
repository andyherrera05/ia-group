<div class="space-y-6">
    <!-- Card: Información del ULD -->
    <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl">
        <h3 class="text-yellow-500 font-bold mb-6 text-lg uppercase tracking-widest flex items-center">
            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
            </svg>
            Carga Aérea (ULD)
        </h3>
        <p class="text-gray-400 text-sm mb-6">Para envíos aéreos con unidades de carga (Unit Load Device)</p>

        {{-- <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Aeropuerto Origen -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Aeropuerto Origen</label>
                <input type="text" wire:model="origen" placeholder="ej: PEK (Beijing)"
                    class="w-full px-4 py-3 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
            </div>

            <!-- Aeropuerto Destino -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Aeropuerto Destino</label>
                <input type="text" wire:model="destino" placeholder="ej: EZE (Buenos Aires)"
                    class="w-full px-4 py-3 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
            </div>

            <!-- Peso Bruto -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Peso Bruto (KG)</label>
                <input type="number" wire:model="peso" step="1" placeholder="5000"
                    class="w-full px-4 py-3 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                <p class="text-xs text-gray-500 mt-1">Peso de la mercancía + embalaje</p>
            </div>

            <!-- Volumen -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Volumen (M³)</label>
                <input type="number" wire:model="volumen" step="0.001" placeholder="10"
                    class="w-full px-4 py-3 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                <p class="text-xs text-gray-500 mt-1">Volumen total del envío</p>
            </div>
        </div> --}}
    </div>

    <!-- Card: Tipo de ULD -->
    <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl">
        <h3 class="text-yellow-500 font-bold mb-4 text-sm uppercase tracking-widest">Tipo de ULD</h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <button type="button" 
                class="group relative overflow-hidden px-4 py-4 border-2 border-yellow-500/30 hover:border-yellow-500 rounded-xl transition-all">
                <span class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-amber-500 opacity-0 group-hover:opacity-10 transition-opacity"></span>
                <span class="relative block text-center">
                    <span class="text-base font-bold text-yellow-400">AKE</span>
                    <span class="block text-xs text-gray-400 mt-1">Contenedor Base Ancha</span>
                    <span class="block text-xs text-gray-500 mt-0.5">1.53m × 1.53m × 1.62m</span>
                </span>
            </button>
            
            <button type="button"
                class="group relative overflow-hidden px-4 py-4 border-2 border-yellow-500/30 hover:border-yellow-500 rounded-xl transition-all">
                <span class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-amber-500 opacity-0 group-hover:opacity-10 transition-opacity"></span>
                <span class="relative block text-center">
                    <span class="text-base font-bold text-yellow-400">AMA</span>
                    <span class="block text-xs text-gray-400 mt-1">Contenedor Alto</span>
                    <span class="block text-xs text-gray-500 mt-0.5">2.24m × 1.53m × 2.44m</span>
                </span>
            </button>

            <button type="button"
                class="group relative overflow-hidden px-4 py-4 border-2 border-yellow-500/30 hover:border-yellow-500 rounded-xl transition-all">
                <span class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-amber-500 opacity-0 group-hover:opacity-10 transition-opacity"></span>
                <span class="relative block text-center">
                    <span class="text-base font-bold text-yellow-400">PMC</span>
                    <span class="block text-xs text-gray-400 mt-1">Pallet Standard</span>
                    <span class="block text-xs text-gray-500 mt-0.5">3.17m × 2.23m × 1.62m</span>
                </span>
            </button>

            <button type="button"
                class="group relative overflow-hidden px-4 py-4 border-2 border-yellow-500/30 hover:border-yellow-500 rounded-xl transition-all">
                <span class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-amber-500 opacity-0 group-hover:opacity-10 transition-opacity"></span>
                <span class="relative block text-center">
                    <span class="text-base font-bold text-yellow-400">AAP</span>
                    <span class="block text-xs text-gray-400 mt-1">Contenedor Bajo</span>
                    <span class="block text-xs text-gray-500 mt-0.5">2.23m × 3.17m × 0.64m</span>
                </span>
            </button>

            <button type="button"
                class="group relative overflow-hidden px-4 py-4 border-2 border-yellow-500/30 hover:border-yellow-500 rounded-xl transition-all">
                <span class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-amber-500 opacity-0 group-hover:opacity-10 transition-opacity"></span>
                <span class="relative block text-center">
                    <span class="text-base font-bold text-yellow-400">AKN</span>
                    <span class="block text-xs text-gray-400 mt-1">Contenedor Refrigerado</span>
                    <span class="block text-xs text-gray-500 mt-0.5">Con control de temperatura</span>
                </span>
            </button>

            <button type="button"
                class="group relative overflow-hidden px-4 py-4 border-2 border-yellow-500/30 hover:border-yellow-500 rounded-xl transition-all">
                <span class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-amber-500 opacity-0 group-hover:opacity-10 transition-opacity"></span>
                <span class="relative block text-center">
                    <span class="text-base font-bold text-yellow-400">PLA</span>
                    <span class="block text-xs text-gray-400 mt-1">Pallet de Aviación</span>
                    <span class="block text-xs text-gray-500 mt-0.5">Hasta 11,340 kg</span>
                </span>
            </button>
        </div>
    </div>

    <!-- Card: Características Especiales -->
    <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl">
        <h3 class="text-yellow-500 font-bold mb-4 text-sm uppercase tracking-widest">Características Especiales</h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="flex items-center p-4 bg-black/40 border border-yellow-500/20 rounded-lg cursor-pointer hover:border-yellow-500/50 transition-all">
                <input type="checkbox" class="w-5 h-5 text-yellow-500 bg-black/40 border-yellow-500/30 rounded focus:ring-yellow-500 focus:ring-offset-0">
                <span class="ml-3 text-sm text-gray-300">Mercancía Frágil</span>
            </label>

            <label class="flex items-center p-4 bg-black/40 border border-yellow-500/20 rounded-lg cursor-pointer hover:border-yellow-500/50 transition-all">
                <input type="checkbox" class="w-5 h-5 text-yellow-500 bg-black/40 border-yellow-500/30 rounded focus:ring-yellow-500 focus:ring-offset-0">
                <span class="ml-3 text-sm text-gray-300">Carga Peligrosa</span>
            </label>

            <label class="flex items-center p-4 bg-black/40 border border-yellow-500/20 rounded-lg cursor-pointer hover:border-yellow-500/50 transition-all">
                <input type="checkbox" class="w-5 h-5 text-yellow-500 bg-black/40 border-yellow-500/30 rounded focus:ring-yellow-500 focus:ring-offset-0">
                <span class="ml-3 text-sm text-gray-300">Temperatura Controlada</span>
            </label>

            <label class="flex items-center p-4 bg-black/40 border border-yellow-500/20 rounded-lg cursor-pointer hover:border-yellow-500/50 transition-all">
                <input type="checkbox" class="w-5 h-5 text-yellow-500 bg-black/40 border-yellow-500/30 rounded focus:ring-yellow-500 focus:ring-offset-0">
                <span class="ml-3 text-sm text-gray-300">Carga Viva (Animales)</span>
            </label>
        </div>
    </div>

    <!-- Card: Valor de Mercancía -->
    <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl">
        <h3 class="text-yellow-500 font-bold mb-4 text-sm uppercase tracking-widest">Valor de Mercancía</h3>
        <input type="number" wire:model="valorMercancia" step="1" placeholder="25000"
            class="w-full px-4 py-3 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
        <p class="text-xs text-gray-500 mt-2">Para calcular el seguro (2% del valor para carga aérea)</p>
    </div>

    <!-- Buttons -->
    <div class="flex flex-col sm:flex-row gap-4">
        <button wire:click="calcular" 
            class="flex-1 bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-400 hover:to-amber-400 text-black font-bold py-4 px-6 rounded-xl transition-all transform hover:scale-105 shadow-lg shadow-yellow-500/30 flex items-center justify-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <span>Calcular Cotización</span>
        </button>
        
        <button wire:click="limpiar"
            class="sm:w-auto bg-white/5 hover:bg-white/10 text-gray-300 font-bold py-4 px-6 rounded-xl border border-white/10 hover:border-yellow-500/50 transition-all">
            Limpiar
        </button>
    </div>
</div>
