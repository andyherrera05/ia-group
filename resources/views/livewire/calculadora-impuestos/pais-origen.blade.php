{{-- 
    ============================================================
    COMPONENTE: País de Origen
    ============================================================
    
    DESCRIPCIÓN:
    Campo de texto para indicar el país de origen de la mercancía.
    
    VARIABLES LIVEWIRE REQUERIDAS:
    - $paisOrigen: string - Nombre del país
    
    USO:
    @include('livewire.calculadora-impuestos.pais-origen')
    ============================================================
--}}

<div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl transition-all duration-300 hover:border-yellow-500/30">
    <label class="block text-yellow-500 font-bold mb-2 text-xs uppercase tracking-widest">País de Origen</label>
    <input type="text" wire:model="paisOrigen" placeholder="Ej: China"
        class="w-full bg-black/30 border-2 border-white/10 text-white px-4 py-3.5 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all placeholder-gray-600">
</div>
