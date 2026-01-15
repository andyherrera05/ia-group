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
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach([
            ['id' => 'sedan', 'name' => 'Sedan', 'icon' => 'icon_type-sedan.svg'],
            ['id' => 'suv', 'name' => 'SUV', 'icon' => 'icon_type-suv.svg'],
            ['id' => 'pickup', 'name' => 'Pick-up', 'icon' => 'icon_type-pick-up.svg'],
            ['id' => 'hatchback', 'name' => 'Hatchback', 'icon' => 'icon_type-hatchback.svg'],
            ['id' => 'coupe', 'name' => 'Coupe', 'icon' => 'icon_type-coupe.svg'],
            ['id' => 'convertible', 'name' => 'Convertible', 'icon' => 'icon_type-convertible.svg'],
            ['id' => 'wagon', 'name' => 'Wagon', 'icon' => 'icon_type-wagon.svg'],
            ['id' => 'minivan', 'name' => 'Mini Van', 'icon' => 'icon_type-mini-van.svg'],
            ['id' => 'van', 'name' => 'Van/Furgoneta', 'icon' => 'icon_type-van.svg'],
            ['id' => 'truck', 'name' => 'Camión', 'icon' => 'icon_type-truck.svg'],
            ['id' => 'bus', 'name' => 'Bus', 'icon' => 'icon_type-bus.svg'],
            ['id' => 'heavy', 'name' => 'Maquinaria', 'icon' => 'icon_type-heavy-equipment.svg'],
            ] as $v)
            <button type="button" wire:click="$set('tipoVehiculo', '{{ $v['id'] }}')"
                class="group relative overflow-hidden px-2 py-3 border-2 rounded-xl transition-all {{ $tipoVehiculo === $v['id'] ? 'border-yellow-500 bg-yellow-500/10' : 'border-yellow-500/10 hover:border-yellow-500/50' }}">
                <div class="relative z-10">
                    <!-- Icono SVG -->
                    @php
                    $iconPath = public_path('images/movilidades/' . $v['icon']);
                    $iconBase64 = '';
                    if (file_exists($iconPath)) {
                    $iconData = file_get_contents($iconPath);
                    $iconBase64 = 'data:image/svg+xml;base64,' . base64_encode($iconData);
                    } else {
                    // Fallback attempts for different folder structures
                    $altPath = base_path('/images/movilidades/' . $v['icon']);
                    if (file_exists($altPath)) {
                    $iconData = file_get_contents($altPath);
                    $iconBase64 = 'data:image/svg+xml;base64,' . base64_encode($iconData);
                    }
                    }
                    @endphp
                    @if($iconBase64)
                    <img src="{{ $iconBase64 }}" class="w-10 h-10 mx-auto mb-2 opacity-80 group-hover:opacity-100 transition-opacity" alt="{{ $v['name'] }}">
                    @else
                    <!-- Fallback si falla la carga -->
                    <div class="w-10 h-10 mx-auto mb-2 flex items-center justify-center bg-yellow-500/20 rounded-full">
                        <span class="text-xs text-yellow-500 font-bold">ICON</span>
                    </div>
                    @endif
                    <span class="block text-center text-xs font-bold {{ $tipoVehiculo === $v['id'] ? 'text-yellow-400' : 'text-gray-400 group-hover:text-yellow-400' }}">
                        {{ $v['name'] }}
                    </span>
                </div>
            </button>
            @endforeach
        </div>
    </div>

    <!-- Características del Vehículo -->
    <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl">
        <h3 class="text-yellow-500 font-bold mb-4 text-sm uppercase tracking-widest">Características del Vehículo</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Marca -->
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Marca</label>
                <input type="text" wire:model="marca" placeholder="Ej: Toyota"
                    class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
            </div>
            <!-- Modelo -->
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Modelo</label>
                <input type="text" wire:model="modelo" placeholder="Ej: Hilux"
                    class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
            </div>
            <!-- Año -->
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Año</label>
                <input type="number" wire:model="anio" placeholder="Ej: 2024"
                    class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
            </div>
            <!-- Combustible -->
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Tipo de Combustible</label>
                <select wire:model="combustible"
                    class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                    <option value="gasolina" style="background-color:#1a170c;">Gasolina</option>
                    <option value="diesel" style="background-color:#1a170c;">Diesel</option>
                    <option value="hibrido" style="background-color:#1a170c;">Híbrido</option>
                    <option value="electrico" style="background-color:#1a170c;">Eléctrico (EV)</option>
                </select>
            </div>
            <!-- Puerto de Origen -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Puerto de Origen (China)</label>
                <select wire:model="polVehiculo"
                    class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
                    <option value="" style="background-color:#1a170c;">-- Seleccionar Puerto --</option>
                    <option value="Shanghai" style="background-color:#1a170c;">Shanghai</option>
                    <option value="Tianjin" style="background-color:#1a170c;">Tianjin (Xingang)</option>
                    <option value="Guangzhou" style="background-color:#1a170c;">Guangzhou (Nansha)</option>
                    <option value="Ningbo" style="background-color:#1a170c;">Ningbo</option>
                    <option value="Qingdao" style="background-color:#1a170c;">Qingdao</option>
                    <option value="Dalian" style="background-color:#1a170c;">Dalian</option>
                </select>
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
                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Largo (m)</label>
                <input type="number" wire:model="largoVehiculo" step="0.01" placeholder="Ej: 4.85"
                    class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Ancho (m)</label>
                <input type="number" wire:model="anchoVehiculo" step="0.01" placeholder="Ej: 1.90"
                    class="w-full px-3 py-2 bg-black/40 border border-yellow-500/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500 transition-all text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-tighter">Alto (m)</label>
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

    <!-- Valor de Mercancía -->
    <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl relative z-30">
        <h3 class="text-yellow-500 font-bold mb-4 text-sm uppercase tracking-widest">Valor del Vehículo (USD)</h3>
        <input type="number" wire:model="valorMercancia" step="1" placeholder="Ej: 15000"
            class="w-full px-4 py-3 bg-black/40 border border-yellow-500/30 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
        <p class="text-xs text-gray-500 mt-2">Valor comercial para cálculo de seguro y aduanas</p>
    </div>

    <!-- Servicios Adicionales -->
    <div class="bg-white/5 backdrop-blur-xl border border-yellow-500/20 rounded-2xl p-6 shadow-xl">
        <h3 class="text-yellow-500 font-bold mb-6 text-sm uppercase tracking-widest">Servicios Adicionales</h3>
        <div class="space-y-4">
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
                            <p class="text-gray-500 text-xs mt-0.5">Transferencia bancaria internacional estándar (SWIFT).</p>
                        </div>
                    </label>

                    <label class="flex items-start space-x-3 cursor-pointer group">
                        <input type="radio" wire:model="pagosInternacionalesSwiftAutos" value="sin_swift"
                            class="mt-1 w-4 h-4 text-yellow-500 border-gray-600 focus:ring-yellow-500 bg-black/40">
                        <div class="flex-1">
                            <span class="text-gray-300 text-sm font-medium group-hover:text-yellow-500 transition-colors">SIN Swift Bancario / USDT</span>
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
        <button wire:click="calcular"
            class="flex-1 bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-400 hover:to-amber-400 text-black font-bold py-4 px-6 rounded-xl transition-all transform hover:scale-105 shadow-lg shadow-yellow-500/30 flex items-center justify-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            <span>Calcular Cotización</span>
        </button>

        <button wire:click="limpiar"
            class="sm:w-auto bg-white/5 hover:bg-white/10 text-gray-300 font-bold py-4 px-6 rounded-xl border border-white/10 hover:border-yellow-500/50 transition-all">
            Limpiar
        </button>
    </div>
</div>