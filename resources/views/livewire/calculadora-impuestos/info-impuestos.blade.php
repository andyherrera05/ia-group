{{-- 
    ============================================================
    COMPONENTE: Información de Impuestos
    ============================================================
    
    DESCRIPCIÓN:
    Card informativa que explica cómo funciona el cálculo de
    impuestos aduaneros al usuario.
    
    DEPENDENCIAS: Ninguna
    
    USO:
    @include('livewire.calculadora-impuestos.info-impuestos')
    ============================================================
--}}

<div class="bg-gradient-to-br from-yellow-500/10 to-amber-500/10 border border-yellow-500/30 rounded-2xl p-6 shadow-xl">
    <div class="flex items-start space-x-4">
        {{-- Icono informativo --}}
        <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
        </div>
        
        {{-- Texto explicativo --}}
        <div>
            <h3 class="text-yellow-400 font-bold text-lg mb-2">💰 ¿Cómo se calculan los impuestos?</h3>
            <p class="text-gray-300 text-sm leading-relaxed">
                Los impuestos de importación se calculan sobre el <strong class="text-yellow-300">valor CIF</strong> (Costo + Seguro + Flete). 
                El <strong class="text-yellow-300">Gravamen Arancelario (GA)</strong> varía según el producto (0% a 40%). 
                El <strong class="text-yellow-300">IVA</strong> es del 13% sobre CIF + GA.
                <span class="text-amber-400">Busca tu producto para obtener la tasa exacta.</span>
            </p>
        </div>
    </div>
</div>
