{{-- 
    ============================================================
    COMPONENTE: Fórmula CIF Explicativa
    ============================================================
    
    DESCRIPCIÓN:
    Tarjeta informativa que explica la fórmula CIF
    y cómo se calculan los impuestos.
    
    USO:
    @include('livewire.calculadora-impuestos.formula-cif')
    ============================================================
--}}

<div class="bg-gradient-to-br from-yellow-500/10 to-amber-500/10 border-l-4 border-yellow-500 rounded-xl p-6 backdrop-blur-sm">
    <h3 class="text-yellow-500 font-bold mb-3 uppercase text-xs tracking-widest">Fórmula CIF (Cost, Insurance, Freight)</h3>
    <div class="text-gray-300 text-sm space-y-2">
        <p><span class="text-yellow-500 font-bold">Base Imponible (CIF)</span> = Valor FOB + Flete + Seguro</p>
        <p><span class="text-yellow-500 font-bold">Arancel</span> = CIF × Tasa de Arancel (%)</p>
        <p><span class="text-yellow-500 font-bold">IVA</span> = (CIF + Arancel) × 16%</p>
        <p><span class="text-yellow-500 font-bold">DUA</span> = CIF × 1%</p>
    </div>
</div>
