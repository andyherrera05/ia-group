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
        <!-- Multi-Product System -->
        <div class="space-y-4">
            <!-- Compact Add Form -->
            <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-5 shadow-xl">
                <h4 class="text-yellow-500 font-bold text-sm mb-4 uppercase tracking-widest flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Agregar Producto a Cotizar
                </h4>

                <div class="space-y-3">

                    <!-- Row 1: Image & Name -->
                    <div class="flex gap-3" style="align-items: center;">
                        <!-- Image Upload (Square with Preview) -->
                        <div style="width: 150px; height: 150px; min-width: 150px; min-height: 150px;" class="shrink-0 relative group">
                            <label class="block w-full h-full rounded-lg border-2 border-dashed border-yellow-500/30 hover:border-yellow-500 bg-black/20 cursor-pointer overflow-hidden transition-all relative">
                                <input type="file" wire:model="temp_manualImagen" class="hidden">
                                @if ($temp_manualImagen)
                                <img src="{{ $temp_manualImagen->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover">
                                @elseif ($temp_imagen)
                                <img src="{{ $temp_imagen }}" class="absolute inset-0 w-full h-full object-cover">
                                @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-500 group-hover:text-yellow-500">
                                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-[10px] uppercase font-bold">Foto</span>
                                </div>
                                @endif
                                <!-- Loading State -->
                                <div wire:loading wire:target="temp_manualImagen" class="absolute inset-0 bg-black/60 flex items-center justify-center z-10">
                                    <div class="w-6 h-6 border-2 border-yellow-500 border-t-transparent rounded-full animate-spin"></div>
                                </div>
                            </label>
                        </div>

                        <!-- Name Input & Extra Fields -->
                        <div class="flex-1 flex flex-col gap-2">
                            <input type="text" wire:model="temp_producto" placeholder="Nombre del Producto (Ej: Zapatillas)"
                                class="w-full h-[105px] px-4 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-lg">
                            @error('temp_producto') <span class="text-red-400 text-[10px] ml-1">{{ $message }}</span> @enderror

                            <div class="grid grid-cols-2 gap-2">
                                <div class="relative">
                                    <input type="text" wire:model.live="temp_hs_code" placeholder="HS Code"
                                        class="w-full h-[105px] p-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white text-xs placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all">

                                    @if(!empty($arancelSuggestions))
                                    <div class="absolute top-full left-0 w-full z-50 bg-gray-900 border border-gray-700 rounded-lg shadow-xl max-h-60 overflow-y-auto mt-1" @click.away="$wire.limpiarArancelSearch()" style="background-color: rgba(0, 0, 0, 0.9);">
                                        @foreach($arancelSuggestions as $suggestion)
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
                                    <input type="number" wire:model="temp_arancel" placeholder="Arancel %"
                                        class="w-full h-[105px] p-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white text-xs placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs">%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Basic Metrics -->
                    <div class="grid grid-cols-4 gap-3">

                        <div>
                            <label class="text-[10px] text-gray-400 uppercase tracking-wider pl-1">Cantidad</label>
                            <input type="number" wire:model="temp_cantidad" placeholder="1" class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white text-sm focus:border-yellow-500/50 focus:outline-none placeholder-gray-600">
                            @error('temp_cantidad') <span class="text-red-400 text-[10px] block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-400 uppercase tracking-wider pl-1">Cajas</label>
                            <input type="number" wire:model="temp_cajas" placeholder="1" class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white text-sm focus:border-yellow-500/50 focus:outline-none placeholder-gray-600">
                            @error('temp_cajas') <span class="text-red-400 text-[10px] block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-400 uppercase tracking-wider pl-1">Valor Unit. ($)</label>
                            <input type="number" wire:model="temp_valor_unitario" placeholder="0.00" class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white text-sm focus:border-yellow-500/50 focus:outline-none placeholder-gray-600">
                            @error('temp_valor_unitario') <span class="text-red-400 text-[10px] block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-400 uppercase tracking-wider pl-1">Peso Unit. (Kg)</label>
                            <input type="number" wire:model="temp_peso_unitario" placeholder="0.00" class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white text-sm focus:border-yellow-500/50 focus:outline-none placeholder-gray-600">
                            @error('temp_peso_unitario') <span class="text-red-400 text-[10px] block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Row 3: Dimensions & Action -->
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-3 items-center">
                        <!-- Auto-fill Helper -->
                        <div class="flex items-center gap-2 mb-3 border-b border-white/5 pb-3">
                            <div class="flex-1">
                                <label class="block text-[8px] text-gray-500 mb-1">Dimensión Única (Ref)</label>
                                <input type="number" wire:model.blur="temp_dimension_total" placeholder="Dimensión de un lado"
                                    class="w-full py-3 bg-black/40 border border-yellow-500/10 rounded px-2 py-1 text-xs text-white focus:border-yellow-500/50 outline-none text-center">
                            </div>
                            <div class="flex gap-2">
                                <button type="button" wire:click="aplicarDimensiones('square')" title="Caja Cuadrada"
                                    class="w-24 h-24 rounded border border-white/10 hover:border-yellow-500/50 hover:bg-white/5 flex items-center justify-center transition-all p-1">
                                    <img src="{{ asset('images/cajas/caja_cuadrada.png') }}" alt="Square" class="w-full h-full object-contain opacity-70 hover:opacity-100">
                                </button>
                                <button type="button" wire:click="aplicarDimensiones('rectangular')" title="Caja Rectangular"
                                    class="w-24 h-24 rounded border border-white/10 hover:border-yellow-500/50 hover:bg-white/5 flex items-center justify-center transition-all p-1">
                                    <img src="{{ asset('images/cajas/caja_rectangular.png') }}" alt="Rectangular" class="w-full h-full object-contain opacity-70 hover:opacity-100">
                                </button>
                                <button type="button" wire:click="aplicarDimensiones('flat')" title="Caja Plana"
                                    class="w-24 h-24 rounded border border-white/10 hover:border-yellow-500/50 hover:bg-white/5 flex items-center justify-center transition-all p-1">
                                    <img src="{{ asset('images/cajas/caja_plana.png') }}" alt="Flat" class="w-full h-full object-contain opacity-70 hover:opacity-100">
                                </button>
                                <button type="button" wire:click="aplicarDimensiones('long')" title="Caja Alargada"
                                    class="w-24 h-24 rounded border border-white/10 hover:border-yellow-500/50 hover:bg-white/5 flex items-center justify-center transition-all p-1">
                                    <img src="{{ asset('images/cajas/caja_tubo.png') }}" alt="Long" class="w-full h-full object-contain opacity-70 hover:opacity-100">
                                </button>
                            </div>
                        </div>
                        <!-- Dimensions -->
                        <div class="md:col-span-2 bg-black/20 rounded-lg p-2 border border-white/5 relative">
                            <label class="text-[10px] text-gray-500 block mb-2 text-center">Dimensiones (cm) o CBM Directo</label>
                            <div class="flex gap-2 bg-black/20 rounded-lg p-2 border border-white/5">
                                <div class="flex-1">
                                    <label class="block text-[4px] text-gray-500 text-center mb-1 uppercase">Largo</label>
                                    <input type="number" wire:model="temp_largo" placeholder="0" class="w-full bg-transparent border-b border-gray-700 text-center text-xs text-white focus:border-yellow-500 outline-none pb-1" title="Largo (cm)">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-[4px] text-gray-500 text-center mb-1 uppercase">Ancho</label>
                                    <input type="number" wire:model="temp_ancho" placeholder="0" class="w-full bg-transparent border-b border-gray-700 text-center text-xs text-white focus:border-yellow-500 outline-none pb-1" title="Ancho (cm)">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-[4px] text-gray-500 text-center mb-1 uppercase">Alto</label>
                                    <input type="number" wire:model="temp_alto" placeholder="0" class="w-full bg-transparent border-b border-gray-700 text-center text-xs text-white focus:border-yellow-500 outline-none pb-1" title="Alto (cm)">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-[4px] text-blue-300/70 text-center mb-1 uppercase">CBM</label>
                                    <input type="number" step="0.01" wire:model="temp_cbm" placeholder="0.00" class="w-full bg-transparent border-b border-blue-500/50 text-center text-xs text-blue-300 focus:border-blue-500 outline-none pb-1" title="CBM Directo">
                                </div>
                            </div>
                            @error('temp_dimensiones') <div class="absolute -bottom-4 left-0 w-full text-center"><span class="text-red-400 text-xs bg-black/80 px-1 rounded">{{ $message }}</span></div> @enderror
                        </div>

                        <!-- Action Button -->
                        <button wire:click="agregarProducto" class="w-full px-6 h-[52px] p-2 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-400 hover:to-yellow-500 text-black font-bold text-xs rounded-lg shadow-lg hover:shadow-yellow-500/20 transition-all flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            AGREGAR PRODUCTO
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product List -->
            @if(count($productos) > 0)
            <div class="bg-white/5 border border-white/10 rounded-xl p-4 animate-fade-in">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex justify-between items-center">
                    Lista de Productos
                    <span class="bg-yellow-500/20 text-yellow-500 px-2 py-0.5 rounded text-[10px]">{{ count($productos) }} items</span>
                </h4>
                <div class="space-y-2 max-h-60 overflow-y-auto custom-scrollbar pr-1">
                    @foreach($productos as $index => $prod)
                    <div class="flex items-center bg-black/30 p-2 rounded-lg border border-white/5 hover:border-yellow-500/30 transition-colors group">
                        <!-- Image -->
                        <div class="w-10 h-10 rounded border border-white/10 overflow-hidden flex-shrink-0 mr-3">
                            @if($prod['imagen'])
                            <img src="{{ $prod['imagen'] }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full bg-white/5 flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0 mr-2">
                            <h5 class="text-white text-sm font-medium truncate">{{ $prod['producto'] }}</h5>
                            <div class="flex flex-wrap gap-2 text-[10px] text-gray-400 mt-0.5">
                                <span class="text-yellow-500/80">{{ $prod['cantidad'] }} uds</span>
                                <span>•</span>
                                <span>${{ number_format($prod['valor_unitario'], 2) }} c/u</span>
                                <span>•</span>
                                <span>{{ $prod['peso_unitario'] }} kg</span>
                            </div>
                        </div>

                        <!-- Totals & Actions -->
                        <div class="text-right flex flex-col justify-center">
                            <span class="text-white font-mono text-xs font-bold">${{ number_format($prod['total_valor'], 2) }}</span>
                            <span class="text-[9px] text-gray-500">{{ $prod['total_peso'] }}kg total</span>
                        </div>

                        <button wire:click="eliminarProducto({{ $index }})" class="ml-3 p-1.5 text-gray-500 hover:text-red-400 bg-white/5 rounded-lg hover:bg-white/10 transition-all opacity-0 group-hover:opacity-100">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Consolidated Totals Display (Read Only) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="bg-black/40 p-3 rounded-xl border border-white/5">
                    <div class="text-[10px] text-gray-500 uppercase">Valor Total</div>
                    <div class="text-lg font-bold text-green-400">${{ number_format($valorMercancia, 2) }}</div>
                </div>
                <div class="bg-black/40 p-3 rounded-xl border border-white/5">
                    <div class="text-[10px] text-gray-500 uppercase">Peso Total</div>
                    <div class="text-lg font-bold text-white">{{ $peso }} <span class="text-xs text-gray-600">kg</span></div>
                </div>
                <div class="bg-black/40 p-3 rounded-xl border border-white/5">
                    <div class="text-[10px] text-gray-500 uppercase">Volumen Total</div>
                    <div class="text-lg font-bold text-white">{{ $volumen }} <span class="text-xs text-gray-600">m³</span></div>
                </div>
                <div class="bg-black/40 p-3 rounded-xl border border-white/5">
                    <div class="text-[10px] text-gray-500 uppercase">Items</div>
                    <div class="text-lg font-bold text-yellow-500">{{ $cantidad }} <span class="text-xs text-gray-600">uds</span></div>
                </div>
            </div>
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
                                    <span class="text-white font-medium">Requiere la recepción del producto en otro departamento?</span>
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
            </div>
        </div>

    </div>

    <!-- Buttons -->
    <div class="flex flex-col sm:flex-row gap-4">
        <button wire:click="calcularResultado"
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