{{-- 
    ============================================================
    COMPONENTE: Título de Página
    ============================================================
    
    DESCRIPCIÓN:
    Sección de título principal con gradiente y decoración.
    
    PROPS:
    - $titulo: Título principal (ej: "CALCULADORA")
    - $subtitulo: Subtítulo con color amarillo (ej: "AÉREA")
    - $descripcion: Texto descriptivo
    
    USO:
    @include('livewire.components.titulo-pagina', [
        'titulo' => 'CALCULADORA',
        'subtitulo' => 'AÉREA',
        'descripcion' => 'Calcula el costo de tus envíos aéreos express'
    ])
    ============================================================
--}}

<div class="text-center mb-12">
    <h2 class="text-4xl sm:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-amber-400 to-yellow-500 mb-4">
        {{ $titulo ?? 'CALCULADORA' }} <span class="text-yellow-500">{{ $subtitulo ?? '' }}</span>
    </h2>
    <p class="text-gray-300 text-lg max-w-2xl mx-auto leading-relaxed">{{ $descripcion ?? 'Calcula el costo de tus envíos' }}</p>
    <div class="w-24 h-1 bg-gradient-to-r from-yellow-500 to-amber-500 mx-auto mt-4 rounded-full"></div>
</div>
