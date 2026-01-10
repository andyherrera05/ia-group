<div class="space-y-6">
    <!-- Card: Búsqueda de Rutas FCL -->
    <div class="backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl relative z-10 overflow-visible">
        <h3 class="text-yellow-500 font-bold mb-6 text-lg uppercase tracking-widest flex items-center">
            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M2 6a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM2 12a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2z" />
            </svg>
            Cotizador de Contenedores FCL
        </h3>
        <p class="text-gray-400 text-sm mb-6">Busca tarifas en tiempo real para contenedores completos (20' y 40')</p>

        <!-- Información del Cliente -->
        <div class="mb-8 p-4 bg-yellow-500/5 border border-yellow-500/20 rounded-xl overflow-visible">
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
                    @error('clienteNombre')
                    <div class="text-red-500 text-[11px] mt-1 font-semibold italic" style="color: #ef4444 !important; font-size: 11px !important;">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Email</label>
                    <input type="email" wire:model.live="clienteEmail" placeholder="ejemplo@correo.com"
                        class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                    @error('clienteEmail')
                    <div class="text-red-500 text-[11px] mt-1 font-semibold italic" style="color: #ef4444 !important; font-size: 11px !important;">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Teléfono</label>
                    <input type="text" wire:model.live="clienteTelefono" placeholder="72732422"
                        class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                    @error('clienteTelefono')
                    <div class="text-red-500 text-[11px] mt-1 font-semibold italic" style="color: #ef4444 !important; font-size: 11px !important;">
                        {{ $message }}
                    </div>
                    @enderror
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
                    @error('clienteCiudad')
                    <div class="text-red-500 text-[11px] mt-1 font-semibold italic" style="color: #ef4444 !important; font-size: 11px !important;">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Dirección</label>
                    <input type="text" wire:model.live="clienteDireccion" placeholder="Dirección completa"
                        class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                    @error('clienteDireccion')
                    <div class="text-red-500 text-[11px] mt-1 font-semibold italic" style="color: #ef4444 !important; font-size: 11px !important;">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Agente de Carga</label>
                    <select wire:model.live="agenteId"
                        class="w-full px-3 py-2 bg-black/60 border border-yellow-500/20 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                        <option value="0" style="background-color:#1a170c; color: #fff;">-- Seleccionar Agente --</option>
                        @foreach($agentes as $agente)
                        <option value="{{ $agente['id'] }}" style="background-color:#1a170c; color: #fff;">{{ $agente['nombre'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-50">
            <!-- Puerto Origen (POL) con Autocompletado -->
            <div class="relative z-[60]">
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd" />
                    </svg>
                    Puerto de Origen (POL)
                </label>
                <input type="text" wire:model.live="searchPOL" x-data x-on:click.away="$wire.showPOLDropdown = false"
                    placeholder="Buscar: Shenzhen, CNSZN, China..."
                    class="w-full px-4 py-1 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all"
                    autocomplete="off">

                <!-- Dropdown de Sugerencias POL -->
                @if ($showPOLDropdown && count($polSuggestions) > 0)
                <div class="absolute z-[9999] w-full mt-1 bg-white border-2 border-gray-200 rounded-lg shadow-2xl overflow-hidden"
                    style="background-color: #030100; color: #FFF; border: 1px solid #f0b100;"
                    x-data="{ activeRegion: null }" style="min-width: 800px; left: 0;">

                    <!-- Pestañas de Regiones -->
                    <div class="border-b border-gray-200 bg-gray-50 p-3"
                        style="background-color: #030100; color: #FFF;">
                        <div class="flex flex-wrap gap-2"
                            style="background-color: #030100; color: #FFF; border: 1px ">
                            @php
                            $regions = collect($polSuggestions)->pluck('region')->unique()->values();
                            @endphp
                            @foreach ($regions as $index => $region)
                            <button type="button"
                                @click="activeRegion = activeRegion === '{{ $region }}' ? null : '{{ $region }}'"
                                :class="activeRegion === '{{ $region }}' ? 'bg-yellow-500 text-white' :
                                            'bg-white text-gray-700 hover:bg-gray-100'"
                                style="background-color: #030100; color: #FFF; border: 1px solid #f0b100;"
                                class="px-4 py-2 rounded text-sm font-medium transition-all border border-gray-300">
                                {{ $region }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Lista de Puertos -->
                    <div class="max-h-80 overflow-y-auto p-3 bg-white"
                        style="background-color: #030100; color: #FFF; border: 1px solid #f0b100;">
                        @foreach ($regions as $region)
                        <div x-show="activeRegion === '{{ $region }}' || activeRegion === null">
                            @if ($loop->first || true)
                            <div class="mb-4">
                                <h4 class="text-xs font-bold text-gray-500 uppercase mb-2 px-2"
                                    x-show="activeRegion === null">{{ $region }}</h4>
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach ($polSuggestions as $port)
                                    @if ($port['region'] === $region)
                                    <button type="button"
                                        wire:click="selectPOL('{{ $port['code'] }}', '{{ $port['name'] }}')"
                                        style="background-color: #030100; color: #FFF;"
                                        class="text-left px-3 py-2 hover:bg-yellow-50 rounded transition-colors text-sm text-gray-700 hover:text-yellow-600">
                                        {{ $port['name'] }}
                                    </button>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if ($polCode)
                <p class="text-xs text-green-400 mt-1 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    Seleccionado: {{ $polCode }}
                </p>
                @endif
            </div>

            <!-- Puerto Destino (POD) con Autocompletado -->
            <div class="relative z-[50]">
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd" />
                    </svg>
                    Puerto de Destino (POD)
                </label>
                <input type="text" wire:model.live="searchPOD" x-data x-on:click.away="$wire.showPODDropdown = false"
                    placeholder="Buscar: Singapore, SGSGP, USA..."
                    class="w-full px-4 py-1 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all"
                    autocomplete="off">

                <!-- Dropdown de Sugerencias POD -->
                @if ($showPODDropdown && count($podSuggestions) > 0)
                <div class="absolute z-[9999] w-full mt-1 bg-white border-2 border-gray-200 rounded-lg shadow-2xl overflow-hidden"
                    style="background-color: #030100; color: #FFF; border: 1px solid #f0b100;"
                    x-data="{ activeRegion: null }" style="min-width: 800px; right: 0;">

                    <!-- Pestañas de Regiones -->
                    <div class="border-b border-gray-200 bg-gray-50 p-3"
                        style="background-color: #030100; color: #FFF;">
                        <div class="flex flex-wrap gap-2">
                            @php
                            $regions = collect($podSuggestions)->pluck('region')->unique()->values();
                            @endphp
                            @foreach ($regions as $index => $region)
                            <button type="button"
                                @click="activeRegion = activeRegion === '{{ $region }}' ? null : '{{ $region }}'"
                                :class="activeRegion === '{{ $region }}' ? 'bg-yellow-500 text-white' :
                                            'bg-white text-gray-700 hover:bg-gray-100'"
                                class="px-4 py-2 rounded text-sm font-medium transition-all border border-gray-300"
                                style="background-color: #030100; color: #FFF;border: 1px solid #f0b100;">
                                {{ $region }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Lista de Puertos -->
                    <div class="max-h-80 overflow-y-auto p-3 bg-white"
                        style="background-color: #030100; color: #FFF;  border: 1px solid #f0b100;">
                        @foreach ($regions as $region)
                        <div x-show="activeRegion === '{{ $region }}' || activeRegion === null">
                            @if ($loop->first || true)
                            <div class="mb-4">
                                <h4 class="text-xs font-bold text-gray-500 uppercase mb-2 px-2"
                                    x-show="activeRegion === null">{{ $region }}</h4>
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach ($podSuggestions as $port)
                                    @if ($port['region'] === $region)
                                    <button type="button"
                                        wire:click="selectPOD('{{ $port['code'] }}', '{{ $port['name'] }}')"
                                        style="background-color: #030100; color: #FFF;"
                                        class="text-left px-3 py-2 hover:bg-yellow-50 rounded transition-colors text-sm text-gray-700 hover:text-yellow-600">
                                        {{ $port['name'] }}
                                    </button>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if ($podCode)
                <p class="text-xs text-green-400 mt-1 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    Seleccionado: {{ $podCode }}
                </p>
                @endif
            </div>

            <!-- Valor de Carga (Nuevo) -->
            <div class="relative z-[40]">
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Valor de Carga (USD)
                </label>
                <input type="number" wire:model="valorMercancia" placeholder="Ej: 5000"
                    class="w-full px-4 py-1 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
            </div>
        </div>
        <div class="mt-6 flex justify-center">
            <button wire:click="buscarTarifasFCL"
                class="relative px-8 py-5 rounded-2xl font-bold text-xl transition-all transform shadow-2xl bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-400 hover:to-amber-400 text-black hover:scale-105 cursor-pointer disabled:opacity-80 disabled:cursor-not-allowed"
                wire:loading.attr="disabled" :class="{ 'cursor-not-allowed': $wire.loadingRates }">

                <div wire:loading wire:target="buscarTarifasFCL" class="flex items-center justify-center space-x-3">
                    <span class="font-medium">
                        @if ($statusMessage && str_starts_with($statusMessage, 'Buscando'))
                        {{ $statusMessage }}...
                        @else
                        Buscando tarifas...
                        @endif
                    </span>
                </div>
                <span wire:loading.remove wire:target="buscarTarifasFCL" class="flex items-center justify-center">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.817-4.817A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                    Buscar Tarifas
                </span>
            </button>
        </div>
    </div>

    <!-- Resultados de Tarifas -->
    @if (count($fclRates) > 0)
    <div
        class="relative overflow-hidden text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-amber-400 to-yellow-500 backdrop-blur-xl border rounded-2xl p-8 shadow-2xl">
        <!-- Fondo sutil inspirado en la imagen (opcional: puedes poner una imagen de fondo real si quieres) -->
        <div class="absolute inset-0 opacity-10 bg-gradient-to-tr from-yellow-600 to-white pointer-events-none"></div>

        <div class="relative z-10">
            <!-- Lista vertical de navieras (cada una en un row con grid)-->
            <div class="space-y-6">
                @php
                $paginatedRates = collect($fclRates)->forPage($currentPage, $perPage);
                $totalPages = ceil(count($fclRates) / $perPage);
                @endphp

                @foreach ($paginatedRates as $index => $rate)
                <div
                    class="bg-gradient-to-r from-yellow-600/60 to-yellow-800/40 backdrop-blur-md border border-yellow-500/30 rounded-2xl p-2 hover:border-yellow-500/50 transition-all duration-300 shadow-xl">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                        <!-- Columna 1: Naviera + Validez -->
                        <div class="md:col-span-1 flex items-center space-x-4">
                            <div
                                class="w-16 h-16 bg-yellow-600/20 rounded-xl flex items-center justify-center border border-yellow-500/40">
                                <span class="text-yellow-400 font-black text-2xl p-2">
                                    {{ strtoupper(substr($rate['shipping_line'], 0, 3)) }}
                                </span>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-xl uppercase">
                                    {{ $rate['shipping_line'] }}
                                </h4>
                                <p class="text-sm text-gray-300">
                                    Válida {{ \Carbon\Carbon::parse($rate['valid_until'])->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Columna 2: Precio 20' -->
                        <div class="text-center">
                            @if (!is_null($rate['gp20'] ?? null))
                            <button wire:click="selectRate({{ $index }}, 'gp20')"
                                class="block w-full bg-white/10 rounded-2xl py-1 hover:bg-yellow-600/30 hover:scale-105 transition-all duration-300 border border-yellow-500/30">
                                <p class="text-xl font-black text-yellow-400 mt-2">
                                    20 GP
                                </p>
                                <p class="text-xl font-black text-white">
                                    USD {{ number_format($rate['gp20']) }}
                                </p>
                                <p
                                    class="text-xs font-black text-yellow-400 mt-2">
                                    ✓ Click para cotizar
                                </p>
                            </button>
                            @else
                            <div class="bg-gray-800/50 rounded-2xl py-1 border border-gray-600">
                                <p class="text-2xl text-gray-500">N/A</p>
                            </div>
                            @endif
                        </div>

                        <!-- Columna 3: Precio 40' -->
                        <div class="text-center">
                            @if (!is_null($rate['gp40'] ?? null))
                            <button wire:click="selectRate({{ $index }}, 'gp40')"
                                class="block w-full bg-white/10 rounded-2xl py-1 hover:bg-yellow-600/30 hover:scale-105 transition-all duration-300 border border-yellow-500/30">
                                <p class="text-xl font-black text-yellow-400 mt-2">
                                    40 GP
                                </p>
                                <p class="text-2xl font-black text-white">
                                    USD {{ number_format($rate['gp40']) }}
                                </p>
                                <p
                                    class="text-xs font-black text-yellow-400 mt-2">
                                    ✓ Click para cotizar
                                </p>
                            </button>
                            @else
                            <div class="bg-gray-800/50 rounded-2xl py-1 border border-gray-600">
                                <p class="text-2xl text-gray-500">N/A</p>
                            </div>
                            @endif
                        </div>

                        <!-- Columna 4: Precio NOR + info extra (tránsito/cierre) -->
                        <div class="text-center">
                            @if (!is_null($rate['hq40'] ?? null))
                            <button wire:click="selectRate({{ $index }}, 'hq40')"
                                class="block w-full bg-white/10 rounded-2xl py-1 hover:bg-yellow-600/30 hover:scale-105 transition-all duration-300 border border-yellow-500/30">
                                <p class="text-xl font-black text-yellow-400 mt-2">
                                    40 HQ
                                </p>
                                <p class="text-2xl font-black text-white">
                                    USD {{ number_format($rate['hq40']) }}
                                </p>
                                <p
                                    class="text-xs font-black text-yellow-400 mt-2">
                                    ✓ Click para cotizar
                                </p>
                            </button>
                            @else
                            <div class="bg-gray-800/50 rounded-2xl py-1 border border-gray-600">
                                <p class="text-2xl text-gray-500">N/A</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Paginación -->
            @if ($totalPages > 1)
            <div class="mt-10 flex flex-col md:flex-row items-center justify-between gap-4 border-t border-yellow-500/20 pt-8">
                <p class="text-gray-400 text-sm">
                    Mostrando <span class="text-yellow-500 font-bold">{{ ($currentPage - 1) * $perPage + 1 }}</span>
                    a <span class="text-yellow-500 font-bold">{{ min($currentPage * $perPage, count($fclRates)) }}</span>
                    de <span class="text-yellow-500 font-bold">{{ count($fclRates) }}</span> resultados
                </p>

                <div class="flex items-center space-x-2">
                    <!-- Botón Anterior -->
                    <button wire:click="previousPage"
                        @if($currentPage==1) disabled @endif
                        class="px-4 py-2 rounded-xl border border-yellow-500/30 text-yellow-500 hover:bg-yellow-500 hover:text-black transition-all disabled:opacity-30 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Números de Página -->
                    <div class="flex items-center space-x-1">
                        @for ($i = 1; $i <= $totalPages; $i++)
                            <button wire:click="setPage({{ $i }})"
                            class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all
                                        {{ $currentPage == $i 
                                            ? 'bg-yellow-500 text-black shadow-lg shadow-yellow-500/20' 
                                            : 'text-gray-400 hover:bg-yellow-500/20 hover:text-yellow-500 border border-transparent' }}">
                            {{ $i }}
                            </button>
                            @endfor
                    </div>

                    <!-- Botón Siguiente -->
                    <button wire:click="nextPage"
                        @if($currentPage==$totalPages) disabled @endif
                        class="px-4 py-2 rounded-xl border border-yellow-500/30 text-yellow-500 hover:bg-yellow-500 hover:text-black transition-all disabled:opacity-30 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if ($loadingRates)
    <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-12 shadow-xl text-center">
        <p class="text-gray-300 font-medium">Buscando mejores tarifas...</p>
    </div>
    @elseif (count($fclRates) === 0)
    <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-12 shadow-xl text-center">
        <div
            class="w-20 h-20 mx-auto my-6 bg-yellow-500/5 border-2 border-yellow-500/20 rounded-full flex items-center justify-center">
            <svg class="w-10 h-10 text-yellow-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <p class="text-gray-400 my-6 text-sm">Selecciona los puertos de origen y destino para ver las tarifas
            disponibles</p>
    </div>
    @endif

    <!-- Botón Limpiar -->
    <div class="flex justify-end">
        <button wire:click="limpiar"
            class="bg-white/5 hover:bg-white/10 text-gray-300 font-bold py-1 px-8 rounded-xl border border-white/10 hover:border-yellow-500/50 transition-all">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Limpiar Búsqueda
        </button>
    </div>
</div>