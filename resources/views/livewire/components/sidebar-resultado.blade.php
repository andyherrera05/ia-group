{{-- 
    ============================================================
    COMPONENTE: Sidebar de Resultado
    ============================================================
    
    DESCRIPCIÓN:
    Panel lateral que muestra el resultado del cálculo, desglose
    de costos y botón de contacto por WhatsApp.
    
    VARIABLES LIVEWIRE REQUERIDAS:
    - $resultado: Total calculado
    - $desglose: Array con concepto => valor
    - $mostrarPregunta: boolean
    - $respuestaUsuario: 'si', 'no' o null
    
    PROPS:
    - $icono: Nombre del icono SVG (opcional)
    - $tipoEnvio: Texto para WhatsApp (ej: "aéreo", "marítimo")
    
    MÉTODOS LIVEWIRE:
    - responder($respuesta): Maneja respuesta Sí/No
    
    USO:
    @include('livewire.components.sidebar-resultado', [
        'tipoEnvio' => 'aéreo'
    ])
    ============================================================
--}}

<div class="lg:col-span-1">
    <div class="bg-white/5 backdrop-blur-xl border-2 border-white/10 rounded-2xl p-6 shadow-2xl transition-all duration-300 hover:border-yellow-500/30 sticky top-24">
        <h2 class="text-2xl font-black text-yellow-500 mb-6 uppercase tracking-widest">Resultado</h2>
        
        @if ($resultado !== null)
            {{-- Card con resultado principal --}}
            <div class="bg-gradient-to-br from-yellow-500/10 to-amber-500/10 border-2 border-yellow-500 rounded-xl p-6 mb-6 text-center transition-all hover:shadow-yellow-500/20 hover:shadow-xl">
                <p class="text-sm font-bold text-yellow-400 mb-2 uppercase tracking-widest">Total Estimado</p>
                <p class="text-5xl font-black text-yellow-400">${{ $resultado }}</p>
                <p class="text-xs text-gray-400 mt-2">USD</p>
            </div>
            
            {{-- Desglose de costos --}}
            @if (count($desglose) > 0)
                <div class="space-y-3">
                    <h3 class="font-bold text-yellow-500 uppercase text-sm tracking-widest mb-4 border-b border-yellow-500/20 pb-3">Desglose:</h3>
                    @foreach ($desglose as $concepto => $valor)
                        <div class="flex justify-between items-center py-3 border-b border-white/5 hover:bg-white/5 px-3 rounded-lg transition-all">
                            <span class="text-gray-300 text-sm font-medium">{{ $concepto }}</span>
                            <span class="font-bold text-white text-sm">
                                @if (is_numeric($valor))
                                    {{ $valor }}
                                @else
                                    ${{ $valor }}
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            {{-- Estado vacío --}}
            <div class="text-center py-16">
                <div class="w-20 h-20 mx-auto mb-6 bg-yellow-500/5 border-2 border-yellow-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-yellow-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
                <p class="text-gray-500 text-sm font-medium">Completa el formulario para generar tu cotización</p>
            </div>
        @endif
        
        {{-- Pregunta interactiva sobre el precio --}}
        @if ($mostrarPregunta && $resultado !== null)
            <div class="mt-8 bg-gradient-to-br from-yellow-500/10 to-amber-500/10 border-2 border-yellow-500 rounded-xl p-5 animate-pulse" style="animation-duration: 2s;">
                @if ($respuestaUsuario === null)
                    {{-- Pregunta inicial --}}
                    <h3 class="text-xl font-black text-yellow-500 text-center mb-5">
                        ¿Te gusta el precio?
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                        <button wire:click="responder('si')" 
                                class="bg-green-500 hover:bg-green-400 text-white font-black py-3 px-4 rounded-lg transition-all transform hover:scale-105 shadow-lg shadow-green-500/30 uppercase text-sm">
                            😊 SÍ
                        </button>
                        <button wire:click="responder('no')" 
                                class="bg-red-500 hover:bg-red-400 text-white font-black py-3 px-4 rounded-lg transition-all transform hover:scale-105 shadow-lg shadow-red-500/30 uppercase text-sm">
                            😕 NO
                        </button>
                    </div>
                @else
                    {{-- Respuesta basada en la elección --}}
                    <div class="text-center space-y-4">
                        @if ($respuestaUsuario === 'si')
                            <svg class="w-16 h-16 mx-auto text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-lg font-black text-green-500 mb-3">¡Perfecto!</h3>
                            <p class="text-gray-300 text-sm mb-6">Contáctanos para confirmar tu envío</p>
                        @else
                            <svg class="w-16 h-16 mx-auto text-yellow-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-lg font-black text-yellow-500 mb-3">¡Podemos mejorarlo!</h3>
                            <p class="text-gray-300 text-sm mb-6">Hablemos de tu necesidad específica</p>
                        @endif
                        
                        <a href="https://wa.me/5491123456789?text=Hola !%20Vi%20el%20precio%20del%20envío%20{{ $tipoEnvio ?? 'logístico' }}%20de%20${{ $resultado }}%20y%20me%20gustaría%20más%20información." 
                           target="_blank"
                           class="inline-flex items-center space-x-3 bg-green-600 hover:bg-green-500 text-white font-black py-3 px-6 rounded-lg transition-all transform hover:scale-105 shadow-xl shadow-green-600/40">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            <span>Contáctanos</span>
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
