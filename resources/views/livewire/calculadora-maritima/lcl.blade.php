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
    @if($esAnalisis)
    <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl flex items-center space-x-6 animate-fade-in relative group">
        @if($imagen)
        <div class="w-24 h-24 rounded-lg overflow-hidden border border-yellow-500/30 flex-shrink-0">
            <img src="{{ $imagen }}" alt="{{ $producto }}" class="w-full h-full object-cover">
        </div>
        @endif
        <div class="flex-1">
            @if($producto)
            <h4 class="text-yellow-500 font-bold text-lg leading-tight">{{ $producto }}</h4>
            @endif
            @if($id_producto)
            <p class="text-gray-400 text-xs mt-1 font-mono uppercase tracking-tighter">ID: {{ $id_producto }}</p>
            @endif
            <p class="text-gray-500 text-[10px] mt-2 italic">* Información cargada desde el análisis del producto</p>
        </div>
        {{-- Botón para resetear y cargar manual si lo desean --}}
        <button wire:click="$set('producto', ''); $set('imagen', ''); $set('id_producto', ''); $set('esAnalisis', false);" 
            class="absolute top-4 right-4 text-gray-500 hover:text-red-400 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    </div>
    @else
    <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl animate-fade-in">
        <h4 class="text-yellow-500 font-bold text-sm mb-4 uppercase tracking-widest flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Entrada Manual de Producto
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Nombre del Producto</label>
                <input type="text" wire:model.live.debounce.500ms="producto" placeholder="Ej: Zapatillas Deportivas"
                    class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Imagen del Producto (Opcional)</label>
                <div class="flex items-center space-x-4">
                    <input type="file" wire:model="manualImagen" class="text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-yellow-500/10 file:text-yellow-500 hover:file:bg-yellow-500/20 transition-all">
                    <div wire:loading wire:target="manualImagen" class="text-yellow-500 italic text-[10px]">Cargando...</div>
                </div>
                @if ($manualImagen)
                    <div class="mt-2 text-xs text-green-500">Imagen lista para la cotización</div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Card: Formulario Principal -->
    <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl">
        <h3 class="text-yellow-500 font-bold mb-6 text-lg uppercase tracking-widest flex items-center">Cotizador LCL</h3>
        <p class="text-gray-400 text-sm mb-6">Complete los datos de su envío para obtener una cotización instantánea.</p>

        
                <!-- Información del Cliente -->
        <div class="mb-8 p-4 bg-yellow-500/5 border border-yellow-500/20 rounded-xl">
            <h4 class="text-yellow-500 font-bold mb-4 text-xs uppercase tracking-widest flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Información del Cliente
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Nombre Completo / Empresa</label>
                    <input type="text" wire:model.live="clienteNombre" placeholder="Ej: JAIME CARDONA"
                        class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Email</label>
                    <input type="email" wire:model.live="clienteEmail" placeholder="ejemplo@correo.com"
                        class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Teléfono</label>
                    <input type="text" wire:model.live="clienteTelefono" placeholder="72732422"
                        class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Ciudad</label>
                    <select wire:model.live="clienteCiudad"
                        class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                        <option value="0" style="background-color:#1a170c; color: #fff;">-- Seleccionar Ciudad --</option>
                        <option value="Cochabamba" style="background-color:#1a170c; color: #fff;">Cochabamba</option>
                        <option value="La Paz" style="background-color:#1a170c; color: #fff;">La Paz</option>
                        <option value="Santa Cruz" style="background-color:#1a170c; color: #fff;">Santa Cruz</option>
                        <option value="Tarija" style="background-color:#1a170c; color: #fff;">Tarija</option>
                        <option value="Potosi" style="background-color:#1a170c; color: #fff;">Potosi</option>
                        <option value="Beni" style="background-color:#1a170c; color: #fff;">Beni</option>
                        <option value="Oruro" style="background-color:#1a170c; color: #fff;">Oruro</option>
                        <option value="Chuquisaca" style="background-color:#1a170c; color: #fff;">Chuquisaca</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Dirección</label>
                    <input type="text" wire:model.live="clienteDireccion" placeholder="Dirección opcional"
                        class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Agente de Carga</label>
                    <select wire:model.live="agenteId"
                        class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                        <option value="0" style="background-color:#1a170c; color: #fff;">-- Seleccionar Agente --</option>
                        @foreach($agentes as $agente)
                            <option value="{{ $agente['id'] }}" style="background-color:#1a170c; color: #fff;">{{ $agente['nombre'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2 flex items-center">
                    Valor de Mercancía (USD)
                </label>
                <input type="number" wire:model="valorMercancia" step="1" required placeholder="Ej: 10,000"
                    class="w-full px-4 py-3 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
            </div>



            <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2 flex items-center">
                        Cantidad (Unidades)
                    </label>
                    <input type="number" wire:model="cantidad" placeholder="Ej: 1" value="1" step="1" required
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
                    <button type="button" wire:click="$set('metodoVolumen', 'dimensiones')"
                        class="px-4 py-2 text-sm rounded-lg transition-all {{ $metodoVolumen == 'dimensiones' ? 'bg-yellow-500 text-black font-bold' : 'bg-black/40 text-gray-400 border border-yellow-500/30 hover:bg-yellow-500/20' }}">
                        Dimensiones (L x A x H)
                    </button>
                    <button type="button" wire:click="$set('metodoVolumen', 'cbm_directo')"
                        class="px-4 py-2 text-sm rounded-lg transition-all {{ $metodoVolumen == 'cbm_directo' ? 'bg-yellow-500 text-black font-bold' : 'bg-black/40 text-gray-400 border border-yellow-500/30 hover:bg-yellow-500/20' }}">
                        CBM Directo (M³)
                    </button>
                </div>
            </div>
            @if ($metodoVolumen == 'dimensiones')
            <div class="sm:col-span-2">
                <h4 class="text-sm font-bold text-gray-300 mb-3">Dimensiones de la Carga (Cm)</h4>

                <div class="grid grid-cols-3 gap-4">

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2">Largo (cm)</label>
                        <input type="number" wire:model="largo" placeholder="Ej: 120"
                            class="w-full px-3 py-2 bg-black/30 border border-purple-500/30 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2">Ancho (cm)</label>
                        <input type="number" wire:model="ancho" placeholder="Ej: 80"
                            class="w-full px-3 py-2 bg-black/30 border border-purple-500/30 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2">Alto (cm)</label>
                        <input type="number" wire:model="alto" placeholder="Ej: 100"
                            class="w-full px-3 py-2 bg-black/30 border border-purple-500/30 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all text-sm">
                    </div>

                </div>
            </div>
            @endif

            @if ($metodoVolumen == 'cbm_directo')
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-300 mb-2 flex items-center">
                    CBM Total (M³)
                </label>
                <input type="number" wire:model="volumen" step="0.5" min="0.5" placeholder="Ej: 2.5"
                    class="w-full px-4 py-3 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                <p class="text-xs text-gray-500 mt-1">Este será el volumen utilizado para la cotización.</p>
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
                                        Recojo desde Almacén
                                    </h4>
                                    <p class="text-gray-400 text-sm mt-1">
                                        La carga será recogida desde un almacén antes de ser enviada al puerto
                                    </p>
                                </div>
                                <span class="text-yellow-400 font-bold text-lg ml-4">
                                    +$26.91
                                    <span class="text-sm font-normal text-gray-300 ml-1">($/CBM)</span>
                                </span>
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
                <div class="space-y-4">
                    <div class="border-2 rounded-lg transition-all border-yellow-500/20 bg-black/30'">
                        <label class="flex items-center justify-between p-4 cursor-pointer">
                            <div class="flex items-center space-x-3 flex-1">
                                <input type="checkbox" wire:model.live="destinoFinal" value="otros" name="destinoFinal"
                                    class="w-5 h-5 border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                                <div class="flex-1">
                                    <span class="text-white font-medium">¿Dónde se hará la entrega de la carga en Bolivia?</span>
                                </div>
                            </div>
                        </label>
                        @if ($destinoFinal === 'otros' || $destinoFinal === true)
                        <div class="p-4 pt-2 border-t border-yellow-500/20">
                            <label for="departamentoDestino" class="block text-xs font-medium text-gray-400 mb-2">
                                Seleccionar Departamento:
                            </label>
                            <select id="departamentoDestino" wire:model="departamentoDestino"
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
                        @endif

                    </div>
                </div>
                <div class="space-y-4">
                    <h4 class="text-yellow-500 font-bold text-sm uppercase tracking-wider mb-2">Servicios de Verificación</h4>

                    <!-- Verificación de Producto -->
                    <div class="bg-black/20 border border-yellow-500/10 rounded-xl p-4 hover:border-yellow-500/30 transition-all">
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" wire:model="verificacionProducto" id="verificacionProducto"
                                class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                            <div class="flex-1">
                                <label for="verificacionProducto" class="flex items-center justify-between cursor-pointer">
                                    <div>
                                        <h5 class="text-white font-semibold text-sm">Verificación de Producto</h5>
                                        <p class="text-gray-400 text-xs mt-0.5">Obtención de video real y fotos del producto real.</p>
                                    </div>
                                    <span class="text-yellow-400 font-bold text-base ml-2">+$10</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Verificación de Calidad -->
                    <div class="bg-black/20 border border-yellow-500/10 rounded-xl p-4 hover:border-yellow-500/30 transition-all">
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" wire:model="verificacionCalidad" id="verificacionCalidad"
                                class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                            <div class="flex-1">
                                <label for="verificacionCalidad" class="flex items-center justify-between cursor-pointer">
                                    <div>
                                        <h5 class="text-white font-semibold text-sm">Verificación de la Calidad</h5>
                                        <p class="text-gray-400 text-xs mt-0.5">Recepción en almacén y pruebas de funcionamiento/uso.</p>
                                    </div>
                                    <span class="text-yellow-400 font-bold text-base ml-2">+$50</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Verificación de Empresa Digital -->
                    <div class="bg-black/20 border border-yellow-500/10 rounded-xl p-4 hover:border-yellow-500/30 transition-all">
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" wire:model="verificacionEmpresaDigital" id="verificacionEmpresaDigital"
                                class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                            <div class="flex-1">
                                <label for="verificacionEmpresaDigital" class="flex items-center justify-between cursor-pointer">
                                    <div>
                                        <h5 class="text-white font-semibold text-sm">Verificación de Empresa Digital</h5>
                                        <p class="text-gray-400 text-xs mt-0.5">Investigación de veracidad de licencias y establecimiento.</p>
                                    </div>
                                    <span class="text-yellow-400 font-bold text-base ml-2">+$100</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Verificación Presencial de Empresa -->
                    <div class="bg-black/20 border border-yellow-500/10 rounded-xl p-4 hover:border-yellow-500/30 transition-all">
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" wire:model="verificacionEmpresaPresencial" id="verificacionEmpresaPresencial"
                                class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                            <div class="flex-1">
                                <label for="verificacionEmpresaPresencial" class="flex items-center justify-between cursor-pointer">
                                    <div>
                                        <h5 class="text-white font-semibold text-sm">Verificación Presencial de Empresa</h5>
                                        <p class="text-gray-400 text-xs mt-0.5">Realización de viaje y visita técnica a la fábrica.</p>
                                    </div>
                                    <span class="text-yellow-400 font-bold text-base ml-2">+$350</span>
                                </label>
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