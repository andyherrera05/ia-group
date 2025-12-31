{{-- 
    ============================================================
    COMPONENTE: Efectos de Fondo
    ============================================================
    
    DESCRIPCIÓN:
    Elementos de fondo animados con gradientes y blur.
    Se usa en todas las calculadoras para consistencia visual.
    
    PERSONALIZACIÓN:
    - Colores: yellow-500, amber-500
    - Opacidad: opacity-5
    - Animación: animate-pulse
    
    USO:
    @include('livewire.components.efectos-fondo')
    ============================================================
--}}

<div class="fixed inset-0 opacity-5 pointer-events-none">
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-yellow-500/30 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-amber-500/30 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
</div>
