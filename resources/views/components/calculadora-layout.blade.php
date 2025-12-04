@props(['titulo', 'descripcion', 'resultado' => null, 'desglose' => []])

<div class="max-w-4xl mx-auto">
    <!-- Título y descripción -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-gray-900">{{ $titulo }}</h1>
            <a href="/" class="text-blue-600 hover:text-blue-700 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Volver</span>
            </a>
        </div>
        <p class="text-gray-600">{{ $descripcion }}</p>
    </div>

    <!-- Mensajes de sesión -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    @if (session()->has('info'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
            {{ session('info') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Datos del Envío</h2>
                
                {{ $slot }}
                
                <!-- Botones de acción -->
                <div class="flex space-x-4 mt-6">
                    <button 
                        wire:click="calcular" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                        Calcular Cotización
                    </button>
                    <button 
                        wire:click="limpiar" 
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-lg transition duration-200">
                        Limpiar
                    </button>
                </div>
            </div>
        </div>

        <!-- Resultados -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Resultado</h2>
                
                @if ($resultado !== null)
                    <div class="bg-blue-50 border-2 border-blue-500 rounded-lg p-6 mb-4">
                        <p class="text-sm text-gray-600 mb-2">Costo Total Estimado:</p>
                        <p class="text-4xl font-bold text-blue-600">${{ $resultado }}</p>
                        <p class="text-xs text-gray-500 mt-2">* Precio estimado en USD</p>
                    </div>
                    
                    @if (count($desglose) > 0)
                        <div class="border-t pt-4">
                            <h3 class="font-semibold text-gray-700 mb-3">Desglose:</h3>
                            <div class="space-y-2">
                                @foreach ($desglose as $concepto => $valor)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">{{ $concepto }}:</span>
                                        <span class="font-semibold text-gray-900">
                                            @if (is_numeric($valor))
                                                {{ $valor }}
                                            @else
                                                ${{ $valor }}
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500">Completa el formulario y presiona "Calcular" para ver el resultado</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Nota informativa -->
    <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
        <div class="flex">
            <svg class="w-5 h-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <p class="text-sm text-yellow-700">
                <strong>Nota:</strong> Los cálculos son estimaciones basadas en tarifas estándar. Para una cotización final, contacta con nuestro equipo comercial.
            </p>
        </div>
    </div>
</div>
