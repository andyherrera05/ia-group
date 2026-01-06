<?php

namespace App\Livewire;

use App\Models\Rate;
use App\Models\Search;
use App\Models\ShippingLine;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class CalculadoraMaritima extends Component
{
    use WithFileUploads;
    public $tipoCarga = 'lcl';

    public $peso;


    public $volumen;

    public $valorMercancia;

    public $cantidad;

    public $cbm_directo;


    public $q;

    public $producto = '';

    public $id_producto = '';

    public $imagen = '';

    public $esAnalisis = false;

    public $manualImagen;

    public function mount()
    {
        $qParam = request()->query('q') ?? $this->q;

        if ($qParam) {
            try {
                $decoded = json_decode(base64_decode($qParam), true);

                if (is_array($decoded)) {
                    $this->temp_producto = $decoded['producto'] ?? '';
                    $this->temp_imagen = $decoded['imagen'] ?? '';

                    $this->temp_cantidad = isset($decoded['cantidad']) ? floatval($decoded['cantidad']) : 1;

                    // El payload trae PESO UNITARIO y CBM UNITARIO
                    $this->temp_peso_unitario = isset($decoded['peso']) ? floatval($decoded['peso']) : 0;

                    $valorTotal = isset($decoded['valorMercancia']) ? floatval($decoded['valorMercancia']) : 0;
                    // REVERTIDO: Tratamos valorMercancia como Valor Unitario tal cual llega
                    $this->temp_valor_unitario = $valorTotal;

                    // Manejo de Volumen (UNITARIO)
                    $cbmUnitario = isset($decoded['cbm']) ? floatval($decoded['cbm']) : 0;
                    if ($cbmUnitario > 0) {
                        $this->temp_cbm = $cbmUnitario;
                    }

                    $this->id_producto = $decoded['id_producto'] ?? '';

                    if ($this->id_producto) {
                        $this->esAnalisis = true;
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error al decodificar parámetro "q": ' . $e->getMessage());
            }
        }

        shuffle($this->agentes);
    }

    public $productos = [];
    public $temp_producto = '';
    public $temp_imagen = '';
    public $temp_manualImagen;
    public $temp_cantidad = 1;
    public $temp_peso_unitario = 0;
    public $temp_largo = 0;
    public $temp_ancho = 0;
    public $temp_alto = 0;
    public $temp_cbm = 0;
    public $temp_valor_unitario = 0;
    public $volumenTotal = 0;
    public $temp_hs_code = '';
    public $temp_arancel = 0;
    public $arancelSuggestions = [];

    public function updatedTempHsCode()
    {
        if (strlen($this->temp_hs_code) < 3) {
            $this->arancelSuggestions = [];
            return;
        }

        $jsonPath = base_path('database/mockup/aranceles.json');
        if (!File::exists($jsonPath)) {
            Log::error("JSON de aranceles no encontrado en: " . $jsonPath);
            return;
        }

        try {
            $jsonContent = File::get($jsonPath);
            $data = json_decode($jsonContent, true);
            $items = [];

            // Aplanar estructura: Capitulos -> Items
            foreach ($data['capitulos'] as $capitulo) {
                if (isset($capitulo['items'])) {
                    foreach ($capitulo['items'] as $item) {
                        $items[] = $item;
                    }
                }
            }

            $search = strtolower($this->temp_hs_code);
            $this->arancelSuggestions = array_filter($items, function ($item) use ($search) {
                return str_contains(strtolower($item['codigo_hs']), $search) || 
                       str_contains(strtolower($item['descripcion']), $search);
            });

            // Limitar a 10 resultados
            $this->arancelSuggestions = array_slice($this->arancelSuggestions, 0, 10);

        } catch (\Exception $e) {
            Log::error("Error buscando aranceles: " . $e->getMessage());
            $this->arancelSuggestions = [];
        }
    }

    public function selectArancel($codigo, $arancel)
    {
        $this->temp_hs_code = $codigo;
        $this->temp_arancel = $arancel;
        $this->arancelSuggestions = [];
    }

    public function limpiarArancelSearch()
    {
        $this->arancelSuggestions = [];
    }

    public function agregarProducto()
    {
        $this->validate([
            'temp_producto' => 'required|min:3',
            'temp_cantidad' => 'required|numeric|min:1',
            'temp_valor_unitario' => 'required|numeric|min:0',
            'temp_peso_unitario' => 'required|numeric|gt:0',
        ], [
            'temp_producto.required' => 'El nombre es obligatorio',
            'temp_cantidad.required' => 'La cantidad es obligatoria',
            'temp_cantidad.min' => 'La cantidad debe ser mayor a 0',
            'temp_valor_unitario.required' => 'El valor es obligatorio',
            'temp_peso_unitario.required' => 'El peso es obligatorio',
            'temp_peso_unitario.gt' => 'El peso debe ser mayor a 0',
        ]);

        $hasDimensions = ($this->temp_largo > 0 && $this->temp_ancho > 0 && $this->temp_alto > 0);
        $hasCbm = ($this->temp_cbm > 0);

        if (!$hasDimensions && !$hasCbm) {
            $this->addError('temp_dimensiones', 'Debes ingresar las Dimensiones (L, W, H) o el CBM.');
            return;
        }

        $imagenUrl = '';
        if ($this->temp_manualImagen) {
            $imagenUrl = $this->temp_manualImagen->temporaryUrl();
        } elseif ($this->temp_imagen) {
            $imagenUrl = $this->temp_imagen;
        }
        $volumenUnitario = 0;

        if ($this->temp_largo > 0 && $this->temp_ancho > 0 && $this->temp_alto > 0) {
            $volumenUnitario = ($this->temp_largo * $this->temp_ancho * $this->temp_alto) / 1000000;
        } elseif ($this->temp_cbm > 0) {
            $volumenUnitario = $this->temp_cbm;
        } else {

            $volumenUnitario = 0.01;
        }

        $pesoTotalItem = $this->temp_peso_unitario;
        $volumenTotalItem = $volumenUnitario;
        $valorTotalItem = $this->temp_valor_unitario * $this->temp_cantidad;

        $this->productos[] = [
            'id' => uniqid(),
            'producto' => $this->temp_producto,
            'imagen' => $imagenUrl,
            'cantidad' => $this->temp_cantidad,
            'peso_unitario' => $this->temp_peso_unitario,
            'largo' => $this->temp_largo,
            'ancho' => $this->temp_ancho,
            'alto' => $this->temp_alto,
            'volumen_unitario' => $volumenUnitario,
            'valor_unitario' => $this->temp_valor_unitario,
            'total_peso' => $pesoTotalItem,
            'total_volumen' => $volumenTotalItem,
            'total_valor' => $valorTotalItem,
            'hs_code' => $this->temp_hs_code,
            'arancel' => $this->temp_arancel
        ];

        $this->temp_producto = '';
        $this->temp_imagen = '';
        $this->temp_manualImagen = null;
        $this->temp_cantidad = 1;
        $this->temp_peso_unitario = 0;
        $this->temp_largo = 0;
        $this->temp_ancho = 0;
        $this->temp_alto = 0;
        $this->temp_cbm = 0;
        $this->temp_valor_unitario = 0;
        $this->temp_hs_code = '';
        $this->temp_arancel = 0;

        $this->calcularTotales();
    }

    public function eliminarProducto($index)
    {
        if (isset($this->productos[$index])) {
            unset($this->productos[$index]);
            $this->productos = array_values($this->productos);
            $this->calcularTotales();
        }
    }

    public function calcularTotales()
    {
        $pesoTotal = 0;
        $this->volumenTotal = 0; // Resetear acumulador
        $valorTotal = 0;
        $cantidadTotal = 0;

        foreach ($this->productos as $prod) {
            $pesoTotal += $prod['total_peso'];
            $this->volumenTotal += $prod['total_volumen'];
            $valorTotal += $prod['total_valor'];
            $cantidadTotal += $prod['cantidad'];
        }
        $this->peso = $pesoTotal;
        $this->volumen = number_format($this->volumenTotal, 4);
        $this->valorMercancia = $valorTotal;
        $this->cantidad = $cantidadTotal;

        if (count($this->productos) > 1) {
            $this->producto = 'Carga Consolidada (' . count($this->productos) . ' items)';
        } elseif (count($this->productos) == 1) {
            $this->producto = $this->productos[0]['producto'];
        } else {
            $this->producto = '';
        }

        $this->calcular();
    }

    public $largo, $ancho, $alto;
    public $origen = '';
    public $destino = '';

    public $volumenCalculado = null;
    public $metodoVolumen = 'dimensiones';

    public $resultado = null;
    public $desglose = [];
    public $fecha;

    public $mostrarPregunta = false;
    public $respuestaUsuario = null;

    public $searchPOL = '';
    public $searchPOD = '';
    public $polCode = '';
    public $podCode = '';
    public $polSuggestions = [];
    public $podSuggestions = [];
    public $showPOLDropdown = false;
    public $showPODDropdown = false;

    public $fclRates = [];
    public $perPage = 5;
    public $currentPage = 1;
    public $loadingRates = false;
    public $statusMessage = null;
    public $rates = null;
    public $progress = null;
    public $currentRunId = null;

    public $selectedContainer = 'gp20';
    public $selectedRate = null;
    public $mostrarModal = false;

    public $freight = 51.29;

    // Control de visibilidad del resultado
    public $mostrarDesglose = false;

    public function calcularResultado()
    {
        $this->calcular();
        $this->mostrarDesglose = true;
        $this->dispatch('scroll-to-result');
    }
    public $importation = 8.58;
    public $total_import_costs = 47.32;
    public $insurance = 38.18;
    public $customs_clearance_cost = 17.09;
    public $logistics_expenses = 33.70;


    public $recojoAlmacen = false;
    public $destinoFinal = 'tarija';
    public $departamentoDestino = '';


    public $verificacionProducto = false;
    public $verificacionCalidad = false;
    public $verificacionEmpresaDigital = false;
    public $verificacionEmpresaPresencial = false;

    public $selectedRateIndex = null;

    public $tipoCobroActual = '';
    public $unidadActual = '';
    public $valorFacturadoActual = 0;
    public $cbmFacturadoActual = 0;
    public $desglose_reporte = [];

    public $clienteNombre = '';
    public $clienteEmail = '';
    public $clienteTelefono = '';
    public $clienteDireccion = '';
    public $clienteCiudad = '';
    public $agenteId = '';
    public $gastosAdicionales = [];

    public $agentes = [
        [
            'id' => 1,
            'nombre' => 'Alejandra Gonzales Soliz',
            'email' => 'logistica@iagroups.com',
            'telefono' => '702693251'
        ],
        [
            'id' => 2,
            'nombre' => 'Christian Quispe Tolaba',
            'email' => 'auction@iagroups.com',
            'telefono' => '64580634'
        ],
        [
            'id' => 3,
            'nombre' => 'Brenda Garcia Gonzales',
            'email' => 'consultora@iagroups.com',
            'telefono' => '64583783'
        ],
        [
            'id' => 4,
            'nombre' => 'Ivana Rodas Vasquez',
            'email' => 'agentes@iagroups.com',
            'telefono' => '64583783'
        ],
        [
            'id' => 5,
            'nombre' => 'Marcelo Veliz',
            'email' => 'tarija@iagroups.com',
            'telefono' => '72981315'
        ],
        // [
        //     'id' => 6,
        //     'nombre' => 'Alejandra Gonzales Soliz',
        //     'email' => 'academy@iagroups.com',
        //     'telefono' => '64700293'
        // ],
    ];


    public $departamentosAgrupados = [
        'amazonica' => [
            'label' => 'Zona Amazónica',
            'color' => 'text-yellow-300',
            'costo' => '$18.18 USD',
            'departamentos' => [
                ['value' => 'beni', 'nombre' => 'Beni'],
                ['value' => 'pando', 'nombre' => 'Pando'],
            ],
        ],
        'central' => [
            'label' => 'Eje Central',
            'color' => 'text-blue-300',
            'costo' => '$18.18 USD',
            'departamentos' => [
                ['value' => 'cochabamba', 'nombre' => 'Cochabamba'],
                ['value' => 'santa_cruz', 'nombre' => 'Santa Cruz'],
            ],
        ],
        'sur' => [
            'label' => 'Zona Sur',
            'color' => 'text-green-300',
            'costo' => '$18.18 USD',
            'departamentos' => [
                ['value' => 'chuquisaca', 'nombre' => 'Chuquisaca'],
                ['value' => 'potosi', 'nombre' => 'Potosí'],
                ['value' => 'oruro', 'nombre' => 'Oruro'],
                ['value' => 'tarija', 'nombre' => 'Tarija'],
            ],
        ],
    ];

    protected $listeners = [
        'fcl-rates-ready' => 'handleRatesReady',
        'fcl-heartbeat'   => 'handleHeartbeat',
        'fcl-error'       => 'handleError',
    ];
    public function updatedDestinoFinal($value)
    {
        if ($value === true || $value === 'otros') {
            $this->destinoFinal = 'otros';
        } else {
            $this->destinoFinal = 'la_paz';
            $this->departamentoDestino = '';
        }
        $this->calcular();
    }

    public function updatedPeso()
    {
        $this->calcular();
    }
    public function updatedVolumen()
    {
        $this->calcular();
    }
    public function updatedValorMercancia()
    {
        $this->calcular();
    }
    public function updatedCantidad()
    {
        $this->calcular();
    }
    public function updatedLargo()
    {
        $this->calcular();
    }
    public function updatedAncho()
    {
        $this->calcular();
    }
    public function updatedAlto()
    {
        $this->calcular();
    }
    public function updatedDepartamentoDestino()
    {
        $this->calcular();
    }
    public function updatedRecojoAlmacen()
    {
        $this->calcular();
    }
    public function updatedVerificacionProducto()
    {
        $this->calcular();
    }
    public function updatedVerificacionCalidad()
    {
        $this->calcular();
    }
    public function updatedVerificacionEmpresaDigital()
    {
        $this->calcular();
    }
    public function updatedVerificacionEmpresaPresencial()
    {
        $this->calcular();
    }
    public function selectRate($rate, $container)
    {
        $this->validate([
            'clienteNombre' => 'required|string|min:3',
            'clienteCiudad' => 'required|not_in:0',
            'clienteDireccion' => 'required|string|min:5',
            'clienteEmail' => 'required|email',
            'clienteTelefono' => 'required|string|min:5',
        ], [
            'clienteNombre.required' => 'El nombre del cliente es obligatorio.',
            'clienteCiudad.required' => 'Debe seleccionar una ciudad.',
            'clienteCiudad.not_in' => 'Debe seleccionar una ciudad.',
            'clienteDireccion.required' => 'La dirección es obligatoria.',
            'clienteEmail.required' => 'El email es obligatorio.',
            'clienteEmail.email' => 'El formato del email no es válido.',
            'clienteTelefono.required' => 'El teléfono es obligatorio.',
        ]);

        $this->mostrarPregunta = false;
        $this->respuestaUsuario = null;

        $this->selectedRate = $rate;
        $this->selectedContainer = $container;

         $nuevoCliente = Cliente::create([
            'clienteNombre'    => $this->clienteNombre,
            'clienteEmail'     => $this->clienteEmail,
            'clienteTelefono'  => $this->clienteTelefono,
            'clienteDireccion' => $this->clienteDireccion,
            'clienteCiudad'    => $this->clienteCiudad,
        ]);

        $containerName = match ($container) {
            'gp20' => "20' Standard",
            'gp40' => "40' Standard",
            'hq40' => "40' High Cube",
            default => "Contenedor",
        };

        $precioBase = $rate[$container] ?? 0;

        if ($precioBase <= 0) {
            session()->flash('error', 'Precio no disponible para este contenedor.');
            return;
        }

        $shippingLine = $rate['shipping_line'] ?? 'Desconocida';

        $gastosLocales = [
            '   ├─ Manipulación en Terminal' => 159.00,
            '   ├─ Gastos de Documentación' => 72.00,
            '   ├─ Gastos Portuarios Varios' => 102.00,
            '   ├─ Control de Equipo (EIR)' => 22.00,
            '   ├─ Sello de Seguridad' => 10.00,
            '   ├─ Despacho de Aduana' => 40.00,
            '   └─ Gasto por Liberación Digital' => 65.00
        ];

        $subtotalPortuaria = array_sum($gastosLocales);

        $valorMercancia = floatval($this->valorMercancia);
        if ($valorMercancia > 0) {
            $this->desglose['Valor de la Mercancía'] = number_format($valorMercancia, 2, '.', '');
        }

        $this->desglose = [
            "Flete Marítimo ({$shippingLine} - {$containerName})" => $precioBase,
            '─ GESTIÓN PORTUARIA Y LOGÍSTICA' => null,
        ];

        foreach ($gastosLocales as $concepto => $monto) {
            $this->desglose[$concepto] = number_format($monto, 2);
        }
        $this->desglose['   Valor de la Mercancía'] = number_format($valorMercancia, 2, '.', '');
        $this->desglose['   Gestión Portuaria'] = number_format($subtotalPortuaria, 2);
        $this->desglose['   Booking y Cupo de Carga'] = number_format($precioBase * 0.05, 2);

        $this->desglose['Tiempo de Tránsito'] = ($rate['transit_time'] ?? 'N/A') . ' días';
        $this->desglose['Válido hasta'] = isset($rate['valid_until'])
            ? \Carbon\Carbon::parse($rate['valid_until'])->format('d/m/Y')
            : 'No especificado';
        $this->desglose['Cierre (cutoff)'] = ($rate['closing'] ?? 'N/A') . ' días';

        $this->resultado = $precioBase + $subtotalPortuaria + ($precioBase * 0.05) + $valorMercancia;

        $this->desglose_reporte = [
            'ref' => $this->id_producto ?: 'FCL-' . strtoupper($container),
            'descripcion' => "Flete Marítimo ({$shippingLine} - {$containerName})",
            'cantidad' => 1,
            'unidad' => 'PCS',
            'precio' => $precioBase,
            'total' => $precioBase,
            'imagen' => $this->imagen ?: null,
            'valorMercancia' => $valorMercancia,
            'gestionPortuaria' => $subtotalPortuaria,
            'booking' => $precioBase * 0.05
        ];

        $this->tipoCobroActual = 'Contenedor Completo (FCL)';
        $this->unidadActual = "Contenedor " . $containerName;
        $this->valorFacturadoActual = $precioBase;
        $this->cbmFacturadoActual = $containerName;

        $this->mostrarPregunta = true;
        $this->mostrarDesglose = true;
        $this->respuestaUsuario = null;

        $this->dispatch('scroll-to-result');

        session()->flash('success', 'Cotización generada correctamente.');
    }
    function calculate_tiered_charge(float $evaluation_value): float
    {
        $rates = [
            [999.00, 65.00, 1],
            [1999.00, 94.50, 1],
            [3999.00, 125.90, 1],
            [5000.00, 145.00, 1],
            [10000.00, 0.03, 2],
            [20000.00, 0.025, 2],
            [30000.00, 0.0225, 2],
            [50000.00, 0.02, 2],
            [100000.00, 0.0175, 2]
        ];

        $charge = 0.0;

        foreach ($rates as $tier) {
            $maximum = $tier[0];
            $rate = $tier[1];
            $type = $tier[2];

            if ($evaluation_value <= $maximum) {

                if ($type === 1) {
                    $charge = $rate;
                } else {
                    $charge = $evaluation_value * $rate;
                }
                return $charge;
            }
        }

        if ($evaluation_value > 100000.00) {
            return $evaluation_value * 0.015;
        }
        return 0.0;
    }
    public function handleRatesReady($data)
    {
        $this->rates = $data;
        $this->fclRates = $data['rates'] ?? [];
        $this->loadingRates = false;
        $this->message = 'Tarifas cargadas · ' . ($data['totalRatesFound'] ?? 0) . ' encontradas';
    }

    public function handleHeartbeat()
    {
        $this->message = 'Buscando tarifas en tiempo real...';
    }

    public function handleError()
    {
        $this->loadingRates = false;
        $this->message = 'Error o tiempo agotado';
    }

    public function getCostoRecojoProperty(): float
    {
        return $this->recojoAlmacen ? 26.91 : 0.0;
    }

    private function getMapaCostosDestino(): array
    {
        return [

            'beni' => ['costo' => 18.18, 'nombre' => 'Beni'],
            'pando' => ['costo' => 18.18, 'nombre' => 'Pando'],

            'cochabamba' => ['costo' => 18.18, 'nombre' => 'Cochabamba'],
            'santa_cruz' => ['costo' => 18.18, 'nombre' => 'Santa Cruz'],

            'chuquisaca' => ['costo' => 18.18, 'nombre' => 'Chuquisaca'],
            'potosi' => ['costo' => 18.18, 'nombre' => 'Potosí'],
            'oruro' => ['costo' => 18.18, 'nombre' => 'Oruro'],
            'tarija' => ['costo' => 18.18, 'nombre' => 'Tarija'],
        ];
    }

    public function calcularCostoDestino(): array
    {
        if ($this->destinoFinal !== 'otros' || empty($this->departamentoDestino)) {
            return ['costo' => 0.0, 'nombre' => ''];
        }

        $departamentos = $this->getMapaCostosDestino();
        $departamentoSeleccionado = $this->departamentoDestino;

        if (isset($departamentos[$departamentoSeleccionado])) {
            return $departamentos[$departamentoSeleccionado];
        }

        return ['costo' => 0.0, 'nombre' => ''];
    }

    // =======================================================
    //              CALCULAR COTIZACIÓN PRINCIPAL
    // =======================================================
    public function calcular($isFinal = true)
    {
    if ($isFinal) {
        $this->validate([
            'peso' => 'nullable|numeric|min:0',
            'volumen' => 'nullable|numeric|min:0.000001',
            'valorMercancia' => 'required|numeric|min:0',
            'clienteNombre' => 'required|string|min:3',
            'clienteCiudad' => 'required|not_in:0',
            'clienteDireccion' => 'required|string|min:5',
            'clienteEmail' => 'required|email',
            'clienteTelefono' => 'required|string|min:7',
        ], [
            'clienteNombre.required' => 'El nombre del cliente es obligatorio.',
            'clienteCiudad.required' => 'Debe seleccionar una ciudad.',
            'clienteCiudad.not_in' => 'Debe seleccionar una ciudad.',
            'clienteDireccion.required' => 'La dirección es obligatoria.',
            'clienteEmail.required' => 'El email es obligatorio.',
            'clienteEmail.email' => 'El formato del email no es válido.',
            'clienteTelefono.required' => 'El teléfono es obligatorio.',
        ]);
    }

        $this->mostrarPregunta = false;
        $this->respuestaUsuario = null;

        $this->fecha = now()->toDateTimeString();

        $peso = floatval($this->peso);
        $largo = floatval($this->largo);
        $ancho = floatval($this->ancho);
        $alto = floatval($this->alto);
        $volumen = floatval($this->volumen);

        $volumetricWeight = $CBM = 0;

        if (!empty($largo) && !empty($ancho) && !empty($alto)) {
            $volumetricWeight = ($largo * $ancho * $alto) / 5000;
            $CBM = ($largo * $ancho * $alto) / 1000000;
        } else {
            $volumetricWeight = $volumen ?? 0;
            $CBM = $this->volumen ?? 0;
        }

        if (($this->peso > 0) && (empty($largo) || empty($ancho) || empty($alto)) && $CBM <= 0) {
            $CBM_estimado = $this->peso / 300;
            $shippingPackage = $this->calculateShippingPackage($this->peso, $CBM_estimado);
        } elseif (empty($this->peso) || $this->peso <= 0) {
            $shippingPackage = $this->calculateShippingPackagePerDimensions($CBM);
        } elseif (empty($largo) || empty($ancho) || empty($alto)) {
            $shippingPackage = $this->calculateShippingPackage($this->peso, $volumetricWeight);
        } else {
            $shippingPackage = $this->calculateShippingPackage($this->peso, $CBM);
        }

        // Calcular Gravamen Arancelario (Suma de aranceles de todos los productos)
        $totalArancel = 0;
        $iva = 0;
        foreach ($this->productos as $prod) {
            $arancelPct = isset($prod['arancel']) ? floatval($prod['arancel']) : 0;
            $flete = $prod['total_valor'] * 0.05;
            $seguro = $prod['total_valor'] * 0.02;
            $totalArancel += ($prod['total_valor'] + $flete + $seguro) * ($arancelPct / 100);
            $iva += ($prod['total_valor'] + $flete + $seguro) * (14.94 / 100);
        }

        if ($totalArancel > 0) {
            $this->desglose['Gravamen Arancelario'] = number_format($totalArancel, 2, '.', '');
            $this->gastosAdicionales['Gravamen Arancelario'] = number_format($totalArancel, 2, '.', '');
            $shippingPackage['costo'] += $totalArancel;
        }

        if ($iva > 0) {
            $this->desglose['IVA'] = number_format($iva, 2, '.', '');
            $this->gastosAdicionales['IVA'] = number_format($iva, 2, '.', '');
            $shippingPackage['costo'] += $iva;
        }

        $this->resultado = (float) $shippingPackage['costo'];
        $this->tipoCobroActual = $shippingPackage['tipo'] ?? '';
        $this->unidadActual = $shippingPackage['unidad'] ?? '';
        $this->valorFacturadoActual = $shippingPackage['valor_facturado'] ?? 0;
        $this->cbmFacturadoActual = $shippingPackage['cbm_facturado'] ?? 0;

        $this->mostrarPregunta = true;

        if ($this->manualImagen) {
            $this->imagen = $this->manualImagen->temporaryUrl();
        }
        if ($this->tipoCarga === 'lcl') {
            $this->desglose_reporte = [
                'ref' => $this->id_producto ?: 'MANUAL',
                'descripcion' => $this->producto ?: 'Producto sin nombre',
                'cantidad' => $this->cantidad ?: 1,
                'unidad' => 'PCS',
                'precio' => $this->valorMercancia,
                'total' => $this->valorMercancia * ($this->cantidad ?: 1),
                'imagen' => $this->imagen
            ];
        }
        if ($isFinal) {
             $nuevoCliente = Cliente::create([
                'clienteNombre'    => $this->clienteNombre,
                'clienteEmail'     => $this->clienteEmail,
                'clienteTelefono'  => $this->clienteTelefono,
                'clienteDireccion' => $this->clienteDireccion,
                'clienteCiudad'    => $this->clienteCiudad,
            ]);
        }

        session()->flash('success', 'Cálculo completado exitosamente.');
    }

    // =======================================================
    //                    CÁLCULO LCL
    // =======================================================
    /**
     * Cálculo para LCL (Less than Container Load)
     * Fórmula básica: tarifa base + (peso * tarifa por kg)
     */
    private function calculateShippingPackage(float $pesoKg, float $cbmReal)
    {
        $TARIFA_POR_KG = [
            1  => 10,
            2 => 9.5,
            3 => 9,
            4 => 8.5,
            5  => 8,
            6 => 7.5,
            7 => 7,
            8 => 6.5,
            9  => 6,
            10 => 5.5,
            11 => 5,
            12 => 4.5,
            13 => 4,
            14 => 3.5,
            15 => 3,
            16 => 2.5
        ];

        $TARIFA_POR_CBM = [
            ['min' => 20,   'precio' => 129],
            ['min' => 15,   'precio' => 138],
            ['min' => 11,   'precio' => 149],
            ['min' => 8,    'precio' => 159],
            ['min' => 5,    'precio' => 168],
            ['min' => 3,    'precio' => 179],
            ['min' => 1,    'precio' => 188],
            ['min' => 0.5,  'precio' => 116],
            ['min' => 0.25, 'precio' => 60]
        ];

        $costoFinal = 0.0;
        $tipoCobro  = '';
        $valorUsado = 0.0;

        if ($cbmReal === null || $cbmReal < 0.1) {
            $tipoCobro  = 'Peso (W/M)';
            $valorUsado = ceil($pesoKg);

            if ($valorUsado >= 1 && $valorUsado <= 16 && isset($TARIFA_POR_KG[$valorUsado])) {
                $costoFinal = $TARIFA_POR_KG[$valorUsado];
            } else {
                $costoFinal = 2.5;
            }
        } else {
            $tipoCobro  = 'CBM';
            $valorUsado = $cbmReal;

            // Ordenamos ascendente para aplicar lógica "Up To" (Hasta X CBM)
            // Array original es descendente (20 -> 0.25). Reverse = (0.25 -> 20).
            $tarifasAsc = array_reverse($TARIFA_POR_CBM);
            
            // Valor por defecto: el precio del tramo más alto (para > 20 CBM)
            // El primer elemento original [0] es el de 20 CBM ($129).
            $costoFinal = $TARIFA_POR_CBM[0]['precio']; 

            foreach ($tarifasAsc as $tramo) {
                if ($cbmReal <= $tramo['min']) {
                    $costoFinal = $tramo['precio'];
                    break;
                }
            }
        }

        $costoRecojo = $this->costoRecojo;
        $resultadoDestino = $this->calcularCostoDestino();
        $costoDestino = $resultadoDestino['costo'];
        $nombreDestino = $resultadoDestino['nombre'];

        $valorFacturado = 0;
        $unidad = str_contains($tipoCobro, 'Peso') ? 'kg' : 'm³';
        if ($unidad == 'kg') {
            $valorFacturado = $costoFinal * $this->peso;
        } else {
            $valorFacturado = $costoFinal;
        }
        $costo_envio_interno = 15;
        $despacho = 0;
        if ($this->volumenTotal <= 0.25) {
            $despacho = 8.43;
        }elseif ($this->volumenTotal <= 0.5){
            $despacho = 16.85;
        }else{
            $despacho = $this->volumenTotal * 33.70;
        }
        
        $total_tiered_charge = $this->calculate_tiered_charge($this->valorMercancia);

        // =====================================================
        // NUEVO: DESGLOSE DETALLADO DEL FLETE MARÍTIMO POR CBM
        // =====================================================
        $desgloseFleteMaritimo = [];

        if ($tipoCobro === 'CBM') {
            $distribucion = [
                'grupo1' => 0.60,
                'grupo2' => 0.25,
                'grupo3' => 0.15,
            ];

            $grupo1 = $costoFinal * $distribucion['grupo1'];
            $grupo2 = $costoFinal * $distribucion['grupo2'];
            $grupo3 = $costoFinal * $distribucion['grupo3'];

            $desgloseFleteMaritimo['─ TRAMO INTERNACIONAL (MARÍTIMO)'] = null;
            $desgloseFleteMaritimo['   ├─ Flete Puerto a Puerto (Ningbo - Iquique)'] = number_format($grupo1 * 0.85, 2); // Aumentamos flete
            $desgloseFleteMaritimo['   ├─ Seguro y Garantía de Espacio'] = number_format($grupo1 * 0.15, 2); // Suena a "asegurar espacio"
            $desgloseFleteMaritimo['   Subtotal Tramo Internacional (Marítimo)'] = number_format($grupo1, 2);

            $desgloseFleteMaritimo['─ OPERACIÓN EN ORIGEN (CHINA)'] = null;
            $desgloseFleteMaritimo['   ├─ Logística Interna (Shenzhen - Yiwu - Ningbo)'] = number_format($grupo2 * 0.80, 2);
            $desgloseFleteMaritimo['   ├─ Gastos Portuarios y Documentación (BL)'] = number_format($grupo2 * 0.20, 2);
            $desgloseFleteMaritimo['   Subtotal Operación en Origen (China)'] = number_format($grupo2, 2);

            $desgloseFleteMaritimo['─ TRAMO FINAL Y ENTREGA (BOLIVIA)'] = null;
            $desgloseFleteMaritimo['   ├─ Flete Terrestre (Iquique - Destino Bolivia)'] = number_format($grupo3 * 0.75, 2); // En lugar de "comisión"
            $desgloseFleteMaritimo['   ├─ Maniobras y Despacho de Tránsito'] = number_format($grupo3 * 0.25, 2); // Suena a protección financiera
            $desgloseFleteMaritimo['   Subtotal Tramo Final y Entrega (Bolivia)'] = number_format($grupo3, 2);
        } else {
            $distribucion = [
                'grupo1' => 0.60,
                'grupo2' => 0.25,
                'grupo3' => 0.15,
            ];

            $grupo1 = $valorFacturado * $distribucion['grupo1'];
            $grupo2 = $valorFacturado * $distribucion['grupo2'];
            $grupo3 = $valorFacturado * $distribucion['grupo3'];

            $desgloseFleteMaritimo['─ TRAMO INTERNACIONAL (MARÍTIMO)'] = null;
            $desgloseFleteMaritimo['   ├─ Flete Puerto a Puerto (Ningbo - Iquique)'] = number_format($grupo1 * 0.85, 2);
            $desgloseFleteMaritimo['   ├─ Seguro y Garantía de Espacio'] = number_format($grupo1 * 0.15, 2);
            $desgloseFleteMaritimo['   Subtotal Tramo Internacional (Marítimo)'] = number_format($grupo1, 2);

            $desgloseFleteMaritimo['─ OPERACIÓN EN ORIGEN (CHINA)'] = null;
            $desgloseFleteMaritimo['   ├─ Logística Interna (Shenzhen - Yiwu - Ningbo)'] = number_format($grupo2 * 0.80, 2);
            $desgloseFleteMaritimo['   ├─ Gastos Portuarios y Documentación (BL)'] = number_format($grupo2 * 0.20, 2);
            $desgloseFleteMaritimo['   Subtotal Operación en Origen (China)'] = number_format($grupo2, 2);


            $desgloseFleteMaritimo['─ TRAMO FINAL Y ENTREGA (BOLIVIA)'] = null;
            $desgloseFleteMaritimo['   ├─ Flete Terrestre (Iquique - Destino Bolivia)'] = number_format($grupo3 * 0.75, 2);
            $desgloseFleteMaritimo['   ├─ Maniobras y Despacho de Tránsito'] = number_format($grupo3 * 0.25, 2);
            $desgloseFleteMaritimo['   Subtotal Tramo Final y Entrega (Bolivia)'] = number_format($grupo3, 2);
        }

        // =====================================================
        // CONSTRUCCIÓN FINAL DEL DESGLOSE
        // =====================================================
        $this->desglose = [
            'Valor de Mercancía' => number_format($this->valorMercancia, 2, '.', ''),
            'Costo de Envío de Paquete' => number_format($valorFacturado, 2, '.', ''),
            'Costo de Envío Interno' => number_format($costo_envio_interno, 2, '.', ''),
            'Despacho de importacion' => number_format($despacho, 2, '.', ''),
            'Agencia despachante' => number_format($total_tiered_charge, 2, '.', ''),
        ];
        $this->desglose = array_merge($this->desglose, $desgloseFleteMaritimo);

        $addServices = 0;
        if ($this->recojoAlmacen) {
            $this->desglose['Recojo desde Almacén'] = number_format($costoRecojo * $this->volumenTotal, 2, '.', '');
            $addServices = (float) number_format($costoRecojo * $this->volumenTotal, 2, '.', '');
        }

        $costoVerificacion = 0;
        if ($this->verificacionProducto) {
            $costoVerificacion = $this->calculateVerificationCost();
            $this->desglose['Verificación del Producto'] = number_format($costoVerificacion, 2, '.', '');
        }
        if ($this->verificacionCalidad) {
            $this->desglose['Verificación de Calidad del Producto'] = 50.00;
            $costoVerificacion += 50.00;
        }
        if ($this->verificacionEmpresaDigital) {
            $this->desglose['Verificación de Empresa Digital'] = 100.00;
            $costoVerificacion += 100.00;
        }
        if ($this->verificacionEmpresaPresencial) {
            $this->desglose['Verificación Presencial de Empresa'] = 350.00;
            $costoVerificacion += 350.00;
        }

        if ($costoDestino > 0 && $nombreDestino) {
            $this->desglose["Entrega a " . $nombreDestino] = number_format($costoDestino, 2, '.', '');
        }

        $this->gastosAdicionales = [];
        if ($this->recojoAlmacen) {
            $this->gastosAdicionales['Recojo desde Almacén'] = number_format($costoRecojo * $valorUsado, 2, '.', '');
        }

        if ($costoDestino > 0 && $nombreDestino) {
            $this->gastosAdicionales["Entrega a " . $nombreDestino] = number_format($costoDestino, 2, '.', '');
        }

        $this->gastosAdicionales['Costo de Envío Interno'] = number_format($costo_envio_interno, 2, '.', '');
        $this->gastosAdicionales['Despacho de importacion'] = number_format($despacho, 2, '.', '');
        $this->gastosAdicionales['Agencia despachante'] = number_format($total_tiered_charge, 2, '.', '');
        if ($this->verificacionProducto) $this->gastosAdicionales['Verificación de Producto'] = number_format($this->calculateVerificationCost(), 2, '.', '');
        if ($this->verificacionCalidad) $this->gastosAdicionales['Verificación de Calidad'] = 50.00;
        if ($this->verificacionEmpresaDigital) $this->gastosAdicionales['Verificación de Empresa Digital'] = 100.00;
        if ($this->verificacionEmpresaPresencial) $this->gastosAdicionales['Verificación Presencial de Empresa'] = 350.00;

        $total = $this->valorMercancia + $valorFacturado + $addServices + $costoDestino + $costoVerificacion + $total_tiered_charge + $despacho + $costo_envio_interno;
        return [
            'costo'  => number_format($total, 2, '.', ''),
            'tipo'   => $tipoCobro,
            'unidad' => $unidad,
            'valor_facturado' => $valorFacturado,
            'cbm_facturado' => $tipoCobro === 'CBM' ? $valorUsado : null,
        ];
    }

    /**
     * Calcula el costo del envío SOLO por CBM (volumen real)
     * Ideal para LCL cuando ya tienes el volumen en m³
     */
    private function calculateShippingPackagePerDimensions(float $cbmReal): array
    {
        $TARIFA_POR_CBM = [
            ['min' => 20,   'precio' => 129],
            ['min' => 15,   'precio' => 138],
            ['min' => 11,   'precio' => 149],
            ['min' => 8,    'precio' => 159],
            ['min' => 5,    'precio' => 168],
            ['min' => 3,    'precio' => 179],
            ['min' => 1,    'precio' => 188],
            ['min' => 0.5,  'precio' => 116],
            ['min' => 0.25, 'precio' => 60]
        ];


        $cbmFacturable = max($cbmReal, 0.25);


        $precioPorCbm = 60;
        $cbmAplicado = 0.25;

        foreach ($TARIFA_POR_CBM as $tramo) {
            if ($cbmFacturable >= $tramo['min']) {
                $precioPorCbm = $tramo['precio'];
                $cbmAplicado = $tramo['min'];
                break;
            }
        }

        $costoFleteTotal = $precioPorCbm * $this->cantidad;

        // =====================================================
        // DESGLOSE DETALLADO DEL FLETE MARÍTIMO PORCENTUAL
        // =====================================================
        $desgloseFleteMaritimo = [];


        $distribucion = [
            'grupo1' => 0.60,
            'grupo2' => 0.25,
            'grupo3' => 0.15,
        ];

        $grupo1 = $precioPorCbm * $distribucion['grupo1'];
        $grupo2 = $precioPorCbm * $distribucion['grupo2'];
        $grupo3 = $precioPorCbm * $distribucion['grupo3'];


        $desgloseFleteMaritimo['─ Grupo 1: Costos Naviera y Documentación'] = null;
        $desgloseFleteMaritimo['   ├─ Flete Marítimo Principal (Naviera)'] = number_format($grupo1 * 0.75, 2);
        $desgloseFleteMaritimo['   ├─ MBL y Documentación Oficial'] = number_format($grupo1 * 0.15, 2);
        $desgloseFleteMaritimo['   └─ Seguro de Flete Básico'] = number_format($grupo1 * 0.10, 2);
        $desgloseFleteMaritimo['   Subtotal Grupo 1'] = number_format($grupo1, 2);

        $desgloseFleteMaritimo['─ Grupo 2: Gastos Operativos en Origen'] = null;
        $desgloseFleteMaritimo['   ├─ Handling, Gate In y THC'] = number_format($grupo2 * 0.40, 2);
        $desgloseFleteMaritimo['   ├─ Comisión Giro China'] = number_format($grupo2 * 0.30, 2);
        $desgloseFleteMaritimo['   └─ BL Fee y Otros Operativos'] = number_format($grupo2 * 0.30, 2);
        $desgloseFleteMaritimo['   Subtotal Grupo 2'] = number_format($grupo2, 2);

        $desgloseFleteMaritimo['─ Grupo 3: Margen y Comisiones'] = null;
        $desgloseFleteMaritimo['   ├─ Comisión Logística / Margen'] = number_format($grupo3 * 0.50, 2);
        $desgloseFleteMaritimo['   ├─ Comisiones Bancarias'] = number_format($grupo3 * 0.20, 2);
        $desgloseFleteMaritimo['   ├─ Flete Interno Adicional'] = number_format($grupo3 * 0.20, 2);
        $desgloseFleteMaritimo['   └─ Seguro Complementario'] = number_format($grupo3 * 0.10, 2);
        $desgloseFleteMaritimo['   Subtotal Grupo 3'] = number_format($grupo3, 2);

        // =====================================================
        // CONSTRUCCIÓN DEL DESGLOSE GENERAL
        // =====================================================
        $this->desglose = [
            'Valor de Mercancía' => number_format($this->valorMercancia, 2, '.', ''),
            'Costo de Envío de Paquete' => number_format($costoFleteTotal, 2, '.', ''),
        ];


        $this->desglose = array_merge($this->desglose, $desgloseFleteMaritimo);

        $costoRecojo = $this->costoRecojo;
        $resultadoDestino = $this->calcularCostoDestino();
        $costoDestino = $resultadoDestino['costo'];
        $nombreDestino = $resultadoDestino['nombre'];

        $additional_services = 0;
        if ($this->recojoAlmacen) {
            $this->desglose['Recojo desde Almacén'] = number_format($costoRecojo * $this->volumenTotal, 2, '.', ''); // ajustado por cantidad
            $additional_services = $costoRecojo * $this->volumenTotal;
        }

        if ($costoDestino > 0 && $nombreDestino) {
            $this->desglose["Entrega a " . $nombreDestino] = number_format($costoDestino, 2, '.', '');
        }

        // =====================================================
        // NUEVO: SEPARACIÓN DE GASTOS ADICIONALES
        // =====================================================
        $this->gastosAdicionales = [];
        if ($this->recojoAlmacen) {
            $this->gastosAdicionales['Recojo desde Almacén'] = number_format($costoRecojo * $this->volumenTotal, 2, '.', '');
        }
        if ($costoDestino > 0 && $nombreDestino) {
            $this->gastosAdicionales["Entrega a " . $nombreDestino] = number_format($costoDestino, 2, '.', '');
        }
        if ($this->verificacionProducto) $this->gastosAdicionales['Verificación de Producto'] = number_format($this->calculateVerificationCost(), 2, '.', '');
        if ($this->verificacionCalidad) $this->gastosAdicionales['Verificación de Calidad'] = 50.00;
        if ($this->verificacionEmpresaDigital) $this->gastosAdicionales['Verificación de Empresa Digital'] = 100.00;
        if ($this->verificacionEmpresaPresencial) $this->gastosAdicionales['Verificación Presencial de Empresa'] = 350.00;

        $costo_envio_interno = 15;
        $despacho = 0;
        if ($this->volumenTotal <= 0.25) {
            $despacho = 8.43;
        } elseif ($this->volumenTotal <= 0.5) {
            $despacho = 16.85;
        } else {
            $despacho = $this->volumenTotal * 33.70;
        }

        $total_tiered_charge = $this->calculate_tiered_charge($this->valorMercancia);

        $this->desglose['Costo de Envío Interno'] = number_format($costo_envio_interno, 2, '.', '');
        $this->desglose['Despacho de importacion'] = number_format($despacho, 2, '.', '');
        $this->desglose['Agencia despachante'] = number_format($total_tiered_charge, 2, '.', '');

        $this->gastosAdicionales['Costo de Envío Interno'] = number_format($costo_envio_interno, 2, '.', '');
        $this->gastosAdicionales['Despacho de importacion'] = number_format($despacho, 2, '.', '');
        $this->gastosAdicionales['Agencia despachante'] = number_format($total_tiered_charge, 2, '.', '');

        $total = $this->valorMercancia + $costoFleteTotal + $additional_services + $costoDestino + $costo_envio_interno + $despacho + $total_tiered_charge;

        return [
            'costo' => number_format($total, 2, '.', ''),
            'tipo'  => 'CBM',
            'unidad' => 'm³',
            'valor_facturado' => $costoFleteTotal,
            'cbm_facturado' => $cbmAplicado,
            'detalle' => [
                'cbm_real' => $cbmReal,
                'cbm_facturado' => $cbmAplicado,
                'precio_por_unidad' => $precioPorCbm,
                'cantidad' => $this->cantidad,
                'flete_total' => $costoFleteTotal
            ]
        ];
    }

    public function responder($respuesta)
    {
        $this->respuestaUsuario = $respuesta;
    }
    public function descargarPDF()
    {

        $origenFinal = $this->tipoCarga === 'fcl' ? $this->searchPOL : $this->origen;
        $destinoFinal = $this->tipoCarga === 'fcl' ? $this->searchPOD : $this->destino;

        $agenteSeleccionado = collect($this->agentes)->firstWhere('id', $this->agenteId);

        return redirect()->route('cotizacion.pdf', [
            'tipoCarga' => $this->tipoCarga,
            'peso' => $this->peso,
            'volumen' => $this->volumen,
            'largo' => $this->largo,
            'ancho' => $this->ancho,
            'alto' => $this->alto,
            'cantidad' => $this->cantidad,
            'origen' => $origenFinal,
            'destino' => $destinoFinal,
            'valorMercancia' => $this->valorMercancia,
            'resultado' => $this->resultado,
            'desglose' => json_encode($this->desglose),
            'tipoCobro' => $this->tipoCobroActual,
            'unidad' => $this->unidadActual,
            'valorFacturado' => $this->valorFacturadoActual,
            'cbmFacturado' => $this->cbmFacturadoActual,
            'desglose_reporte' => json_encode($this->desglose_reporte),
            'gastosAdicionales' => json_encode($this->gastosAdicionales),

            'clienteNombre' => $this->clienteNombre,
            'clienteEmail' => $this->clienteEmail,
            'clienteTelefono' => $this->clienteTelefono,
            'clienteDireccion' => $this->clienteDireccion,
            'clienteCiudad' => $this->clienteCiudad,
            'agente' => json_encode($agenteSeleccionado),
            'productos' => json_encode($this->productos),
        ]);
    }
    
    
    // =======================================================
    //              MÉTODOS FCL - BÚSQUEDA DE PUERTOS
    // =======================================================

    /**
     * Método público para buscar puertos POL (llamado desde Alpine.js)
     */
    public function searchPOLPorts($value)
    {
        $this->searchPOL = $value;
        $this->updatedSearchPOL($value);
    }

    /**
     * Método público para buscar puertos POD (llamado desde Alpine.js)
     */
    public function searchPODPorts($value)
    {
        $this->searchPOD = $value;
        $this->updatedSearchPOD($value);
    }

    /**
     * Buscar puertos de origen (POL) mientras el usuario escribe
     */
    public function updatedSearchPOL($value)
    {

        if ($this->polCode && str_starts_with($value, $this->polCode . ' - ')) {
            return;
        }


        if ($this->polCode && strlen($value) < 5) {
            $this->polCode = '';
        }

        if (strlen($value) < 2) {
            $this->polSuggestions = [];
            $this->showPOLDropdown = false;
            return;
        }

        $this->polSuggestions = $this->searchPortsPOL($value);
        $this->showPOLDropdown = count($this->polSuggestions) > 0;
    }

    /**
     * Buscar puertos de destino (POD) mientras el usuario escribe
     */
    public function updatedSearchPOD($value)
    {

        if ($this->podCode && str_starts_with($value, $this->podCode . ' - ')) {
            return;
        }

        if ($this->podCode && strlen($value) < 5) {
            $this->podCode = '';
        }

        if (strlen($value) < 2) {
            $this->podSuggestions = [];
            $this->showPODDropdown = false;
            return;
        }

        $this->podSuggestions = $this->searchPortsPOD($value);
        $this->showPODDropdown = count($this->podSuggestions) > 0;
    }

    /**
     * Seleccionar puerto de origen
     */
    public function selectPOL($code, $name)
    {
        $this->polCode = $code;
        $this->searchPOL = $code . ' - ' . $name;
        $this->showPOLDropdown = false;
        $this->polSuggestions = [];

        $this->js('$wire.$refresh()');
    }

    /**
     * Seleccionar puerto de destino
     */
    public function selectPOD($code, $name)
    {
        $this->podCode = $code;
        $this->searchPOD = $code . ' - ' . $name;
        $this->showPODDropdown = false;
        $this->podSuggestions = [];

        $this->js('$wire.$refresh()');
    }

    private function searchPortsPOD($query)
    {
        $ports = [
            ['code' => 'SGSGP', 'name' => 'Singapur', 'country' => 'Singapur', 'region' => 'Sudeste asiático'],
            ['code' => 'THBKK', 'name' => 'Bangkok', 'country' => 'Tailandia', 'region' => 'Sudeste asiático'],
            ['code' => 'MYPKE', 'name' => 'Puerto Klang', 'country' => 'Malasia', 'region' => 'Sudeste asiático'],
            ['code' => 'PHMAN', 'name' => 'Manila', 'country' => 'Filipinas', 'region' => 'Sudeste asiático'],
            ['code' => 'IDJAK', 'name' => 'Yakarta', 'country' => 'Indonesia', 'region' => 'Sudeste asiático'],
            ['code' => 'VNHCM', 'name' => 'Ho Chi Minh', 'country' => 'Vietnam', 'region' => 'Sudeste asiático'],
            ['code' => 'THLCB', 'name' => 'Laem Chabang', 'country' => 'Tailandia', 'region' => 'Sudeste asiático'],
            ['code' => 'khkos', 'name' => 'Sihanoukville', 'country' => 'Camboya', 'region' => 'Sudeste asiático'],
            ['code' => 'JPTOK', 'name' => 'Tokio', 'country' => 'Japón', 'region' => 'Este asiático'],
            ['code' => 'JPOSK', 'name' => 'Osaka', 'country' => 'Japón', 'region' => 'Este asiático'],
            ['code' => 'JPYOK', 'name' => 'Yokohama', 'country' => 'Japón', 'region' => 'Este asiático'],
            ['code' => 'JPKOB', 'name' => 'Kobe', 'country' => 'Japón', 'region' => 'Este asiático'],
            ['code' => 'KRBUS', 'name' => 'Busan', 'country' => 'Corea del Sur', 'region' => 'Este asiático'],
            ['code' => 'KRINC', 'name' => 'Inchon', 'country' => 'Corea del Sur', 'region' => 'Este asiático'],
            ['code' => 'TWKAO', 'name' => 'Kaohsiung', 'country' => 'Taiwán', 'region' => 'Este asiático'],
            ['code' => 'TWKEE', 'name' => 'Keelung', 'country' => 'Taiwán', 'region' => 'Este asiático'],
            ['code' => 'AUSYD', 'name' => 'Sídney', 'country' => 'Australia', 'region' => 'Oceanía'],
            ['code' => 'AUMEL', 'name' => 'Melbourne', 'country' => 'Australia', 'region' => 'Oceanía'],
            ['code' => 'AUBRI', 'name' => 'Brisbane', 'country' => 'Australia', 'region' => 'Oceanía'],
            ['code' => 'AUFRE', 'name' => 'Fremantle', 'country' => 'Australia', 'region' => 'Oceanía'],
            ['code' => 'AUADE', 'name' => 'Adelaida', 'country' => 'Australia', 'region' => 'Oceanía'],
            ['code' => 'NZAUC', 'name' => 'Auckland', 'country' => 'Nueva Zelanda', 'region' => 'Oceanía'],
            ['code' => 'NZLYT', 'name' => 'Lyttelton', 'country' => 'Nueva Zelanda', 'region' => 'Oceanía'],
            ['code' => 'NZTAU', 'name' => 'Tauranga', 'country' => 'Nueva Zelanda', 'region' => 'Oceanía'],
            ['code' => 'USLGB', 'name' => 'Long Beach', 'country' => 'Estados Unidos', 'region' => 'Norteamérica'],
            ['code' => 'USLSA', 'name' => 'Los Ángeles', 'country' => 'Estados Unidos', 'region' => 'Norteamérica'],
            ['code' => 'USNYK', 'name' => 'Nueva York', 'country' => 'Estados Unidos', 'region' => 'Norteamérica'],
            ['code' => 'USSEA', 'name' => 'Seattle', 'country' => 'Estados Unidos', 'region' => 'Norteamérica'],
            ['code' => 'USOAK', 'name' => 'Oakland', 'country' => 'Estados Unidos', 'region' => 'Norteamérica'],
            ['code' => 'USTAC', 'name' => 'Tacoma', 'country' => 'Estados Unidos', 'region' => 'Norteamérica'],
            ['code' => 'USNFK', 'name' => 'Norfolk', 'country' => 'Estados Unidos', 'region' => 'Norteamérica'],
            ['code' => 'USSAV', 'name' => 'Savannah', 'country' => 'Estados Unidos', 'region' => 'Norteamérica'],
            ['code' => 'USMIA', 'name' => 'Miami', 'country' => 'Estados Unidos', 'region' => 'Norteamérica'],
            ['code' => 'USCHA', 'name' => 'Charleston', 'country' => 'Estados Unidos', 'region' => 'Norteamérica'],
            ['code' => 'CAVCR', 'name' => 'Vancouver', 'country' => 'Canadá', 'region' => 'Norteamérica'],
            ['code' => 'CATOR', 'name' => 'Toronto', 'country' => 'Canadá', 'region' => 'Norteamérica'],
            ['code' => 'CAMTL', 'name' => 'Montreal', 'country' => 'Canadá', 'region' => 'Norteamérica'],
            ['code' => 'GBFEL', 'name' => 'Felixstowe', 'country' => 'Reino Unido', 'region' => 'Europa'],
            ['code' => 'FRLEH', 'name' => 'Le Havre', 'country' => 'Francia', 'region' => 'Europa'],
            ['code' => 'DEHAM', 'name' => 'Hamburgo', 'country' => 'Alemania', 'region' => 'Europa'],
            ['code' => 'NLROT', 'name' => 'Róterdam', 'country' => 'Países Bajos', 'region' => 'Europa'],
            ['code' => 'BEANT', 'name' => 'Amberes', 'country' => 'Bélgica', 'region' => 'Europa'],
            ['code' => 'GBSOU', 'name' => 'Southampton', 'country' => 'Reino Unido', 'region' => 'Europa'],
            ['code' => 'BEZEE', 'name' => 'Zeebrugge', 'country' => 'Bélgica', 'region' => 'Europa'],
            ['code' => 'RULED', 'name' => 'San Petersburgo', 'country' => 'Rusia', 'region' => 'Europa'],
            ['code' => 'NOOSL', 'name' => 'Oslo', 'country' => 'Noruega', 'region' => 'Europa'],
            ['code' => 'ITGOA', 'name' => 'Génova', 'country' => 'Italia', 'region' => 'Europa'],
            ['code' => 'ITNAP', 'name' => 'Nápoles', 'country' => 'Italia', 'region' => 'Europa'],
            ['code' => 'ESBOR', 'name' => 'Barcelona', 'country' => 'España', 'region' => 'Europa'],
            ['code' => 'ESVAL', 'name' => 'Valencia', 'country' => 'España', 'region' => 'Europa'],
            ['code' => 'FRMRS', 'name' => 'Marsella', 'country' => 'Francia', 'region' => 'Europa'],
            ['code' => 'ROCON', 'name' => 'Constanza', 'country' => 'Rumania', 'region' => 'Europa'],
            ['code' => 'SIKOP', 'name' => 'Koper', 'country' => 'Eslovenia', 'region' => 'Europa'],
            ['code' => 'UAODE', 'name' => 'Odesa', 'country' => 'Ucrania', 'region' => 'Europa'],
            ['code' => 'TRIST', 'name' => 'Estambul', 'country' => 'Turquía', 'region' => 'Oriente Medio/Europa'],
            ['code' => 'HRRIJ', 'name' => 'Rijeka', 'country' => 'Croacia', 'region' => 'Europa'],
            ['code' => 'AEJAL', 'name' => 'Jebel Ali', 'country' => 'Emiratos Árabes Unidos', 'region' => 'Oriente Medio'],
            ['code' => 'BHBAH', 'name' => 'Baréin', 'country' => 'Baréin', 'region' => 'Oriente Medio'],
            ['code' => 'IRBSR', 'name' => 'Bandar Abbas', 'country' => 'Irán', 'region' => 'Oriente Medio'],
            ['code' => 'KWKUW', 'name' => 'Kuwait', 'country' => 'Kuwait', 'region' => 'Oriente Medio'],
            ['code' => 'SAJED', 'name' => 'Yeda', 'country' => 'Arabia Saudita', 'region' => 'Oriente Medio'],
            ['code' => 'SADAM', 'name' => 'Dammam', 'country' => 'Arabia Saudita', 'region' => 'Oriente Medio'],
            ['code' => 'SDPSU', 'name' => 'Puerto Sudán', 'country' => 'Sudán', 'region' => 'África'],
            ['code' => 'JOAQA', 'name' => 'Aqaba', 'country' => 'Jordania', 'region' => 'Oriente Medio'],
            ['code' => 'EGPSA', 'name' => 'Puerto Said', 'country' => 'Egipto', 'region' => 'África'],
            ['code' => 'ILHFA', 'name' => 'Haifa', 'country' => 'Israel', 'region' => 'Oriente Medio'],
            ['code' => 'LBBRT', 'name' => 'Beirut', 'country' => 'Líbano', 'region' => 'Oriente Medio'],
            ['code' => 'SYLAT', 'name' => 'Latakia', 'country' => 'Siria', 'region' => 'Oriente Medio'],
            ['code' => 'LKCOL', 'name' => 'Colombo', 'country' => 'Sri Lanka', 'region' => 'Sur de Asia'],
            ['code' => 'INNSA', 'name' => 'Nhava Sheva', 'country' => 'India', 'region' => 'Sur de Asia'],
            ['code' => 'INDEL', 'name' => 'Nueva Delhi', 'country' => 'India', 'region' => 'Sur de Asia'],
            ['code' => 'INMAA', 'name' => 'Chennai', 'country' => 'India', 'region' => 'Sur de Asia'],
            ['code' => 'INMUN', 'name' => 'Mundra', 'country' => 'India', 'region' => 'Sur de Asia'],
            ['code' => 'INBLR', 'name' => 'Bangalore', 'country' => 'India', 'region' => 'Sur de Asia'],
            ['code' => 'INCAL', 'name' => 'Calcuta', 'country' => 'India', 'region' => 'Sur de Asia'],
            ['code' => 'BDCTG', 'name' => 'Chittagong', 'country' => 'Bangladés', 'region' => 'Sur de Asia'],
            ['code' => 'PKKAR', 'name' => 'Karachi', 'country' => 'Pakistán', 'region' => 'Sur de Asia'],
            ['code' => 'KEMOM', 'name' => 'Mombasa', 'country' => 'Kenia', 'region' => 'África'],
            ['code' => 'MZMAP', 'name' => 'Maputo', 'country' => 'Mozambique', 'region' => 'África'],
            ['code' => 'TZDAR', 'name' => 'Dar es Salaam', 'country' => 'Tanzania', 'region' => 'África'],
            ['code' => 'ZADUR', 'name' => 'Durban', 'country' => 'Sudáfrica', 'region' => 'África'],
            ['code' => 'ZACPT', 'name' => 'Ciudad del Cabo', 'country' => 'Sudáfrica', 'region' => 'África'],
            ['code' => 'BJCOT', 'name' => 'Cotonú', 'country' => 'Benín', 'region' => 'África'],
            ['code' => 'GHTEM', 'name' => 'Tema', 'country' => 'Ghana', 'region' => 'África'],
            ['code' => 'NGLAG', 'name' => 'Lagos', 'country' => 'Nigeria', 'region' => 'África'],
            ['code' => 'CIABI', 'name' => 'Abiyán', 'country' => 'Costa de Marfil', 'region' => 'África'],
            ['code' => 'TGLOM', 'name' => 'Lomé', 'country' => 'Togo', 'region' => 'África'],
            ['code' => 'MACAS', 'name' => 'Casablanca', 'country' => 'Marruecos', 'region' => 'África'],
            ['code' => 'TNTNS', 'name' => 'Túnez', 'country' => 'Túnez', 'region' => 'África'],
            ['code' => 'DZALG', 'name' => 'Argel', 'country' => 'Argelia', 'region' => 'África'],
            ['code' => 'ARENA', 'name' => 'Buenos Aires', 'country' => 'Argentina', 'region' => 'Sudamérica'],
            ['code' => 'UYMON', 'name' => 'Montevideo', 'country' => 'Uruguay', 'region' => 'Sudamérica'],
            ['code' => 'BRSTS', 'name' => 'Santos', 'country' => 'Brasil', 'region' => 'Sudamérica'],
            ['code' => 'BRRGR', 'name' => 'Río Grande', 'country' => 'Brasil', 'region' => 'Sudamérica'],
            ['code' => 'BRRDJ', 'name' => 'Río de Janeiro', 'country' => 'Brasil', 'region' => 'Sudamérica'],
            ['code' => 'PYASU', 'name' => 'Asunción', 'country' => 'Paraguay', 'region' => 'Sudamérica'],
            ['code' => 'COBUE', 'name' => 'Buenaventura', 'country' => 'Colombia', 'region' => 'Sudamérica'],
            ['code' => 'PECAL2C', 'name' => 'Callao', 'country' => 'Perú', 'region' => 'Sudamérica'],
            ['code' => 'ECGYL', 'name' => 'Guayaquil', 'country' => 'Ecuador', 'region' => 'Sudamérica'],
            ['code' => 'CLIQU', 'name' => 'Iquique', 'country' => 'Chile', 'region' => 'Sudamérica'],
            ['code' => 'CLARI', 'name' => 'Arica', 'country' => 'Chile', 'region' => 'Sudamérica'],
            ['code' => 'CLVAL', 'name' => 'Valparaíso', 'country' => 'Chile', 'region' => 'Sudamérica'],
            ['code' => 'CLSAN', 'name' => 'San Antonio', 'country' => 'Chile', 'region' => 'Sudamérica'],
            ['code' => 'MXMAN', 'name' => 'Manzanillo', 'country' => 'México', 'region' => 'Norteamérica'],
            ['code' => 'PAPAN', 'name' => 'Ciudad de Panamá', 'country' => 'Panamá', 'region' => 'Centroamérica'],
            ['code' => 'MXGDL', 'name' => 'Guadalajara', 'country' => 'México', 'region' => 'Norteamérica'],
            ['code' => 'PRSJU', 'name' => 'San Juan', 'country' => 'Puerto Rico', 'region' => 'Caribe'],
        ];

        $query = strtolower($query);
        return array_values(array_filter($ports, function ($port) use ($query) {
            return str_contains(strtolower($port['name']), $query) ||
                str_contains(strtolower($port['code']), $query) ||
                str_contains(strtolower($port['country']), $query) ||
                str_contains(strtolower($port['region']), $query);
        }));
    }

    private function searchPortsPOL($query)
    {
        $ports = [
            ['code' => 'CNSZN', 'name' => 'Shen Zhen', 'country' => 'China', 'region' => 'Sudeste asiático'],
            ['code' => 'CNGUA', 'name' => 'Guang Zhou', 'country' => 'China', 'region' => 'Sudeste asiático'],
            ['code' => 'CNSHA', 'name' => 'Shang Hai', 'country' => 'China', 'region' => 'Sudeste asiático'],
            ['code' => 'HKHKG', 'name' => 'Hong Kong', 'country' => 'Hong Kong', 'region' => 'Sudeste asiático'],
            ['code' => 'CNXMN', 'name' => 'Xia Men', 'country' => 'China', 'region' => 'Sudeste asiático'],
            ['code' => 'CNNBO', 'name' => 'Ning Bo', 'country' => 'China', 'region' => 'Sudeste asiático'],
            ['code' => 'CNQIN', 'name' => 'Qing Dao', 'country' => 'China', 'region' => 'Sudeste asiático'],
            ['code' => 'CNTJN', 'name' => 'Tian Jin', 'country' => 'China', 'region' => 'Sudeste asiático'],
            ['code' => 'CNXIA', 'name' => 'Xia Men', 'country' => 'China', 'region' => 'Sudeste asiático'],
        ];

        $query = strtolower($query);
        return array_values(array_filter($ports, function ($port) use ($query) {
            return str_contains(strtolower($port['name']), $query) ||
                str_contains(strtolower($port['code']), $query) ||
                str_contains(strtolower($port['country']), $query) ||
                str_contains(strtolower($port['region']), $query);
        }));
    }
    /**
     * Buscar tarifas FCL usando los códigos POL/POD
     */
    public function buscarTarifasFCL()
    {

        $this->reset(['rates', 'loadingRates', 'statusMessage', 'fclRates', 'currentPage']);
        $this->currentRunId = null;


        if (empty($this->polCode) || empty($this->podCode)) {
            $this->statusMessage = 'Selecciona origen y destino';
            return;
        }

        $this->loadingRates = true;
        $this->statusMessage = 'Conectando con el proveedor de tarifas';

        $originCode = strtolower(substr($this->polCode, 0, 5));
        $destCode   = strtolower(substr($this->podCode, 0, 5));
        $url = "https://www.5688.com.cn/fcl/{$originCode}-{$destCode}";

        try {
            $response = Http::timeout(120)->withHeaders([
                'Authorization' => 'Bearer ' . config('services.firecrawl.key'), // Recomendado: pon tu API key en .env
                'Content-Type'  => 'application/json',
            ])->post('https://api.firecrawl.dev/v2/scrape', [
                'url' => $url,
                'formats' => [
                    [
                        'type' => 'json',
                        'prompt' => 'Extrae todas las tarifas FCL válidas de la tabla. Incluye solo filas completas con precios numéricos. Ignora filas de carga o incompletas.',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'rates' => [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'shipping_line' => ['type' => 'string'],
                                            'gp20'          => ['type' => ['integer', 'null']],
                                            'gp40'          => ['type' => ['integer', 'null']],
                                            'hq40'          => ['type' => ['integer', 'null']],
                                            'transit_time'  => ['type' => ['string', 'null']],
                                            'valid_until'   => ['type' => ['string', 'null']],
                                            'closing'       => ['type' => ['integer', 'null']],
                                        ],
                                        'required' => ['shipping_line']
                                    ]
                                ]
                            ],
                            'required' => ['rates']
                        ]
                    ]
                ]
            ]);

            $responseArray = json_decode($response->body(), true);

            if (!$responseArray || !($responseArray['success'] ?? false)) {
                throw new \Exception('Error al conectar con Firecrawl o respuesta no exitosa.');
            }

            $data = $responseArray['data']['json']['rates'] ?? [];

            if (empty($data)) {
                $this->statusMessage = 'No se encontraron tarifas para esta ruta en este momento.';
                $this->fclRates = collect();
            } else {
                $this->fclRates = collect($data);

                $this->guardarTarifasEnBaseDeDatos($url, $data);

                $this->statusMessage = '¡Tarifas actualizadas! Se encontraron ' . count($data) . ' opciones.';
            }
            if (empty($data) || count($data) === 0) {
                $this->statusMessage = 'No hay tarifas en tiempo real para esta ruta en este momento.';
                $this->cargarTarifasDesdeBaseDeDatos();
                if ($this->fclRates->isNotEmpty()) {
                    $this->statusMessage .= ' Mostrando tarifas guardadas anteriormente.';
                }
            }
        } catch (\Exception $e) {
            Log::error('Error Firecrawl FCL: ' . $e->getMessage(), [
                'url' => $url,
                'pol' => $this->polCode,
                'pod' => $this->podCode,
            ]);
            $this->cargarTarifasDesdeBaseDeDatos();

            if ($this->fclRates->isEmpty()) {
                $this->statusMessage = 'No se pudieron obtener tarifas en tiempo real. Inténtalo más tarde.';
            } else {
                $this->statusMessage = 'Mostrando tarifas guardadas (conexión fallida).';
            }
        }
        $this->loadingRates = false;
    }
    private function guardarTarifasEnBaseDeDatos($url, $rates)
    {
        try {
            $search = Search::updateOrCreate(
                [
                    'pol_code' => $this->polCode,
                    'pod_code' => $this->podCode,
                    'transport_type' => 'FCL',
                ],
                [
                    'external_uid' => null,
                    'result_page_url' => $url,
                    'total_rates_found' => count($rates),
                    'success' => true,
                    'searched_at' => now(),
                ]
            );

            Rate::where('search_id', $search->id)->delete();

            foreach ($rates as $rateData) {
                $shippingLine = ShippingLine::firstOrCreate(
                    ['name' => $rateData['shipping_line']],
                    ['code' => strtoupper(str_replace(' ', '', $rateData['shipping_line'])), 'name' => $rateData['shipping_line']]
                );

                Rate::create([
                    'search_id' => $search->id,
                    'shipping_line_id' => $shippingLine->id,
                    'valid_until' => $rateData['valid_until'] ?? now()->addWeek(),
                    'gp20' => $rateData['gp20'],
                    'gp40' => $rateData['gp40'],
                    'hq40' => $rateData['hq40'],
                    'transit_time' => $rateData['transit_time'] ? (int) filter_var($rateData['transit_time'], FILTER_SANITIZE_NUMBER_INT) : null,
                    'closing' => $rateData['closing'],
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('No se pudieron guardar las tarifas en BD', ['error' => $e->getMessage()]);
        }
    }
    private function cargarTarifasDesdeBaseDeDatos()
    {
        $today = now()->format('Y-m-d');

        $cachedRates = DB::table('rates')
            ->join('searches', 'rates.search_id', '=', 'searches.id')
            ->join('shipping_lines', 'rates.shipping_line_id', '=', 'shipping_lines.id')
            ->where('searches.pol_code', $this->polCode)
            ->where('searches.pod_code', $this->podCode)
            ->where('searches.transport_type', 'FCL')
            ->where('searches.success', 1)
            ->where('rates.valid_until', '>=', $today)
            ->select([
                'shipping_lines.name as shipping_line_name',
                'shipping_lines.logo as shipping_line_logo',
                'rates.gp20',
                'rates.gp40',
                'rates.hq40',
                'rates.transit_time',
                'rates.closing',
                'rates.valid_until'
            ])
            ->orderBy('rates.gp20')
            ->get()
            ->map(function ($rate) {
                return (array) $rate;
            });

        $this->fclRates = $cachedRates->isNotEmpty() ? $cachedRates : collect();
    }

    /**
     * Método llamado desde JS cuando el stream emite "ready" con los datos ya procesados.
     */
    public function onRatesReady($payload)
    {
        $this->rates = $payload;
        $this->message = 'Resultados listos';
        $this->loadingRates = false;
        $this->progress = null;
    }

    /**
     * Método opcional para actualizaciones de progreso (si quieres emitir heartbeat desde JS)
     */
    public function onProgress($progress)
    {
        $this->progress = $progress;
    }

    public function setPage($page)
    {
        $this->currentPage = $page;
    }

    public function nextPage()
    {
        $totalPages = ceil(count($this->fclRates) / $this->perPage);
        if ($this->currentPage < $totalPages) {
            $this->currentPage++;
        }
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function setTipoCarga($tipo)
    {
        $this->tipoCarga = $tipo;
        $this->limpiar();
    }

    public function limpiar()
    {
        $this->reset([
            'peso',
            'volumen',
            'cantidad',
            'largo',
            'ancho',
            'alto',
            'valorMercancia',
            'resultado',
            'desglose',
            'desglose_reporte',
            'mostrarPregunta',
            'respuestaUsuario',
            'clienteNombre',
            'clienteEmail',
            'clienteTelefono',
            'clienteDireccion',
            'clienteCiudad',
            'agenteId',
            'gastosAdicionales',
            'peso',
            'cbm_directo',
            'q',
            'producto',
            'id_producto',
            'imagen',
            'manualImagen'
        ]);

        $this->reset([
            'searchPOL',
            'searchPOD',
            'polCode',
            'podCode',
            'fclRates',
            'polSuggestions',
            'podSuggestions',
            'showPOLDropdown',
            'showPODDropdown',
            'rates',
            'loadingRates',
            'statusMessage',
            'currentRunId'
        ]);

        $this->reset([
            'recojoAlmacen',
            'departamentoDestino',
            'verificacionProducto',
            'verificacionCalidad',
            'verificacionEmpresaDigital',
            'verificacionEmpresaPresencial'
        ]);

        $this->destinoFinal = 'la_paz';
    }

    private function calculateVerificationCost(): float
    {
        // Contar ítems (al menos 1 si no hay nada en la lista pero se está cotizando)
        $count = count($this->productos);
        if ($count === 0 && ($this->valorMercancia > 0 || $this->peso > 0)) {
            $count = 1;
        }
        $count = max(1, $count);

        $rate = 10.00;

        if ($count <= 2) {
            $rate = 30.00;
        } elseif ($count === 3) {
            $rate = 25.00;
        } elseif ($count === 4) {
            $rate = 20.00;
        } elseif ($count === 5) {
            $rate = 15.00;
        } else {
            $rate = 10.00; // 6 o más
        }

        return $rate * $count;
    }

    public function render()
    {
        return view('livewire.calculadora-maritima');
    }
}
