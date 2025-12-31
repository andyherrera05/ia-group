{{-- 
    ============================================================
    COMPONENTE: Valores CIF
    ============================================================
    
    DESCRIPCIÓN:
    Campos para ingresar valores de FOB, Flete y Seguro.
    Estos conforman la base imponible CIF.
    
    VARIABLES LIVEWIRE REQUERIDAS:
    - $valorMercancia: float - Valor FOB
    - $valorFlete: float - Costo del flete
    - $valorSeguro: float - Costo del seguro
    
    USO:
    @include('livewire.calculadora-impuestos.valores-cif')
    ============================================================
--}}

<div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl transition-all duration-300 hover:border-yellow-500/30">
    <h3 class="text-yellow-500 font-bold mb-4 text-xs uppercase tracking-widest">Valores (FOB + Flete + Seguro = CIF)</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        {{-- Campo: Valor Mercancía (FOB) --}}
        <div class="space-y-2">
            <label class="block text-gray-400 text-xs">Valor Mercancía (FOB) *</label>
            <div class="relative">
                <span class="absolute left-4 top-3.5 text-yellow-500 font-bold">$</span>
                <input type="number" wire:model="valorMercancia" step="0.01" placeholder="10000"
                    class="w-full bg-black/30 border-2 border-white/10 text-white pl-8 pr-4 py-3.5 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all" required>
            </div>
        </div>
        
        {{-- Campo: Valor Flete --}}
        <div class="space-y-2">
            <label class="block text-gray-400 text-xs">Valor Flete</label>
            <div class="relative">
                <span class="absolute left-4 top-3.5 text-yellow-500 font-bold">$</span>
                <input type="number" wire:model="valorFlete" step="0.01" placeholder="500"
                    class="w-full bg-black/30 border-2 border-white/10 text-white pl-8 pr-4 py-3.5 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all">
            </div>
        </div>
        
        {{-- Campo: Valor Seguro --}}
        <div class="space-y-2">
            <label class="block text-gray-400 text-xs">Valor Seguro</label>
            <div class="relative">
                <span class="absolute left-4 top-3.5 text-yellow-500 font-bold">$</span>
                <input type="number" wire:model="valorSeguro" step="0.01" placeholder="200"
                    class="w-full bg-black/30 border-2 border-white/10 text-white pl-8 pr-4 py-3.5 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all">
            </div>
        </div>
    </div>
</div>
