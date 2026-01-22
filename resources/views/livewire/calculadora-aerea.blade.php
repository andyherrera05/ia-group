<div class="min-h-screen bg-gradient-to-br from-gray-950 via-black to-gray-900 text-white overflow-x-hidden">
    <!-- Background Effects - Subtle -->
    <div class="fixed inset-0 opacity-5 pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-yellow-500/30 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-amber-500/30 rounded-full blur-3xl animate-pulse"
            style="animation-delay: 1s;"></div>
    </div>

    <header class="bg-white/5 backdrop-blur-xl border-b border-yellow-500/20 sticky top-0 z-50 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-6">
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center space-x-4 group">
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-yellow-500/10 border-2 border-yellow-500/30 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                        <img src="/images/logo.png" alt="IA Groups Logo" class="w-full h-full object-contain">
                        <svg class="w-7 h-7 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z" />
                        </svg>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-widest text-yellow-500">IA GROUPS</h1>
                </a>
                <a href="/"
                    class="text-yellow-400 hover:text-yellow-300 font-medium transition-colors flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Volver</span>
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
        <div class="text-center mb-12">
            <h2
                class="text-4xl sm:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-amber-400 to-yellow-500 mb-4">
                CALCULADORA <span class="text-yellow-500">AÉREA</span>
            </h2>
            <p class="text-gray-300 text-lg max-w-2xl mx-auto leading-relaxed">Calcula el costo de tus envíos aéreos
                express</p>
            <div class="w-24 h-1 bg-gradient-to-r from-yellow-500 to-amber-500 mx-auto mt-4 rounded-full"></div>
        </div>

        @if (session()->has('success'))
        <div
            class="bg-yellow-500/10 border-l-4 border-yellow-500 text-yellow-500 px-6 py-4 rounded-xl mb-8 backdrop-blur-sm">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif
        @if (session()->has('error'))
        <div
            class="bg-red-500/10 border-l-4 border-red-500 text-red-400 px-6 py-4 rounded-xl mb-8 backdrop-blur-sm">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">

                <!-- Información del Cliente -->
                <div class="mb-8 p-4 bg-yellow-500/5 border border-yellow-500/20 rounded-xl">
                    <h4 class="text-yellow-500 font-bold mb-4 text-xs uppercase tracking-widest flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Información del Cliente
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label
                                class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Nombre
                                Completo / Empresa</label>
                            <input type="text" wire:model.live="clienteNombre" placeholder="Ej: JAIME CARDONA"
                                class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                            @error('clienteNombre')
                            <div class="text-red-500 text-[11px] mt-1 font-semibold italic"
                                style="color: #ef4444 !important; font-size: 11px !important;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Email</label>
                            <input type="email" wire:model.live="clienteEmail" placeholder="ejemplo@correo.com"
                                class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                            @error('clienteEmail')
                            <div class="text-red-500 text-[11px] mt-1 font-semibold italic"
                                style="color: #ef4444 !important; font-size: 11px !important;">
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
                                wire:model.live="clienteTelefono"
                                placeholder="72732422"
                                class="w-full px-3 py-2 bg-transparent border-none text-white placeholder-gray-600 focus:ring-0 focus:outline-none text-sm">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Ciudad</label>
                            <select wire:model.live="clienteCiudad"
                                class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                                <option value="0" style="background-color:#1a170c; color: #fff;">-- Seleccionar
                                    Ciudad --</option>
                                <option value="Cochabamba" style="background-color:#1a170c; color: #fff;">Cochabamba
                                </option>
                                <option value="La Paz" style="background-color:#1a170c; color: #fff;">La Paz</option>
                                <option value="Santa Cruz" style="background-color:#1a170c; color: #fff;">Santa Cruz
                                </option>
                                <option value="Tarija" style="background-color:#1a170c; color: #fff;">Tarija</option>
                                <option value="Potosi" style="background-color:#1a170c; color: #fff;">Potosi</option>
                                <option value="Beni" style="background-color:#1a170c; color: #fff;">Beni</option>
                                <option value="Oruro" style="background-color:#1a170c; color: #fff;">Oruro</option>
                                <option value="Chuquisaca" style="background-color:#1a170c; color: #fff;">Chuquisaca
                                </option>
                            </select>
                            @error('clienteCiudad')
                            <div class="text-red-500 text-[11px] mt-1 font-semibold italic"
                                style="color: #ef4444 !important; font-size: 11px !important;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Dirección</label>
                            <input type="text" wire:model.live="clienteDireccion" placeholder="Dirección completa"
                                class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                            @error('clienteDireccion')
                            <div class="text-red-500 text-[11px] mt-1 font-semibold italic"
                                style="color: #ef4444 !important; font-size: 11px !important;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label
                                class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Agente
                                de Carga</label>
                            <select wire:model.live="agenteId"
                                class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                                <option value="-" style="background-color:#1a170c; color: #fff;">-- Seleccionar
                                    Agente --</option>
                                <option value="0" style="background-color:#1a170c; color: #fff;">Ninguno</option>
                                @foreach ($agentes as $agente)
                                <option value="{{ $agente['id'] }}"
                                    style="background-color:#1a170c; color: #fff;">{{ $agente['nombre'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Compact Add Form -->
                    <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-5 shadow-xl">
                        <div class="mb-4">
                            <h4 class="text-yellow-500 font-bold text-sm uppercase tracking-widest flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Agregar Producto a Cotizar
                            </h4>
                        </div>

                        <div class="space-y-3">
                            <!-- Row 1: Image & Name -->
                            <div class="flex flex-col md:flex-row gap-3 items-start">
                                <!-- Image Upload (Square with Preview) -->
                                <div
                                    class="shrink-0 relative group w-[100px] h-[100px] min-w-[100px] min-h-[100px] md:w-[100px] md:h-[100px]" style="width: 150px; height: 150px;">
                                    <label
                                        class="block w-full h-full rounded-lg border-2 border-dashed border-yellow-500/30 hover:border-yellow-500 bg-black/20 cursor-pointer overflow-hidden transition-all relative">
                                        <input type="file" wire:model="temp_manualImagen" class="hidden" />
                                        @if ($temp_manualImagen)
                                        <img
                                            src="{{ $temp_manualImagen->temporaryUrl() }}"
                                            class="absolute inset-0 w-full h-full object-cover" />
                                        @elseif ($temp_imagen)
                                        <img
                                            src="{{ $temp_imagen }}"
                                            class="absolute inset-0 w-full h-full object-cover" />
                                        @else
                                        <div
                                            class="w-full h-full flex flex-col items-center justify-center text-gray-500 group-hover:text-yellow-500">
                                            <svg
                                                class="w-6 h-6 mb-2"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-[8px] uppercase font-bold">Foto</span>
                                        </div>
                                        @endif
                                        <!-- Loading State -->
                                        <div
                                            wire:loading
                                            wire:target="temp_manualImagen"
                                            class="absolute inset-0 bg-black/60 flex items-center justify-center z-10">
                                            <div
                                                class="w-6 h-6 border-2 border-yellow-500 border-t-transparent rounded-full animate-spin"></div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Name Input & Extra Fields -->
                                <div class="flex-1 flex flex-col gap-2 w-full">
                                    <label
                                        class="text-[10px] text-gray-400 uppercase tracking-wider pl-1">Nombre del Producto</label>
                                    <input
                                        type="text"
                                        wire:model="temp_producto"
                                        placeholder="Nombre del Producto (Ej: Zapatillas)"
                                        class="w-full h-[50px] p-4 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm" />
                                    @error('temp_producto')
                                    <span class="text-red-400 text-[10px] ml-1">{{ $message }}</span>
                                    @enderror

                                    <div
                                        class="grid grid-cols-3 gap-1 overflow-x-auto pb-1"
                                        style="min-width: 0;">
                                        <div class="col-span-1 flex flex-col gap-2 mr-1">
                                            <label
                                                class="text-gray-400 uppercase tracking-wider pl-1 text-[12px]" style="font-size: 10px;">Cantidad de piezas por caja</label>
                                            <input
                                                type="number"
                                                wire:model="temp_cantidad"
                                                placeholder="Cant."
                                                class="w-[35px] min-w-[35px] max-w-[35px] py-3 text-center bg-black/40 border border-yellow-500/10 rounded-lg text-white text-xs focus:border-yellow-500/50 focus:outline-none placeholder-gray-600 shrink-0" />
                                            @error('temp_cantidad')
                                            <span
                                                class="text-red-400 text-[10px] block mt-0.5 leading-none">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-span-1 flex flex-col gap-2 mr-1">
                                            <label
                                                class="text-gray-400 uppercase tracking-wider pl-1 text-[12px]" style="font-size: 10px;">Cantidad de cajas</label>
                                            <input
                                                type="number"
                                                wire:model="temp_cantidad_cajas"
                                                placeholder="Cajas"
                                                class="w-[35px] min-w-[35px] max-w-[35px] py-3 text-center bg-black/40 border border-yellow-500/10 rounded-lg text-white text-xs focus:border-yellow-500/50 focus:outline-none placeholder-gray-600 shrink-0" />
                                            @error('temp_cantidad_cajas')
                                            <span
                                                class="text-red-400 text-[10px] block mt-0.5 leading-none">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-span-1 flex flex-col gap-2 mr-1">
                                            <label
                                                class="text-gray-400 uppercase tracking-wider pl-1 text-[12px]" style="font-size: 10px;">Valor Unit. ($)</label>
                                            <input
                                                type="number"
                                                wire:model="temp_valor_unitario"
                                                placeholder="Valor ($)"
                                                class="w-[70px] p-3 bg-black/40 border border-yellow-500/10 rounded-lg text-white text-xs focus:border-yellow-500/50 focus:outline-none placeholder-gray-600" />
                                            @error('temp_valor_unitario')
                                            <span
                                                class="text-red-400 text-[10px] block mt-0.5 leading-none">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Row: Horizontal Inputs -->
                                    <div class="grid grid-cols-3 sm:grid-cols-3 gap-2 relative">
                                        <div class="flex space-x-0 w-[90px]">
                                            <div class="flex-1">
                                                <label
                                                    class="text-gray-400 uppercase tracking-wider pl-1 text-[12px]" style="font-size: 10px;">Peso Unit</label>
                                                <input
                                                    type="number"
                                                    wire:model="temp_peso_unitario"
                                                    placeholder="Peso"
                                                    class="w-full p-3 bg-black/40 border border-yellow-500/10 rounded-l-lg text-white text-xs focus:border-yellow-500/50 focus:outline-none placeholder-gray-600" />
                                            </div>
                                            <div class="w-12 flex-shrink-0">
                                                <label
                                                    class="text-gray-400 uppercase tracking-wider pl-1 text-[12px]" style="font-size: 10px;">Unid</label>
                                                <select
                                                    wire:model="temp_peso_unidad"
                                                    class="py-2 w-full px-0 pl-1 bg-black/40 border border-yellow-500/10 border-l-0 rounded-r-lg text-white text-[10px] focus:border-yellow-500/50 focus:outline-none">
                                                    <option value="kg">Kg</option>
                                                    <option value="lb">Lb</option>
                                                </select>
                                            </div>
                                            @error('temp_peso_unitario')
                                            <span
                                                class="text-red-400 text-[10px] block mt-0.5 w-full leading-none">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <!-- Costo Envio Interno -->
                                        <div class="col-span-1 relative group">
                                            <label
                                                class="text-gray-400 uppercase tracking-wider pl-1 text-[12px]" style="font-size: 10px;">Costo Envio Interno</label>
                                            <input
                                                type="text"
                                                wire:model.live.debounce.300ms="temp_costo_envio_interno"
                                                placeholder="Costo Envio Interno"
                                                class="w-full h-[42px] p-3 bg-black/40 border border-yellow-500/10 rounded-lg text-white text-xs focus:border-yellow-500/50 focus:outline-none placeholder-gray-600"
                                                title="Usa punto (.) para decimales • Ejemplo: 0.2" />
                                            <!-- Tooltip explicativo (aparece al hacer hover) -->
                                            <div
                                                class="absolute hidden group-hover:block bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1 bg-gray-800 text-white text-xs rounded border border-gray-700 whitespace-nowrap z-10">
                                                Usa <strong>punto</strong> para decimales<br />
                                                Ejemplos válidos: 0.2 • 1250.50
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Row 3: Dimensions & Action -->
                            <div class="grid grid-cols-1 gap-3 items-center mt-2">
                                <!-- Dimensions -->
                                <div class="bg-black/20 rounded-lg p-2 border border-white/5 relative">
                                    <div class="flex justify-center items-center gap-2 mb-2 flex-col sm:flex-row">
                                        <label class="text-[10px] text-gray-500 block text-center uppercase">
                                            Dimensiones Unitarias
                                        </label>
                                        <select
                                            wire:model="temp_medida_unidad"
                                            class="bg-black/40 border border-yellow-500/10 rounded text-white text-[10px] focus:outline-none focus:border-yellow-500/50 py-0.5 px-1 w-full sm:w-auto">
                                            <option value="cm">cm</option>
                                            <option value="in">in</option>
                                        </select>
                                    </div>

                                    <!-- Bloque que NO se modifica para móvil -->
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-4">
                                        <div class="w-full md:w-56">
                                            <label class="block text-[8px] text-gray-500 mb-1">
                                                Dimensión Única (Ref)
                                            </label>
                                            <input
                                                type="number"
                                                wire:model.blur="temp_dimension_total"
                                                placeholder="Dimensión de un lado"
                                                class="w-full py-2 bg-black/40 border border-yellow-500/10 rounded px-2 text-xs text-white focus:border-yellow-500/50 outline-none text-center" />
                                        </div>

                                        <div class="grid grid-cols-2 sm:grid-cols-4 md:flex gap-3 justify-center md:justify-start">
                                            <button
                                                type="button"
                                                wire:click="aplicarDimensiones('square')"
                                                title="Caja Cuadrada"
                                                class="w-full sm:w-28 md:w-32 h-20 sm:h-24 md:h-24 rounded border border-white/10 hover:border-yellow-500/50 hover:bg-white/5 flex items-center justify-center transition-all p-2" style="width: 80px; height: 80px;">
                                                <img
                                                    src="{{ asset('images/cajas/caja_cuadrada.png') }}"
                                                    alt="Square"
                                                    class="w-full h-full object-contain opacity-70 hover:opacity-100" />
                                            </button>
                                            <button
                                                type="button"
                                                wire:click="aplicarDimensiones('rectangular')"
                                                title="Caja Rectangular"
                                                class="w-full sm:w-28 md:w-32 h-20 sm:h-24 md:h-24 rounded border border-white/10 hover:border-yellow-500/50 hover:bg-white/5 flex items-center justify-center transition-all p-2" style="width: 80px; height: 80px;">
                                                <img
                                                    src="{{ asset('images/cajas/caja_rectangular.png') }}"
                                                    alt="Rectangular"
                                                    class="w-full h-full object-contain opacity-70 hover:opacity-100" />
                                            </button>
                                            <button
                                                type="button"
                                                wire:click="aplicarDimensiones('flat')"
                                                title="Caja Plana"
                                                class="w-full sm:w-28 md:w-32 h-20 sm:h-24 md:h-24 rounded border border-white/10 hover:border-yellow-500/50 hover:bg-white/5 flex items-center justify-center transition-all p-2" style="width: 80px; height: 80px;">
                                                <img
                                                    src="{{ asset('images/cajas/caja_plana.png') }}"
                                                    alt="Flat"
                                                    class="w-full h-full object-contain opacity-70 hover:opacity-100" />
                                            </button>
                                            <button
                                                type="button"
                                                wire:click="aplicarDimensiones('long')"
                                                title="Caja Alargada"
                                                class="w-full sm:w-28 md:w-32 h-20 sm:h-24 md:h-24 rounded border border-white/10 hover:border-yellow-500/50 hover:bg-white/5 flex items-center justify-center transition-all p-2" style="width: 80px; height: 80px;">
                                                <img
                                                    src="{{ asset('images/cajas/caja_tubo.png') }}"
                                                    alt="Long"
                                                    class="w-full h-full object-contain opacity-70 hover:opacity-100" />
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Inputs para largo, ancho y alto, adaptados para móvil -->
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <div class="flex-1">
                                            <label
                                                class="block text-[8px] text-gray-500 text-center mb-1 uppercase">Largo</label>
                                            <input
                                                type="number"
                                                wire:model="temp_largo"
                                                placeholder="0"
                                                class="w-full bg-transparent border-b border-gray-700 text-center text-xs text-white focus:border-yellow-500 outline-none pb-1" />
                                        </div>
                                        <div class="flex-1">
                                            <label
                                                class="block text-[8px] text-gray-500 text-center mb-1 uppercase">Ancho</label>
                                            <input
                                                type="number"
                                                wire:model="temp_ancho"
                                                placeholder="0"
                                                class="w-full bg-transparent border-b border-gray-700 text-center text-xs text-white focus:border-yellow-500 outline-none pb-1" />
                                        </div>
                                        <div class="flex-1">
                                            <label
                                                class="block text-[8px] text-gray-500 text-center mb-1 uppercase">Alto</label>
                                            <input
                                                type="number"
                                                wire:model="temp_alto"
                                                placeholder="0"
                                                class="w-full bg-transparent border-b border-gray-700 text-center text-xs text-white focus:border-yellow-500 outline-none pb-1" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Arancel Option -->
                            <div class="md:col-span-2 bg-black/20 rounded-lg p-3 border border-white/5">
                                <div class="flex items-center gap-2 mb-2">
                                    <input type="checkbox" wire:model.live="temp_con_arancel" id="temp_con_arancel" class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                                    <label for="temp_con_arancel" class="text-gray-400 cursor-pointer uppercase tracking-wider" style="font-size:14px">¿Necesita realizar el calculo de impuestos? <span class="ml-2 bg-yellow-500 text-black text-[10px] px-2 py-0.5 rounded font-bold">PLAN PREMIUM</span></label>
                                </div>

                                @if($temp_con_arancel)
                                <div class="mt-2 p-3 bg-yellow-500/5 border border-yellow-500/20 rounded-lg space-y-3">
                                    <div class="grid grid-cols-2 gap-2 bg-black/20 rounded-lg p-2 border border-white/5">
                                        <!-- HS Code Search -->
                                        <div class="relative z-[40]">
                                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                                Buscador Arancelario
                                            </label>
                                            <input type="text" wire:model.live.debounce.300ms="temp_hs_code"
                                                placeholder="HS Code"
                                                class="w-full px-4 py-1 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                                            <span class="block uppercase font-bold text-gray-500 mt-1"
                                                style="font-size: 9px;">Colocar una palabra genérica que identifique al
                                                producto</span>

                                            @if (!empty($arancelSuggestions))
                                            <div class="absolute z-50 left-0 w-[150%] mt-1 bg-gray-900 border border-yellow-500/30 rounded-lg shadow-2xl max-h-48 overflow-y-auto custom-scrollbar"
                                                style="background-color: rgba(0, 0, 0, 0.9); z-index: 1000;">
                                                @foreach ($arancelSuggestions as $sug)
                                                <div wire:click="selectArancel('{{ $sug['codigo_hs'] }}', {{ $sug['arancel'] }})"
                                                    class="p-2 hover:bg-yellow-500/10 cursor-pointer border-b border-white/5 last:border-0 transition-colors" style="font-size: 12px">
                                                    <div class="flex justify-between items-start">
                                                        <span
                                                            class="text-yellow-500 text-[9px] font-bold">{{ $sug['codigo_hs'] }}</span>
                                                        <span
                                                            class="bg-yellow-500/20 text-yellow-500 text-[7px] px-1 rounded">{{ $sug['arancel'] }}%</span>
                                                    </div>
                                                    <p class="text-[8px] text-gray-400 truncate">
                                                        {{ $sug['descripcion'] }}
                                                    </p>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                        <!-- Arancel % -->
                                        <div class="relative z-[40]">
                                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                Arancel %
                                            </label>
                                            <input type="number" wire:model="temp_arancel" placeholder="GA %"
                                                class="w-full px-4 py-1 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product List -->
                @if (count($items) > 0)
                <div class="bg-white/5 border border-white/10 rounded-xl p-4 animate-fade-in">
                    <h4
                        class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex justify-between items-center">
                        Lista de Productos
                        <span
                            class="bg-yellow-500/20 text-yellow-500 px-2 py-0.5 rounded text-[10px]">{{ count($items) }}
                            items</span>
                    </h4>
                    <div class="space-y-2 max-h-60 overflow-y-auto custom-scrollbar pr-1">
                        @foreach ($items as $index => $item)
                        <div
                            class="flex items-center bg-black/30 p-2 rounded-lg border border-white/5 hover:border-yellow-500/30 transition-colors group">
                            <!-- Image -->
                            <div
                                class="w-10 h-10 rounded border border-white/10 overflow-hidden flex-shrink-0 mr-3">
                                @if (isset($item['imagen']) && $item['imagen'])
                                <img src="{{ $item['imagen'] }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full bg-white/5 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                @endif
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0 mr-2">
                                <h5 class="text-white text-sm font-medium truncate">
                                    {{ $item['producto'] ?? 'Producto' }}
                                </h5>
                                <div class="flex flex-wrap gap-2 text-[10px] text-gray-400 mt-0.5">
                                    <span class="text-yellow-500/80">{{ $item['cantidad_total'] }} uds</span>
                                    <span>•</span>
                                    <span>${{ number_format($item['valor_unitario'] ?? ($item['valorMercancia'] ?? 0), 2) }}
                                        unids</span>
                                    <span>•</span>
                                    <span>{{ number_format($item['peso_unitario'] ?? ($item['peso'] ?? 0), 2) }} kg</span>
                                </div>
                            </div>

                            <!-- Totals & Actions -->
                            <div class="text-right flex flex-col justify-center">
                                @php
                                $totalVal = isset($item['total_valor'])
                                ? $item['total_valor']
                                : number_format(($item['valorMercancia'] ?? 0) * $item['cantidad'], 2);
                                $totalPes = isset($item['total_peso'])
                                ? $item['total_peso']
                                : number_format(($item['peso'] ?? 0) * $item['cantidad'], 2);
                                @endphp
                                <span
                                    class="text-white font-mono text-xs font-bold">${{ number_format($totalVal, 2) }}</span>
                                <span class="text-[9px] text-gray-500">{{ $totalPes }}kg total</span>
                            </div>

                            <button wire:click="eliminarProducto({{ $index }})"
                                class="ml-3 p-1.5 text-gray-500 hover:text-red-400 bg-white/5 rounded-lg hover:bg-white/10 transition-all opacity-0 group-hover:opacity-100">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="bg-white/5 border border-white/10 rounded-xl p-4 animate-fade-in">
                    <div class="p-6 shadow-xl">
                        <h3 class="text-yellow-500 font-bold mb-6 text-lg uppercase tracking-widest flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z" />
                            </svg>
                            Servicios Adicionales
                        </h3>
                        <p class="text-gray-400 text-sm mb-6">Selecciona los servicios adicionales que requieras para tu envío</p>
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
                                    <input type="checkbox" wire:model="verificacionCalidad" id="verificacionCalidad"
                                        class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                                    <div class="flex-1">
                                        <label for="verificacionCalidad" class="flex items-center justify-between cursor-pointer">
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
                                    <input type="checkbox" wire:model="verificacionEmpresaDigital" id="verificacionEmpresaDigital"
                                        class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                                    <div class="flex-1">
                                        <label for="verificacionEmpresaDigital" class="flex items-center justify-between cursor-pointer">
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
                                    <input type="checkbox" wire:model="verificacionEmpresaPresencial" id="verificacionEmpresaPresencial"
                                        class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                                    <div class="flex-1">
                                        <label for="verificacionEmpresaPresencial" class="flex items-center justify-between cursor-pointer">
                                            <div>
                                                <h5 class="text-white font-semibold text-sm">Verificación in situ de la Empresa(Física/Digital)</h5>
                                                <p class="text-gray-400 text-xs mt-0.5">Realización de viaje y visita técnica a la fábrica.</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <!-- Verificación Sustancias Peligrosas -->
                            <div class="bg-black/20 border border-yellow-500/10 rounded-xl p-4 hover:border-yellow-500/30 transition-all">
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" wire:model="verificacionSustanciasPeligrosas" id="verificacionSustanciasPeligrosas"
                                        class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                                    <div class="flex-1">
                                        <label for="verificacionSustanciasPeligrosas" class="flex items-center justify-between cursor-pointer">
                                            <div>
                                                <h5 class="text-white font-semibold text-sm">¿Los productos que envia contienen sustancias peligrosas?</h5>
                                                <p class="text-gray-400 text-xs mt-0.5">Envio de sustancias peligrosas como: explosivos, gases, liquidos y solidos infamable, etc.</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- Pagos internacionales con swift 1% o sin swift 2.5%-->
                            <div class="bg-black/20 border border-yellow-500/10 rounded-xl p-4 hover:border-yellow-500/30 transition-all">
                                <div class="flex items-center justify-between {{ $requierePagoInternacional ? 'mb-3' : '' }}">
                                    <label class="flex items-center space-x-3 cursor-pointer">
                                        <input type="checkbox" wire:model.live="requierePagoInternacional"
                                            class="w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-offset-0 focus:ring-yellow-500 transition-all">
                                        <h5 class="text-white font-semibold text-sm">¿Requiere Pago Internacional?</h5>
                                    </label>
                                </div>

                                @if($requierePagoInternacional)
                                <div class="space-y-3 border-t border-yellow-500/20 pt-3 mt-3">
                                    <!-- Opción Con Swift (1%) -->
                                    <label class="flex items-start space-x-3 cursor-pointer group">
                                        <input type="radio" wire:model="pagosInternacionalesSwift" value="swift"
                                            class="mt-1 w-4 h-4 text-yellow-500 border-gray-600 focus:ring-yellow-500 bg-black/40">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-300 text-sm font-medium group-hover:text-yellow-500 transition-colors">CON Swift Bancario / USD</span>
                                                <span class="text-white text-xs font-medium px-2 py-1 rounded" style="background-color: #FA9F00;">Alta Comision</span>
                                            </div>
                                            <p class="text-gray-500 text-xs mt-0.5">¿Transferencia bancaria internacional estándar (SWIFT).</p>
                                        </div>
                                    </label>

                                    <!-- Opción Sin Swift (2.5%) -->
                                    <label class="flex items-start space-x-3 cursor-pointer group">
                                        <input type="radio" wire:model="pagosInternacionalesSwift" value="sin_swift"
                                            class="mt-1 w-4 h-4 text-yellow-500 border-gray-600 focus:ring-yellow-500 bg-black/40">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-300 text-sm font-medium group-hover:text-yellow-500 transition-colors">SIN Swift Bancario / USDT</span>
                                                <span class="text-white text-xs font-medium px-2 py-1 rounded" style="background-color: #FA9F00;">Baja Comision</span>
                                            </div>
                                            <p class="text-gray-500 text-xs mt-0.5">Pagos directos en China, USDT o sin uso de red SWIFT.</p>
                                        </div>
                                    </label>
                                </div>
                                @endif
                            </div>
                            <!-- Seguro de la carga -->
                            <div class="bg-black/20 border border-yellow-500/10 rounded-xl p-4 hover:border-yellow-500/30 transition-all">
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" wire:model="seguroCarga" id="seguroCarga"
                                        class="mt-1 w-5 h-5 rounded border-yellow-500/50 bg-black/40 text-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-0 focus:ring-offset-black cursor-pointer">
                                    <div class="flex-1">
                                        <label for="seguroCarga" class="flex items-center justify-between cursor-pointer">
                                            <div>
                                                <h5 class="text-white font-semibold text-sm">¿Requiere seguro de la carga?</h5>
                                                <p class="text-gray-400 text-xs mt-0.5">Seguro contra todo tipo de riesgo en la mercancia y transporte.</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Consolidated Info moved to right column or below but user wanted just replacement of list -->

                {{-- REMOVED OLD LOOP AND BUTTONS --}}

                <div class="flex space-x-4">
                    <button wire:click="calcular"
                        class="flex-1 bg-gradient-to-r from-yellow-500 via-amber-500 to-yellow-500 hover:from-yellow-400 hover:via-amber-400 hover:to-yellow-400 text-black font-black py-4 px-6 text-lg uppercase tracking-wider transition-all transform hover:scale-105 hover:-translate-y-1 shadow-xl hover:shadow-yellow-500/50 rounded-xl">
                        CALCULAR
                    </button>
                    <button wire:click="limpiar"
                        class="bg-white/5 border-2 border-white/10 hover:border-yellow-500 text-gray-300 hover:text-yellow-400 font-bold py-4 px-6 uppercase transition-all transform hover:scale-105 rounded-xl">
                        LIMPIAR
                    </button>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div
                    class="bg-white/5 backdrop-blur-xl border-2 border-white/10 rounded-2xl p-6 shadow-2xl transition-all duration-300 hover:border-yellow-500/30 sticky top-24">
                    <h2 class="text-2xl font-black text-yellow-500 mb-6 uppercase tracking-widest">Resultado</h2>
                    <!-- Updated logic: Check for mostrarPregunta -->

                    @if ($mostrarPregunta && $resultado !== null)
                    <div
                        class="bg-gradient-to-br from-yellow-500/10 to-amber-500/10 border-2 border-yellow-500 rounded-xl p-6 mb-6 text-center transition-all hover:shadow-yellow-500/20 hover:shadow-xl">
                        <p class="text-sm font-bold text-yellow-400 mb-2 uppercase tracking-widest">Total Estimado
                        </p>
                        <p class="text-5xl font-black text-yellow-400">${{ $resultado }}</p>
                        <p class="text-xs text-gray-400 mt-2">USD - Incluye impuestos</p>
                    </div>

                    @if (count($desglose) > 0)
                    <div class="space-y-4" x-data="{ showDetailed: false }">
                        <h3
                            class="font-bold text-yellow-500 uppercase text-xs tracking-widest border-b border-yellow-500/20 pb-3">
                            Resumen de Cotización
                        </h3>

                        @php
                        $mainItems = [];
                        $detailedItems = [];
                        foreach ($desglose as $concepto => $valor) {
                        $trimmedConcepto = trim($concepto);
                        $isDetailed =
                        str_contains($concepto, '─') ||
                        str_contains($concepto, '├─') ||
                        str_contains($concepto, '└─') ||
                        str_starts_with($trimmedConcepto, 'Subtotal');

                        if ($isDetailed) {
                        $detailedItems[$concepto] = $valor;
                        } else {
                        $mainItems[$concepto] = $valor;
                        }
                        }
                        @endphp

                        <!-- Items Principales -->
                        <div class="space-y-2">
                            @foreach ($mainItems as $concepto => $valor)
                            <div
                                class="flex justify-between items-center py-2 px-4 bg-white/5 rounded-lg border border-white/5">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-300 text-sm font-medium" style="width: 175px;">{{ trim($concepto) }}</span>


                                    @if(in_array(trim($concepto), ['Cargos de importacion y despacho', 'Agencia Despachante', 'Impuestos']))
                                    <span class="px-1.5 py-0.5 rounded font-bold bg-green-500/20 text-green-400 border border-green-500/30" style="font-size:9px">TC OF</span>
                                    @else
                                    <span class="px-1.5 py-0.5 rounded font-bold bg-blue-500/20 text-blue-400 border border-blue-500/30" style="font-size:9px">TC BLUE</span>
                                    @endif
                                </div>
                                <span class="font-bold text-white text-sm">
                                    {{ is_numeric($valor) ? '$' . number_format($valor, 2) : $valor }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @php
                        $mercValue = 0;
                        if(isset($desglose['Valor de Mercancía'])) {
                        $mercValue = (float)str_replace(',', '', $desglose['Valor de Mercancía']);
                        }
                        @endphp
                        @if (count($detailedItems) > 0 && $mercValue > 0)
                        <button @click="showDetailed = !showDetailed"
                            class="w-full flex items-center justify-between py-3 px-4 bg-yellow-500/10 border border-yellow-500/30 rounded-xl text-yellow-500 hover:bg-yellow-500/20 transition-all group">
                            <span class="text-sm font-bold uppercase tracking-wider">Ver Desglose Detallado de Flete</span>
                            <svg class="w-5 h-5 transform transition-transform duration-300"
                                :class="showDetailed ? 'rotate-180' : ''" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Sección Detallada (Accordion) -->
                        <div x-show="showDetailed" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            class="space-y-1 pl-4 border-l-2 border-yellow-500/20 mt-2">
                            @foreach ($detailedItems as $concepto => $valor)
                            @php
                            $isHeader =
                            str_contains($concepto, '─') &&
                            !str_contains($concepto, '├─') &&
                            !str_contains($concepto, '└─');
                            $isSubtotal = str_contains($concepto, 'Subtotal');
                            @endphp

                            @if ($isHeader)
                            <div class="pt-4 pb-1">
                                <span
                                    class="text-yellow-500/70 text-[10px] font-black uppercase tracking-widest">{{ ltrim($concepto, '─ ') }}</span>
                            </div>
                            @else
                            <div
                                class="flex justify-between items-center py-1.5 px-3 {{ $isSubtotal ? 'bg-white/5 rounded-md border-t border-white/10 mt-1 mb-2' : '' }}">
                                <span
                                    class="{{ $isSubtotal ? 'text-white font-bold text-xs' : 'text-gray-400 text-[11px]' }}">
                                    {{ ltrim($concepto, ' ├─└─') }}
                                </span>
                                @if ($valor !== null)
                                <span
                                    class="{{ $isSubtotal ? 'text-yellow-400 font-bold text-xs' : 'text-gray-300 text-[11px]' }}">
                                    {{ is_numeric($valor) ? '$' . number_format($valor, 2) : $valor }}
                                </span>
                                @endif
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <!-- Botón PDF -->
                    <div class="mt-6">
                        <button wire:click="descargarPDF" wire:loading.attr="disabled"
                            class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-4 px-6 rounded-xl transition-all transform hover:scale-105 shadow-xl shadow-red-600/40 flex items-center justify-center space-x-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Descargar Cotización en PDF</span>
                        </button>
                    </div>
                    @endif
                    @else
                    <div class="text-center py-16">
                        <div
                            class="w-20 h-20 mx-auto mb-6 bg-yellow-500/5 border-2 border-yellow-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-yellow-500/30" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">Completa el formulario para generar tu cotización</p>
                    </div>
                    @endif
                    {{-- Pregunta interactiva sobre el precio --}}
                    @if ($mostrarPregunta && $resultado !== null)
                    <div class="mt-8 bg-gradient-to-br from-yellow-500/10 to-amber-500/10 border-2 border-yellow-500 rounded-xl p-5 animate-pulse"
                        style="animation-duration: 2s;">
                        @if ($respuestaUsuario === null)
                        {{-- Pregunta inicial --}}
                        <h3 class="text-xl font-black text-yellow-500 text-center mb-5">
                            ¿Te gusta el precio?
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            <button wire:click="responder('si')"
                                class="bg-green-500 hover:bg-green-400 text-white font-black py-3 px-4 rounded-lg transition-all transform hover:scale-105 shadow-lg shadow-green-500/30 uppercase text-sm">
                                Sí, ¡me encanta!
                            </button>
                            <button wire:click="responder('no')"
                                class="bg-red-500 hover:bg-red-400 text-white font-black py-3 px-4 rounded-lg transition-all transform hover:scale-105 shadow-lg shadow-red-500/30 uppercase text-sm">
                                No, muy caro
                            </button>
                        </div>
                        @else
                        <div class="text-center space-y-6">
                            @if ($respuestaUsuario === 'si')
                            <div class="animate-bounce"><span
                                    class="text-green-400 text-5xl font-black italic">¡PERFECTO!</span>
                            </div>
                            <p class="text-lg font-bold text-gray-200">Nuestros especialistas están listos
                                para ayudarte:</p>
                            @else
                            <div class="animate-pulse mb-2">
                                <span class="text-yellow-400 text-5xl font-black italic">TRANQUILO</span>
                            </div>
                            <div
                                class="bg-yellow-500/20 border-2 border-dashed border-yellow-500 rounded-xl p-4 mb-4 transform hover:scale-105 transition-transform cursor-default">
                                <p
                                    class="text-[10px] font-bold text-yellow-500 uppercase tracking-widest mb-1">
                                    🔥 Precio Especial con Agente</p>
                                <p class="text-4xl font-black text-white">${{ $resultadoRebajado }}</p>
                                <p class="text-[10px] text-gray-400 mt-1 italic">Válido contactando ahora
                                    mismo</p>
                            </div>
                            <p class="text-base font-bold text-gray-200">¡Podemos ajustarlo! Habla con un
                                experto para aplicar esta rebaja:</p>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4 text-left">
                                @php
                                $contactos = [
                                ['area' => 'Auction', 'num' => '59164580634', 'color' => 'yellow'],
                                ['area' => 'Academy', 'num' => '59164700293', 'color' => 'yellow'],
                                [
                                'area' => 'Imports & Exports',
                                'num' => '59172976032',
                                'color' => 'yellow',
                                ],
                                [
                                'area' => 'Agente de carga',
                                'num' => '5974518652',
                                'color' => 'yellow',
                                ],
                                ['area' => 'Negocios', 'num' => '59164583783', 'color' => 'yellow'],
                                ['area' => 'IA Groups', 'num' => '59172981315', 'color' => 'yellow'],
                                ];
                                shuffle($contactos);
                                @endphp

                                @foreach ($contactos as $c)
                                @php
                                $montoRef =
                                $respuestaUsuario === 'no' ? $resultadoRebajado : $resultado;
                                $mensajeTexto =
                                'Hola ' .
                                $c['area'] .
                                "! Vengo de la calculadora aérea. El sistema me dio un precio de $" .
                                $montoRef .
                                ' USD' .
                                ($respuestaUsuario === 'no'
                                ? '. ¡Deseo aplicar al precio especial rebajado!'
                                : '');
                                $urlWebWa =
                                'https://web.whatsapp.com/send?phone=' .
                                $c['num'] .
                                '&text=' .
                                urlencode($mensajeTexto);
                                @endphp
                                <a href="{{ $urlWebWa }}" target="_blank"
                                    class="flex items-center justify-between bg-gray-800/50 p-3 rounded-lg border border-gray-700 hover:border-{{ $c['color'] }}-500 transition-all group">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-xs text-gray-400 uppercase tracking-widest">{{ $c['area'] }}</span>
                                        <span class="text-white font-bold">+{{ $c['num'] }}</span>
                                    </div>
                                </a>
                                @endforeach
                            </div>

                            <p class="text-xs text-gray-500 pt-4 italic">Haz clic en el área correspondiente
                                para una atención personalizada.</p>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <!-- Card 1: Single Unit Tip -->
                    <div class="relative group overflow-hidden bg-gradient-to-br from-yellow-500/10 to-amber-500/10 border border-yellow-500/20 rounded-xl p-4 hover:border-yellow-500/40 transition-all duration-300 hover:shadow-lg hover:shadow-yellow-500/10">
                        <div class="absolute -right-4 -top-4 opacity-5 group-hover:opacity-10 transition-opacity transform rotate-12">
                            <svg class="w-24 h-24 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex items-start gap-3 relative z-10">
                            <div class="flex-shrink-0 p-2.5 bg-yellow-500/20 rounded-lg text-yellow-500 ring-1 ring-yellow-500/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                            <div>
                                <h5 class="text-yellow-500 font-bold text-[10px] uppercase tracking-widest mb-1 flex items-center gap-2">
                                    Tip de Cálculo
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                </h5>
                                <p class="text-gray-300 text-xs leading-relaxed font-medium">
                                    Si envías productos similares, registra las especificaciones de <strong class="text-white border-b border-yellow-500/50">una sola unidad</strong>. Nuestro sistema multiplicará automáticamente.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Decimal Formatting Tip -->
                    <div class="relative group overflow-hidden bg-gradient-to-br from-yellow-500/10 to-amber-500/10 border border-yellow-500/20 rounded-xl p-4 hover:border-yellow-500/40 transition-all duration-300 hover:shadow-lg hover:shadow-yellow-500/10">
                        <div class="absolute -right-4 -top-4 opacity-5 group-hover:opacity-10 transition-opacity transform -rotate-12">
                            <svg class="w-24 h-24 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="flex items-start gap-3 relative z-10">
                            <div class="flex-shrink-0 p-2.5 bg-yellow-500/20 rounded-lg text-yellow-500 ring-1 ring-yellow-500/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <h5 class="text-yellow-500 font-bold text-[10px] uppercase tracking-widest mb-1 flex items-center gap-2">
                                    Formato Numérico
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                </h5>
                                <p class="text-gray-300 text-xs leading-relaxed font-medium">
                                    Para decimales, usa siempre el <strong class="text-white border-b border-yellow-500/50">Punto (.)</strong>
                                    <span class="block mt-1 opacity-70 text-[10px]">Ejemplo: <span class="text-white">0.5</span> (Correcto) vs 0,5 (Incorrecto)</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div
                class="mt-16 bg-gradient-to-r from-yellow-500/10 to-amber-500/10 border-l-4 border-yellow-500 rounded-xl p-6 backdrop-blur-sm">
                <div class="flex items-start space-x-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-yellow-500 font-bold mb-2 uppercase tracking-widest text-sm">Nota Importante</h4>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            Los cálculos son estimaciones basados en tarifas estándar. El precio final puede variar según
                            disponibilidad, aerolínea y condiciones especiales del servicio.
                            <strong class="text-yellow-500">Contacta con nuestro equipo</strong> para una cotización
                            oficial y personalizada.
                        </p>
                    </div>
                </div>
            </div> -->
    </div>
</div>