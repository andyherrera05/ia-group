<div class="space-y-6">
    <!-- Card: Header Autos -->
    <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl">
        <h3 class="text-yellow-500 font-bold mb-6 text-lg uppercase tracking-widest flex items-center">
            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1v-1h3.05a2.5 2.5 0 014.9 0H19a1 1 0 001-1v-5a1 1 0 00-.3-.7l-2-3A1 1 0 0016.8 3H12a1 1 0 00-1 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
            </svg>
            Cotizador de Vehículos (Ro-Ro)
        </h3>
        <p class="text-gray-400 text-sm mb-6">Transporte internacional de vehículos (Roll-on/Roll-off)</p>

        <!-- Tipo de Vehiculos -->
        <h4 class="text-yellow-500 font-bold mb-4 text-sm uppercase tracking-widest">Tipo de Vehículo</h4>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <label for="claseVehiculo" class="pt-2 block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Tipo de Vehículo</label>
            <select wire:model.live="claseVehiculo"
                class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                <option value="" style="background-color:#1a170c;">-- Seleccionar Tipo de Vehículo --</option>
                @foreach($clasesVehiculoOptions as $option)
                <option value="{{ $option['codigo'] }}" style="background-color:#1a170c;">{{ $option['descripcion'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <label for="marcaVehiculo" class="pt-2 block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Marca</label>
            <select wire:model.live="marcaVehiculo"
                class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                <option value="" style="background-color:#1a170c;">-- Seleccionar Marca --</option>
                @foreach($marcasVehiculoOptions as $option)
                <!-- Using 'codigo' as value because the API for vehicle types needs the brand ID -->
                <option value="{{ $option['codigo'] }}" style="background-color:#1a170c;">{{ $option['descripcion'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <label for="tipoVehiculo" class="pt-2 block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Tipo de Vehículo</label>
            <select wire:model.live="tipoVehiculo"
                class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                <option value="" style="background-color:#1a170c;">-- Seleccionar Tipo de Vehículo --</option>
                @foreach($tiposVehiculoOptions as $option)
                <!-- Using 'codigo' as value for subsequent API calls (subtypes) -->
                <option value="{{ $option['codigo'] }}" style="background-color:#1a170c;">{{ $option['descripcion'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <label for="subtipoVehiculo" class="pt-2 block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Subtipo de Vehículo</label>
            <select wire:model.live="subtipoVehiculo"
                class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                <option value="" style="background-color:#1a170c;">-- Seleccionar Subtipo de Vehículo --</option>
                @foreach($subtiposVehiculoOptions as $option)
                <option value="{{ $option['codigo'] }}" style="background-color:#1a170c;">{{ $option['descripcion'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <label for="cilindradaVehiculo" class="pt-2 block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Cilindrada</label>
            <select wire:model.live="cilindradaVehiculo"
                class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                <option value="" style="background-color:#1a170c;">-- Seleccionar Cilindrada --</option>
                @foreach($cilindradaVehiculoOptions as $option)
                @if(is_array($option))
                <option value="{{ $option['codigo'] }}" style="background-color:#1a170c;">{{ $option['descripcion'] }}</option>
                @else
                <!-- Handle case where option is just a value (e.g., integer or string) -->
                <option value="{{ $option }}" style="background-color:#1a170c;">{{ $option }}</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <label for="traccionVehiculo" class="pt-2 block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Tracción</label>
            <select wire:model.live="traccionVehiculo"
                class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                <option value="" style="background-color:#1a170c;">-- Seleccionar Tracción --</option>
                @foreach($traccionVehiculoOptions as $option)
                @if(is_array($option))
                <option value="{{ $option['codigo'] }}" style="background-color:#1a170c;">{{ $option['descripcion'] }}</option>
                @else
                <!-- Handle case where option is just a value (e.g., integer or string) -->
                <option value="{{ $option }}" style="background-color:#1a170c;">{{ $option }}</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <label for="transmisionVehiculo" class="pt-2 block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Transmisión</label>
            <select wire:model.live="transmisionVehiculo"
                class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                <option value="" style="background-color:#1a170c;">-- Seleccionar Transmisión --</option>
                @foreach($transmisionVehiculoOptions as $option)
                @if(is_array($option))
                <option value="{{ $option['codigo'] }}" style="background-color:#1a170c;">{{ $option['descripcion'] }}</option>
                @else
                <!-- Handle case where option is just a value (e.g., integer or string) -->
                <option value="{{ $option }}" style="background-color:#1a170c;">{{ $option }}</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <label for="combustibleVehiculo" class="pt-2 block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Combustible</label>
            <select wire:model.live="combustibleVehiculo"
                class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                <option value="" style="background-color:#1a170c;">-- Seleccionar Combustible --</option>
                @foreach($combustibleVehiculoOptions as $option)
                @if(is_array($option))
                <option value="{{ $option['codigo'] }}" style="background-color:#1a170c;">{{ $option['descripcion'] }}</option>
                @else
                <!-- Handle case where option is just a value (e.g., integer or string) -->
                <option value="{{ $option }}" style="background-color:#1a170c;">{{ $option }}</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <label for="anioVehiculo" class="pt-2 block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Año</label>
            <select wire:model.live="anioVehiculo"
                class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                <option value="" style="background-color:#1a170c;">-- Seleccionar Año --</option>
                @foreach($anioVehiculoOptions as $option)
                @if(is_array($option))
                <option value="{{ $option['codigo'] }}" style="background-color:#1a170c;">{{ $option['descripcion'] }}</option>
                @else
                <!-- Handle case where option is just a value (e.g., integer or string) -->
                <option value="{{ $option }}" style="background-color:#1a170c;">{{ $option }}</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <label for="paisVehiculo" class="pt-2 block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">País</label>
            <select wire:model.live="paisVehiculo"
                class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                <option value="" style="background-color:#1a170c;">-- Seleccionar País --</option>
                @foreach($paisVehiculoOptions as $option)
                @if(is_array($option))
                <option value="{{ $option['codigo'] }}" style="background-color:#1a170c;">{{ $option['descripcion'] }}</option>
                @else
                <!-- Handle case where option is just a value (e.g., integer or string) -->
                <option value="{{ $option }}" style="background-color:#1a170c;">{{ $option }}</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <label for="otrasCaracteristicasVehiculo" class="pt-2 block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Otras Características</label>
            <select wire:model.live="otrasCaracteristicasVehiculo"
                class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                <option value="" style="background-color:#1a170c;">-- Seleccionar Otras Características --</option>
                @foreach($otrasCaracteristicasVehiculoOptions as $option)
                @if(is_array($option))
                <option value="{{ $option['codigo'] }}" style="background-color:#1a170c;">{{ $option['descripcion'] }}</option>
                @else
                <!-- Handle case where option is just a value (e.g., integer or string) -->
                <option value="{{ $option }}" style="background-color:#1a170c;">{{ $option }}</option>
                @endif
                @endforeach
            </select>
        </div>
        <!-- Valor de Mercancía -->
        <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl relative z-30">
            <h3 class="text-yellow-500 font-bold mb-4 text-sm uppercase tracking-widest">Valor del Vehículo (USD)</h3>
            <input type="number" wire:model="valorMercanciaRoRo" step="1" placeholder="Ej: 15000"
                class="w-full px-4 py-3 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
            <p class="text-xs text-gray-500 mt-2">Valor comercial para cálculo de seguro y aduanas</p>
            <!-- Arancel Option -->
            <div class="md:col-span-2 bg-black/20 rounded-lg p-3 border border-white/5">
                <div class="flex items-center gap-2 mb-2">
                    <input type="checkbox" wire:model.live="temp_con_arancelRoRo" id="temp_con_arancelRoRo" class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                    <label for="temp_con_arancelRoRo" class="text-gray-400 cursor-pointer uppercase tracking-wider" style="font-size:14px">¿La carga tiene arancel?</label>
                </div>

                @if($temp_con_arancel)
                <div class="mt-2 p-3 bg-yellow-500/5 border border-yellow-500/20 rounded-lg space-y-3">
                    <div class="grid grid-cols-2 gap-2 bg-black/20 rounded-lg p-2 border border-white/5">
                        <div class="relative">
                            <label for="temp_hs_codeRoRo" class="block text-[4px] text-gray-500 text-center mb-1 uppercase">HS Code</label>
                            <input type="text" wire:model.live="temp_hs_codeRoRo" placeholder="HS Code"
                                class="w-full h-[105px] p-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white text-xs placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all">

                            @if(!empty($arancelSuggestionsRoRo))
                            <div class="absolute top-full left-0 w-full z-50 bg-gray-900 border border-gray-700 rounded-lg shadow-xl max-h-60 overflow-y-auto mt-1" @click.away="$wire.limpiarArancelSearch()" style="background-color: rgba(0, 0, 0, 0.9);">
                                @foreach($arancelSuggestionsRoRo as $suggestion)
                                <div wire:click="selectArancel('{{ $suggestion['codigo_hs'] }}', {{ $suggestion['arancel'] }})"
                                    class="p-2 hover:bg-gray-800 cursor-pointer text-xs border-b border-gray-800 last:border-0">
                                    <div class="font-bold text-yellow-500">{{ $suggestion['codigo_hs'] }}</div>
                                    <div class="text-gray-300">{{ $suggestion['descripcion'] }}</div>
                                    <div class="text-gray-500 text-[10px]">Arancel: {{ $suggestion['arancel'] }}%</div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="relative">
                            <label for="temp_arancelRoRo" class="block text-[4px] text-gray-500 text-center mb-1 uppercase">Arancel</label>
                            <input type="number" wire:model="temp_arancelRoRo" placeholder="Arancel %"
                                class="w-full h-[105px] p-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white text-xs placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs">%</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>

    <!-- Dimensiones y Peso -->
    <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl">
        <h3 class="text-yellow-500 font-bold mb-4 text-sm uppercase tracking-widest flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            Dimensiones y Peso
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div>
                <label class="pt-2 block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Largo (m)</label>
                <input type="number" wire:model="largoVehiculo" step="0.01" placeholder="Ej: 4.85"
                    class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
            </div>
            <div>
                <label class="pt-2 block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Ancho (m)</label>
                <input type="number" wire:model="anchoVehiculo" step="0.01" placeholder="Ej: 1.90"
                    class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
            </div>
            <div>
                <label class="pt-2 block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Alto (m)</label>
                <input type="number" wire:model="altoVehiculo" step="0.01" placeholder="Ej: 1.75"
                    class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Peso (kg)</label>
                <input type="number" wire:model="pesoVehiculo" step="1" placeholder="Ej: 2100"
                    class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
            </div>
        </div>
    </div>



    <!-- Servicios Adicionales -->
    <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl">
        <h3 class="text-yellow-500 font-bold mb-6 text-sm uppercase tracking-widest">Servicios Adicionales</h3>
        <div class="space-y-4">
            <!-- Verificación de Producto -->
            <div class="bg-black/20 border border-yellow-500/10 rounded-xl p-4 hover:border-yellow-500/30 transition-all">
                <div class="flex items-start space-x-3">
                    <input type="checkbox" wire:model="verificacionProductoRoRo" id="verificacionProductoRoRo"
                        class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                    <div class="flex-1">
                        <label for="verificacionProductoRoRo" class="flex items-center justify-between cursor-pointer">
                            <div>
                                <h5 class="text-white font-semibold text-sm">Verificación de Producto por modelo</h5>
                                <p class="text-gray-400 text-xs mt-0.5">Obtención de video real y fotos del producto real.</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Verificación de Calidad -->
            <div class="bg-black/20 border border-yellow-500/10 rounded-xl p-4 hover:border-yellow-500/30 transition-all">
                <div class="flex items-start space-x-3">
                    <input type="checkbox" wire:model="verificacionCalidadRoRo" id="verificacionCalidadRoRo"
                        class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                    <div class="flex-1">
                        <label for="verificacionCalidadRoRo" class="flex items-center justify-between cursor-pointer">
                            <div>
                                <h5 class="text-white font-semibold text-sm">Verificación de la Calidad del producto</h5>
                                <p class="text-gray-400 text-xs mt-0.5">Recepción en almacén y pruebas de funcionamiento/uso.</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Verificación de Empresa Digital -->
            <div class="bg-black/20 border border-yellow-500/10 rounded-xl p-4 hover:border-yellow-500/30 transition-all">
                <div class="flex items-start space-x-3">
                    <input type="checkbox" wire:model="verificacionEmpresaDigitalRoRo" id="verificacionEmpresaDigitalRoRo"
                        class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                    <div class="flex-1">
                        <label for="verificacionEmpresaDigitalRoRo" class="flex items-center justify-between cursor-pointer">
                            <div>
                                <h5 class="text-white font-semibold text-sm">Verificación de Empresa Digital</h5>
                                <p class="text-gray-400 text-xs mt-0.5">Investigación de veracidad de licencias y establecimiento.</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Verificación Presencial de Empresa -->
            <div class="bg-black/20 border border-yellow-500/10 rounded-xl p-4 hover:border-yellow-500/30 transition-all">
                <div class="flex items-start space-x-3">
                    <input type="checkbox" wire:model="verificacionEmpresaPresencialRoRo" id="verificacionEmpresaPresencialRoRo"
                        class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                    <div class="flex-1">
                        <label for="verificacionEmpresaPresencialRoRo" class="flex items-center justify-between cursor-pointer">
                            <div>
                                <h5 class="text-white font-semibold text-sm">Verificación in situ de la Empresa(Física/Digital)</h5>
                                <p class="text-gray-400 text-xs mt-0.5">Realización de viaje y visita técnica a la fábrica.</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <!-- Verificación Sustancias Peligrosas -->
            <div class="bg-black/20 border border-yellow-500/10 rounded-xl p-4 hover:border-yellow-500/30 transition-all">
                <div class="flex items-start space-x-3">
                    <input type="checkbox" wire:model="verificacionSustanciasPeligrosasRoRo" id="verificacionSustanciasPeligrosasRoRo"
                        class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                    <div class="flex-1">
                        <label for="verificacionSustanciasPeligrosasRoRo" class="flex items-center justify-between cursor-pointer">
                            <div>
                                <h5 class="text-white font-semibold text-sm">¿Los productos que envia contienen sustancias peligrosas?</h5>
                                <p class="text-gray-400 text-xs mt-0.5">Envio de sustancias peligrosas como: explosivos, gases, liquidos y solidos infamable, etc.</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <!-- Seguro -->
            <div class="bg-black/20 border border-yellow-500/10 rounded-xl p-4 hover:border-yellow-500/30 transition-all">
                <div class="flex items-start space-x-3">
                    <input type="checkbox" wire:model="seguroCargaAutos" id="seguroCargaAutos"
                        class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 cursor-pointer">
                    <div class="flex-1">
                        <label for="seguroCargaAutos" class="flex items-center justify-between cursor-pointer">
                            <div>
                                <h5 class="text-white font-semibold text-sm">¿Requiere seguro de carga?</h5>
                                <p class="text-gray-400 text-xs mt-0.5">Cobertura contra todo riesgo durante el tránsito.</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Pago Internacional -->
            <div class="bg-black/20 border border-yellow-500/10 rounded-xl p-4 hover:border-yellow-500/30 transition-all">
                <div class="flex items-center justify-between {{ $requierePagoInternacionalAutos ? 'mb-3' : '' }}">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="requierePagoInternacionalAutos"
                            class="w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-offset-0 focus:ring-yellow-500 transition-all">
                        <h5 class="text-white font-semibold text-sm">¿Requiere Pago Internacional?</h5>
                    </label>
                </div>

                @if($requierePagoInternacionalAutos)
                <div class="space-y-3 border-t border-yellow-500/20 pt-3 mt-3">
                    <label class="flex items-start space-x-3 cursor-pointer group">
                        <input type="radio" wire:model="pagosInternacionalesSwiftAutos" value="swift"
                            class="mt-1 w-4 h-4 text-yellow-500 border-gray-600 focus:ring-yellow-500 bg-black/40">
                        <div class="flex-1">
                            <span class="text-gray-300 text-sm font-medium group-hover:text-yellow-500 transition-colors">CON Swift Bancario / USD</span>
                            <span class="text-white text-xs font-medium px-2 py-1 rounded" style="background-color: #FA9F00;">Alta Comision</span>
                            <p class="text-gray-500 text-xs mt-0.5">Transferencia bancaria internacional estándar (SWIFT).</p>
                        </div>
                    </label>

                    <label class="flex items-start space-x-3 cursor-pointer group">
                        <input type="radio" wire:model="pagosInternacionalesSwiftAutos" value="sin_swift"
                            class="mt-1 w-4 h-4 text-yellow-500 border-gray-600 focus:ring-yellow-500 bg-black/40">
                        <div class="flex-1">
                            <span class="text-gray-300 text-sm font-medium group-hover:text-yellow-500 transition-colors">SIN Swift Bancario / USDT</span>
                            <span class="text-white text-xs font-medium px-2 py-1 rounded" style="background-color: #FA9F00;">Baja Comision</span>
                            <p class="text-gray-500 text-xs mt-0.5">Pagos directos en China, USDT o sin uso de red SWIFT.</p>
                        </div>
                    </label>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="flex flex-col sm:flex-row gap-4">
        <button wire:click="calcularResultadoRoRo"
            class="flex-1 bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-400 hover:to-amber-400 text-black font-bold py-4 px-6 rounded-xl transition-all transform hover:scale-105 shadow-lg shadow-yellow-500/30 flex items-center justify-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            <span>Calcular Cotización</span>
        </button>

        <button wire:click="limpiar"
            class="sm:w-auto bg-white/5 hover:bg-white/10 text-gray-300 font-bold py-4 px-6 rounded-xl border border-white/10 hover:border-yellow-500/50 transition-all">
            Limpiar
        </button>
    </div>
</div>