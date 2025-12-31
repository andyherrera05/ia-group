{{-- 
    ============================================================
    COMPONENTE: Alertas de Sesión
    ============================================================
    
    DESCRIPCIÓN:
    Muestra mensajes de éxito o error guardados en la sesión.
    
    VARIABLES REQUERIDAS:
    - session('success'): Mensaje de éxito
    - session('error'): Mensaje de error
    
    USO:
    @include('livewire.components.alertas-sesion')
    ============================================================
--}}

{{-- Alerta de éxito --}}
@if (session()->has('success'))
    <div class="bg-yellow-500/10 border-l-4 border-yellow-500 text-yellow-500 px-6 py-4 rounded-xl mb-8 backdrop-blur-sm">
        <div class="flex items-center space-x-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    </div>
@endif

{{-- Alerta de error --}}
@if (session()->has('error'))
    <div class="bg-red-500/10 border-l-4 border-red-500 text-red-400 px-6 py-4 rounded-xl mb-8 backdrop-blur-sm">
        <div class="flex items-center space-x-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    </div>
@endif
