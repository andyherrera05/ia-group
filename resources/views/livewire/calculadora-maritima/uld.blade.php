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

        <!-- desconsolidacion -->
        <div class="flex items-center gap-2 mb-2">
            <button type="button" wire:click="$set('desconsolidacion', '1')"
                class="group relative overflow-hidden px-2 py-3 border-2 rounded-xl transition-all border-yellow-500 bg-yellow-500/10">
                <div class="relative z-10">
                    <span class="block text-center text-xs font-bold text-gray-400 group-hover:text-yellow-400">
                        Consolidacion
                    </span>
                </div>
            </button>
            <button type="button" wire:click="$set('desconsolidacion', '0')"
                class="group relative overflow-hidden px-2 py-3 border-2 rounded-xl transition-all border-yellow-500/10 hover:border-yellow-500/50">
                <div class="relative z-10">
                    <span class="block text-center text-xs font-bold text-gray-400 group-hover:text-yellow-400">
                        Desconsolidacion
                    </span>
                </div>
            </button>
        </div>

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
                    <input type="text" wire:model.live="clienteNombreRoRo" placeholder="Ej: JAIME CARDONA"
                        class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                    @error('clienteNombreRoRo')
                    <div class="text-red-500 text-[11px] mt-1 font-semibold italic" style="color: #ef4444 !important; font-size: 11px !important;">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Email</label>
                    <input type="email" wire:model.live="clienteEmailRoRo" placeholder="ejemplo@correo.com"
                        class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                    @error('clienteEmailRoRo')
                    <div class="text-red-500 text-[11px] mt-1 font-semibold italic" style="color: #ef4444 !important; font-size: 11px !important;">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div
                    x-data="{ 
                                open: false, 
                                countryCode: @entangle('codigoPais'),
                                get currentFlag() {
                                    return this.$refs[this.countryCode]?.dataset.flag || '🌍';
                                }
                            }"
                    class="flex items-center w-full bg-black/40 border border-yellow-500/10 rounded-lg focus-within:ring-1 focus-within:ring-yellow-500 transition-all relative">

                    <div class="relative">

                        <button
                            type="button"
                            @click="open = !open"
                            class="px-2 flex items-center gap-2 pl-3 pr-2 py-2 text-gray-300 hover:text-white border-r border-yellow-500/10 transition-colors text-sm focus:outline-none min-w-[90px]">
                            <span x-text="currentFlag"></span>
                            <span x-text="countryCode"></span>

                            <svg class="w-3 h-3 text-gray-500 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            @click.outside="open = false"
                            x-transition.opacity.duration.200ms
                            class="absolute z-50 top-full left-0 mt-1 w-48 bg-gray-900 border border-yellow-500/20 rounded-md shadow-xl py-1 max-h-60 overflow-y-auto custom-scrollbar"
                            style="display: none;">
                            @foreach($this->paises as $pais)
                            <button
                                type="button"
                                x-ref="{{ $pais['code'] }}"
                                data-flag="{{ $pais['flag'] }}"
                                @click="countryCode = '{{ $pais['code'] }}'; open = false"
                                class="flex items-center gap-3 w-full px-4 py-2 text-sm text-gray-300 hover:bg-yellow-500/20 hover:text-white text-left transition-colors"
                                :class="countryCode === '{{ $pais['code'] }}' ? 'bg-yellow-500/10 text-yellow-500' : ''" style="background-color: #1a170c;">
                                <span class="text-lg">{{ $pais['flag'] }}</span>
                                <span class="font-mono text-gray-400">{{ $pais['code'] }}</span>
                                <span class="truncate ml-auto text-xs text-gray-500">{{ $pais['name'] }}</span>
                            </button>
                            @endforeach
                        </div>
                        @error('clienteTelefono')
                        <span
                            class="text-red-400 text-[10px] block mt-0.5 leading-none">{{ $message }}</span>
                        @enderror
                    </div>

                    <input
                        type="tel"
                        wire:model.live="clienteTelefonoRoRo"
                        placeholder="72732422"
                        class="w-full px-3 py-2 bg-transparent border-none text-white placeholder-gray-600 focus:ring-0 focus:outline-none text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Ciudad</label>
                    <select wire:model.live="clienteCiudadRoRo"
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
                    @error('clienteCiudadRoRo')
                    <div class="text-red-500 text-[11px] mt-1 font-semibold italic" style="color: #ef4444 !important; font-size: 11px !important;">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Dirección</label>
                    <input type="text" wire:model.live="clienteDireccionRoRo" placeholder="Dirección completa"
                        class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                    @error('clienteDireccionRoRo')
                    <div class="text-red-500 text-[11px] mt-1 font-semibold italic" style="color: #ef4444 !important; font-size: 11px !important;">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Agente de Carga</label>
                    <select wire:model.live="agenteIdRoRo"
                        class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                        <option value="-" style="background-color:#1a170c; color: #fff;">-- Seleccionar Agente --</option>
                        <option value="0" style="background-color:#1a170c; color: #fff;">Ninguno</option>
                        @foreach($agentes as $agente)
                        <option value="{{ $agente['id'] }}" style="background-color:#1a170c; color: #fff;">{{ $agente['nombre'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <!-- Tipo de Vehiculos -->
        <div class="mb-8 p-4 bg-yellow-500/5 border border-yellow-500/20 rounded-xl">
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
            <div class="mt-4 flex flex-col sm:flex-row gap-3 w-full">
                <!-- Image Upload (Square with Preview) -->
                <div style="width: 150px; height: 150px; min-width: 150px; min-height: 150px;" class="shrink-0 relative group">
                    <label class="block w-full h-full rounded-lg border-2 border-dashed border-yellow-500/30 hover:border-yellow-500 bg-black/20 cursor-pointer overflow-hidden transition-all relative">
                        <input type="file" wire:model="temp_manualImagenRoRo" accept="image/*" class="hidden">
                        @if ($temp_manualImagenRoRo)
                        <img src="{{ $temp_manualImagenRoRo->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover">
                        @elseif ($temp_imagenRoRo)
                        <img src="{{ $temp_imagenRoRo }}" class="absolute inset-0 w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-500 group-hover:text-yellow-500">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-[10px] uppercase font-bold">Foto</span>
                        </div>
                        @endif
                        <!-- Loading State -->
                        <div wire:loading wire:target="temp_manualImagenRoRo" class="absolute inset-0 bg-black/60 flex items-center justify-center z-10">
                            <div class="w-6 h-6 border-2 border-yellow-500 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                    </label>
                </div>
                <div class="flex-1 w-full">
                    <h3 class="text-yellow-500 font-bold mb-4 text-sm uppercase tracking-widest">Nombre del Vehículo</h3>
                    <input type="text" wire:model="nombreVehiculoRoRo" step="1" placeholder="Ej: Mazda"
                        class="w-full px-4 py-3 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                    <p class="text-xs text-gray-500 mt-2"></p>
                </div>
                <div class="flex-1 w-full">
                    <h3 class="text-yellow-500 font-bold mb-4 text-sm uppercase tracking-widest">Valor del Vehículo (USD)</h3>
                    <input type="number" wire:model="valorMercanciaRoRo" step="1" placeholder="Ej: 15000"
                        class="w-full px-4 py-3 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                    <p class="text-xs text-gray-500 mt-2">Valor comercial para cálculo de seguro y aduanas</p>
                </div>
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