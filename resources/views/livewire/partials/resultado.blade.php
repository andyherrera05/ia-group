            @if ($selectedRate && $selectedContainer)
                <div class="text-center mb-4">
                    <p class="text-yellow-400 font-bold text-lg">
                        Contenedor seleccionado:
                        {{ match ($selectedContainer) {
                            'gp20' => "20' Standard",
                            'gp40' => "40' Standard",
                            'hq40' => "40' High Cube",
                        } }}
                        - {{ $selectedRate['shippingLine'] }}
                    </p>
                </div>
            @endif
