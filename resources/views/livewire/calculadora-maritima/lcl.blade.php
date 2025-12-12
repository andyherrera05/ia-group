<div class="space-y-6">
    <!-- Card: Introducción Explicativa -->
    <div class="bg-gradient-to-br from-blue-500/10 to-cyan-500/10 border border-blue-500/30 rounded-2xl p-6 shadow-xl">
        <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-blue-400 font-bold text-lg mb-2">💡 ¿Qué es LCL?</h3>
                <p class="text-gray-300 text-sm leading-relaxed">
                    <strong class="text-blue-300">LCL (Less than Container Load)</strong> es ideal cuando tu carga
                    <strong>no llena un contenedor completo</strong>.
                    Pagas solo por el espacio que utilizas, compartiendo el contenedor con otros clientes.
                    <span class="text-yellow-400">Económico para envíos pequeños y medianos.</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Card: Formulario Principal -->
    <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl">
        <h3 class="text-yellow-500 font-bold mb-6 text-lg uppercase tracking-widest flex items-center">Cotizador LCL
            ...</h3>
        <p class="text-gray-400 text-sm mb-6">Complete los datos de su envío para obtener una cotización instantánea.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2 flex items-center">
                    Valor de Mercancía (USD)
                </label>
                <input type="number" wire:model="valorMercancia" step="1" placeholder="Ej: 10,000"
                    class="w-full px-4 py-3 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
            </div>



            <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2 flex items-center">
                        Cantidad (Unidades)
                    </label>
                    <input type="number" wire:model="cantidad" step="1" placeholder="1" required
                        class="w-full px-4 py-3 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2 flex items-center">
                        Peso Total (KG)
                    </label>
                    <input type="number" wire:model="peso" step="1" placeholder="Ej: 500"
                        class="w-full px-4 py-3 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                    <p class="text-xs text-gray-500 mt-1">Peso bruto incluyendo embalaje</p>
                </div>
            </div>

            <div class="sm:col-span-2 mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Seleccione el Método de Cálculo de
                    Volumen:</label>
                <div class="flex space-x-4">
                    <button type="button" wire:click="$set('metodoVolumen', 'cbm_directo')"
                        class="px-4 py-2 text-sm rounded-lg transition-all {{ $metodoVolumen == 'cbm_directo' ? 'bg-yellow-500 text-black font-bold' : 'bg-black/40 text-gray-400 border border-yellow-500/30 hover:bg-yellow-500/20' }}">
                        CBM Directo (M³)
                    </button>
                    <button type="button" wire:click="$set('metodoVolumen', 'dimensiones')"
                        class="px-4 py-2 text-sm rounded-lg transition-all {{ $metodoVolumen == 'dimensiones' ? 'bg-yellow-500 text-black font-bold' : 'bg-black/40 text-gray-400 border border-yellow-500/30 hover:bg-yellow-500/20' }}">
                        Dimensiones (L x A x H)
                    </button>
                </div>
            </div>

            @if ($metodoVolumen == 'cbm_directo')
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2 flex items-center">
                        CBM Total (M³)
                    </label>
                    <input type="number" wire:model="volumen" step="0.001" placeholder="Ej: 2.5"
                        class="w-full px-4 py-3 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                    <p class="text-xs text-gray-500 mt-1">Este será el volumen utilizado para la cotización.</p>
                </div>
            @endif

            @if ($metodoVolumen == 'dimensiones')
                <div class="sm:col-span-2">
                    <h4 class="text-sm font-bold text-gray-300 mb-3">Dimensiones de la Carga (Cm)</h4>

                    <div class="grid grid-cols-3 gap-4">

                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-2">Largo (cm)</label>
                            <input type="number" wire:model="largo" placeholder="120"
                                class="w-full px-3 py-2 bg-black/30 border border-purple-500/30 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-2">Ancho (cm)</label>
                            <input type="number" wire:model="ancho" placeholder="80"
                                class="w-full px-3 py-2 bg-black/30 border border-purple-500/30 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-2">Alto (cm)</label>
                            <input type="number" wire:model="alto" placeholder="100"
                                class="w-full px-3 py-2 bg-black/30 border border-purple-500/30 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all text-sm">
                        </div>

                    </div>
                </div>
            @endif
        </div>
        <div class="p-6 shadow-xl">
            <h3 class="text-yellow-500 font-bold mb-6 text-lg uppercase tracking-widest flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z" />
                </svg>
                Servicios Adicionales
            </h3>
            <p class="text-gray-400 text-sm mb-6">Selecciona los servicios adicionales que requieras para tu envío</p>

            <div class="space-y-6">
                <!-- Recojo de Almacén -->
                <div class="bg-black/20 border border-yellow-500/20 rounded-xl p-5">
                    <div class="flex items-start space-x-4">
                        <input type="checkbox" wire:model="recojoAlmacen" id="recojoAlmacen"
                            class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                        <div class="flex-1">
                            <label for="recojoAlmacen" class="flex items-center justify-between cursor-pointer">
                                <div>
                                    <h4 class="text-white font-semibold text-base flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-yellow-400" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                        </svg>
                                        Recojo desde Almacén
                                    </h4>
                                    <p class="text-gray-400 text-sm mt-1">
                                        La carga será recogida desde un almacén antes de ser enviada al puerto
                                    </p>
                                </div>
                                <span class="text-yellow-400 font-bold text-lg ml-4">+$26.91</span>
                            </label>
                            @if ($recojoAlmacen)
                                <div class="mt-3 p-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                                    <p class="text-yellow-300 text-xs flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Servicio incluido: Recojo, embalaje y transporte al puerto
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Destino Final -->
                <div class="bg-black/20 border border-yellow-500/20 rounded-xl p-5">
                    <h4 class="text-white font-semibold text-base mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd" />
                        </svg>
                        Destino Final de Entrega
                    </h4>

                    <div class="space-y-4">
                        <div class="border-2 rounded-lg transition-all border-yellow-500/20 bg-black/30'">
                            <label class="flex items-center justify-between p-4 cursor-pointer">
                                <div class="flex items-center space-x-3 flex-1">
                                    <input type="radio" wire:model.live="destinoFinal" value="otros"
                                        name="destinoFinal"
                                        class="w-5 h-5 border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                                    <div class="flex-1">
                                        <span class="text-white font-medium">¿Dónde que se entregue la carga en Bolivia?</span>
                                    </div>
                                </div>
                            </label>
                            <div class="px-4 pb-4 pt-2 border-t border-yellow-500/20">
                                <label for="departamentoDestino" class="block text-xs font-medium text-gray-400 mb-2">
                                    Seleccionar Departamento:
                                </label>
                                <select id="departamentoDestino" wire:model.live="departamentoDestino"
                                    class="w-full px-4 py-3 bg-black/60 border border-yellow-500/40 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">

                                    <option value="" style="background-color: #0f0e0d">
                                        -- Selecciona un departamento --
                                    </option>
                                    @foreach ($departamentosAgrupados as $zona)
                                        <optgroup label="{{ $zona['label'] }}"
                                            class="bg-gray-900 {{ $zona['color'] }}"
                                            style="background-color: #0f0e0d">
                                            @foreach ($zona['departamentos'] as $departamento)
                                                <option value="{{ $departamento['value'] }}"
                                                    class="bg-gray-900 text-white" style="background-color: #0f0e0d">
                                                    {{ $departamento['nombre'] }} - {{ $zona['costo'] }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach

                                </select>

                                @if ($departamentoDestino)
                                    <div class="mt-3 p-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                                        <p class="text-yellow-300 text-xs flex items-start">
                                            <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span>
                                                @if ($departamentoDestino === 'beni' || $departamentoDestino === 'pando')
                                                    Zona amazónica: Tiempo de entrega extendido de 5-8 días
                                                    adicionales por logística especial.
                                                @elseif($departamentoDestino === 'la_paz' || $departamentoDestino === 'cochabamba' || $departamentoDestino === 'santa_cruz')
                                                    Eje central: Tiempo de entrega de 3-5 días adicionales con rutas
                                                    principales.
                                                @else
                                                    Zona sur: Tiempo de entrega de 2-4 días adicionales.
                                                @endif
                                            </span>
                                        </p>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="flex flex-col sm:flex-row gap-4">
        <button wire:click="calcular"
            class="flex-1 bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-400 hover:to-amber-400 text-black font-bold py-4 px-6 rounded-xl transition-all transform hover:scale-105 shadow-lg shadow-yellow-500/30 flex items-center justify-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            <span>Calcular Cotización</span>
        </button>

        <button wire:click="limpiar"
            class="sm:w-auto bg-white/5 hover:bg-white/10 text-gray-300 font-bold py-4 px-6 rounded-xl border border-white/10 hover:border-yellow-500/50 transition-all flex items-center justify-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <span>Limpiar</span>
        </button>
    </div>
</div>
