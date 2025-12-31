{{-- 
    ============================================================
    COMPONENTE: Botones de Acción Impuestos
    ============================================================
    
    DESCRIPCIÓN:
    Botones para calcular impuestos y limpiar el formulario.
    
    MÉTODOS LIVEWIRE:
    - calcular(): Ejecuta el cálculo de impuestos
    - limpiar(): Resetea todos los campos
    
    USO:
    @include('livewire.calculadora-impuestos.botones-accion')
    ============================================================
--}}

<div class="flex space-x-4">
    {{-- Botón Calcular Impuestos --}}
    <button wire:click="calcular" 
        class="flex-1 bg-gradient-to-r from-yellow-500 via-amber-500 to-yellow-500 hover:from-yellow-400 hover:via-amber-400 hover:to-yellow-400 text-black font-black py-4 px-6 text-lg uppercase tracking-wider transition-all transform hover:scale-105 hover:-translate-y-1 shadow-xl hover:shadow-yellow-500/50 rounded-xl">
        CALCULAR IMPUESTOS
    </button>
    
    {{-- Botón Limpiar --}}
    <button wire:click="limpiar" 
        class="bg-white/5 border-2 border-white/10 hover:border-yellow-500 text-gray-300 hover:text-yellow-400 font-bold py-4 px-6 uppercase transition-all transform hover:scale-105 rounded-xl">
        LIMPIAR
    </button>
</div>
