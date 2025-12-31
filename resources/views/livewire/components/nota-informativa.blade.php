{{-- 
    ============================================================
    COMPONENTE: Nota Informativa
    ============================================================
    
    DESCRIPCIÓN:
    Card de nota importante al pie de la calculadora.
    
    PROPS:
    - $mensaje: Texto del mensaje (opcional)
    - $icono: Path del SVG (opcional, default: info circle)
    
    USO:
    @include('livewire.components.nota-informativa', ['mensaje' => '...', 'icono' => '...'])
    ============================================================
--}}

@php
    $iconoDefault = 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
@endphp

<div class="mt-16 bg-gradient-to-r from-yellow-500/10 to-amber-500/10 border-l-4 border-yellow-500 rounded-xl p-6 backdrop-blur-sm">
    <div class="flex items-start space-x-4">
        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icono ?? $iconoDefault }}"/>
            </svg>
        </div>
        <div>
            <h4 class="text-yellow-500 font-bold mb-2 uppercase tracking-widest text-sm">Nota Importante</h4>
            <p class="text-gray-300 text-sm leading-relaxed">
                {{ $mensaje ?? 'Los cálculos son estimaciones basados en tarifas estándar. El precio final puede variar según disponibilidad y condiciones especiales del servicio.' }}
                <strong class="text-yellow-500">Contacta con nuestro equipo</strong> para una cotización oficial y personalizada.
            </p>
        </div>
    </div>
</div>
