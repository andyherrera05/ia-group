{{-- 
    ============================================================
    COMPONENTE: Categoría de Producto
    ============================================================
    
    DESCRIPCIÓN:
    Selector de categoría con 4 opciones:
    - General (10% Arancel)
    - Alimentos (5% Arancel)
    - Tecnología (15% Arancel)
    - Textil (20% Arancel)
    
    VARIABLES LIVEWIRE REQUERIDAS:
    - $categoria: string - 'general', 'alimentos', 'tecnologia', 'textil'
    
    USO:
    @include('livewire.calculadora-impuestos.categoria-producto')
    ============================================================
--}}

<div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl transition-all duration-300 hover:border-yellow-500/50 hover:shadow-yellow-500/10">
    <label class="block text-yellow-500 font-bold mb-4 text-sm uppercase tracking-widest">Categoría de Producto</label>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        {{-- Opción: General --}}
        <button wire:click="$set('categoria', 'general')"
            class="group relative overflow-hidden px-4 py-4 border-2 rounded-xl font-bold transition-all transform hover:scale-105 {{ $categoria === 'general' ? 'border-yellow-500 bg-gradient-to-br from-yellow-500/20 to-amber-500/20 text-yellow-400 shadow-lg shadow-yellow-500/20' : 'border-white/10 text-gray-300 hover:border-yellow-500/50 hover:text-yellow-400' }}">
            <span class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-amber-500 opacity-0 group-hover:opacity-10 transition-opacity"></span>
            <span class="relative block text-center">
                <span class="text-lg">GENERAL</span>
                <span class="block text-xs font-normal mt-1 text-gray-400">10% Arancel</span>
            </span>
        </button>
        
        {{-- Opción: Alimentos --}}
        <button wire:click="$set('categoria', 'alimentos')"
            class="group relative overflow-hidden px-4 py-4 border-2 rounded-xl font-bold transition-all transform hover:scale-105 {{ $categoria === 'alimentos' ? 'border-yellow-500 bg-gradient-to-br from-yellow-500/20 to-amber-500/20 text-yellow-400 shadow-lg shadow-yellow-500/20' : 'border-white/10 text-gray-300 hover:border-yellow-500/50 hover:text-yellow-400' }}">
            <span class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-amber-500 opacity-0 group-hover:opacity-10 transition-opacity"></span>
            <span class="relative block text-center">
                <span class="text-lg">ALIMENTOS</span>
                <span class="block text-xs font-normal mt-1 text-gray-400">5% Arancel</span>
            </span>
        </button>
        
        {{-- Opción: Tecnología --}}
        <button wire:click="$set('categoria', 'tecnologia')"
            class="group relative overflow-hidden px-4 py-4 border-2 rounded-xl font-bold transition-all transform hover:scale-105 {{ $categoria === 'tecnologia' ? 'border-yellow-500 bg-gradient-to-br from-yellow-500/20 to-amber-500/20 text-yellow-400 shadow-lg shadow-yellow-500/20' : 'border-white/10 text-gray-300 hover:border-yellow-500/50 hover:text-yellow-400' }}">
            <span class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-amber-500 opacity-0 group-hover:opacity-10 transition-opacity"></span>
            <span class="relative block text-center">
                <span class="text-lg">TECNOLOGÍA</span>
                <span class="block text-xs font-normal mt-1 text-gray-400">15% Arancel</span>
            </span>
        </button>
        
        {{-- Opción: Textil --}}
        <button wire:click="$set('categoria', 'textil')"
            class="group relative overflow-hidden px-4 py-4 border-2 rounded-xl font-bold transition-all transform hover:scale-105 {{ $categoria === 'textil' ? 'border-yellow-500 bg-gradient-to-br from-yellow-500/20 to-amber-500/20 text-yellow-400 shadow-lg shadow-yellow-500/20' : 'border-white/10 text-gray-300 hover:border-yellow-500/50 hover:text-yellow-400' }}">
            <span class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-amber-500 opacity-0 group-hover:opacity-10 transition-opacity"></span>
            <span class="relative block text-center">
                <span class="text-lg">TEXTIL</span>
                <span class="block text-xs font-normal mt-1 text-gray-400">20% Arancel</span>
            </span>
        </button>
    </div>
</div>
