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

    public $desconsolidacionAutos = '1';

    public $desconsolidacionFCL = '1';

    public $volumen;

    public $valorMercancia;

    public $cantidad;

    public $cbm_directo;

    public $p2pPrice;

    public $q;

    public $producto = '';

    public $id_producto = '';

    public $imagen = '';

    public $esAnalisis = false;

    public $manualImagen;

    public $costoDestino = 0;

    public $nombreDestino = '';

    public $totalLogisticaBolivia = 0;

    public $totalBrokersChina = 0;

    public $totalCBMCobrables = 0;
    public $volumenUnitario = 0;

    public $largo, $ancho, $alto;
    public $origen = '';
    public $destino = '';

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

    public $productos = [];
    public $temp_producto = '';
    public $temp_imagen = '';
    public $temp_manualImagen;
    public $temp_manualImagenRoRo;
    public $temp_imagenRoRo = '';
    public $temp_cantidad = 1;
    public $temp_piezas_por_carton = 1;
    public $temp_peso_unitario = 0;
    public $temp_largo = 0;
    public $temp_ancho = 0;
    public $temp_alto = 0;
    public $temp_cbm = 0;
    public $temp_dimension_total = '';
    public $temp_valor_unitario = 0;
    public $volumenTotal = 0;
    public $temp_hs_code = '';
    public $temp_arancel = 0;
    public $arancelSuggestions = [];
    public $temp_con_arancel = false;
    public $temp_con_arancelFCL = false;
    public $temp_costo_interno = 0;


    // Pallet properties (Estándar Asia: 110x110 cm, 15kg)
    public $temp_con_pallet = false;
    public $temp_pallet_largo = 110;
    public $temp_pallet_ancho = 110;
    public $temp_pallet_alto = 15;
    public $temp_pallet_peso = 15;

    // Control de visibilidad del resultado
    public $mostrarDesglose = false;

    public $cbmTotalUnitario = 0;
    public $pesoKGTotal = 0;
    public $cantidadTotal = 0;

    public $recojoAlmacen = false;
    public $destinoFinal = 'tarija';
    public $departamentoDestino = '';


    public $verificacionProducto = false;
    public $verificacionCalidad = false;
    public $seguroCarga = false;
    public $verificacionEmpresaDigital = false;
    public $verificacionSustanciasPeligrosas = false;
    public $verificacionEmpresaPresencial = false;
    public $pagosInternacionalesSwift = 'swift';
    public $requierePagoInternacional = false;
    public $vehiculosEncontrados = [];
    public $selectedVehicleIndex = null;

    // FCL Specific Properties
    public $transporteTerrestre = false;
    public $representacionImportacionFCL = false;
    public $pesoMercanciaFCL = 0;
    public $volumenMercanciaFCL = 0;


    // Autos (Ro-Ro) Specific Properties
    public $tipoCarroceria = ''; // For visual selection and shipping price
    public $claseVehiculo = '';
    public $marcaVehiculo = '';
    public $tipoVehiculo = '';
    public $subtipoVehiculo = '';
    public $cilindradaVehiculo = '';
    public $traccionVehiculo = '';
    public $transmisionVehiculo = '';
    public $paisVehiculo = '';
    public $combustibleVehiculo = '';
    public $anioVehiculo = '';
    public $otrasCaracteristicasVehiculo = '';
    public $largoVehiculo = 0;
    public $anchoVehiculo = 0;
    public $altoVehiculo = 0;
    public $pesoVehiculo = 0;
    public $polVehiculo = ''; // Puerto de Origen
    public $clasesVehiculoOptions = [];
    public $marcasVehiculoOptions = [];
    public $tiposVehiculoOptions = [];
    public $subtiposVehiculoOptions = [];
    public $cilindradaVehiculoOptions = [];
    public $traccionVehiculoOptions = [];
    public $transmisionVehiculoOptions = [];
    public $combustibleVehiculoOptions = [];
    public $anioVehiculoOptions = [];
    public $paisVehiculoOptions = [];
    public $otrasCaracteristicasVehiculoOptions = [];
    public $requierePagoInternacionalAutos = false;
    public $pagosInternacionalesSwiftAutos = 'swift';
    public $seguroCargaAutos = false;
    public $valorMercanciaRoRo = 0;
    public $nombreVehiculoRoRo = '';
    public $temp_con_arancelRoRo = false;
    public $temp_hs_codeRoRo = '';
    public $temp_arancelRoRo = 0;
    public $arancelSuggestionsRoRo = [];
    public $verificacionProductoRoRo = false;
    public $verificacionCalidadRoRo = false;
    public $verificacionEmpresaDigitalRoRo = false;
    public $verificacionSustanciasPeligrosasRoRo = false;
    public $verificacionEmpresaPresencialRoRo = false;
    public $verificacionEmisionesGasesRoRo = false;
    public $requiereReexpidicionVehiculoRoRo = false;
    public $requiereTransporteTerrestreRoRo = false;

    public $clienteNombreRoRo = '';
    public $clienteEmailRoRo = '';
    public $clienteTelefonoRoRo = '';
    public $codigoPaisRoRo = '+591';
    public $clienteDireccionRoRo = '';
    public $clienteCiudadRoRo = '';
    public $agenteIdRoRo = '';
    public $gastosAdicionalesRoRo = [];



    public $selectedRateIndex = null;

    public $tipoCobroActual = '';
    public $unidadActual = '';
    public $valorFacturadoActual = 0;
    public $cbmFacturadoActual = 0;
    public $desglose_reporte = [];

    public $clienteNombre = '';
    public $clienteEmail = '';
    public $clienteTelefono = '';
    public $codigoPais = '+591';
    public $clienteDireccion = '';
    public $clienteCiudad = '';
    public $agenteId = '';
    public $volumetricWeight = 0;
    public $gastosAdicionales = [];

    public $tipoCobro = '';

    public $valorCBMKG = 0;

    public $agentes = [
        [
            'id' => 1,
            'nombre' => 'Alejandra',
            'email' => 'logistica@iagroups.com',
            'telefono' => '702693251'
        ],
        [
            'id' => 2,
            'nombre' => 'Christian',
            'email' => 'auction@iagroups.com',
            'telefono' => '64580634'
        ],
        [
            'id' => 3,
            'nombre' => 'Brenda',
            'email' => 'consultora@iagroups.com',
            'telefono' => '64588678'
        ],
        [
            'id' => 4,
            'nombre' => 'Ivana',
            'email' => 'agentes@iagroups.com',
            'telefono' => '72976032'
        ],
        [
            'id' => 5,
            'nombre' => 'Marcelo',
            'email' => 'tarija@iagroups.com',
            'telefono' => '72981315'
        ],
        [
            'id' => 6,
            'nombre' => 'Make ',
            'email' => 'make@iagroups.com',
            'telefono' => '64700457'
        ],
        [
            'id' => 7,
            'nombre' => 'Miguel ',
            'email' => 'miguel@iagroups.com',
            'telefono' => '64700293'
        ],
        [
            'id' => 8,
            'nombre' => 'Patricia ',
            'email' => 'viajes@iagroups.com',
            'telefono' => '63778785'
        ],
        // [
        //     'id' => 6,
        //     'nombre' => 'Alejandra Gonzales Soliz',
        //     'email' => 'academy@iagroups.com',
        //     'telefono' => '64700293'
        // ],
    ];

    public $precios_envio_por_tipo = [
        'pickup'      => ['1' => 3300, '0' => 2850],
        'van'         => ['1' => 3200, '0' => 2750],
        'suv'         => ['1' => 2900, '0' => 2450],
        'wagon'       => ['1' => 2700, '0' => 2250],
        'minivan'     => ['1' => 2700, '0' => 2250],
        'sedan'       => ['1' => 2700, '0' => 2250],
        'coupe'       => ['1' => 2500, '0' => 2050],
        'convertible' => ['1' => 2300, '0' => 1950],
        'hatchback'   => ['1' => 2300, '0' => 1950],
        'truck'       => ['1' => 4500, '0' => 3850],
        'bus'         => ['1' => 5000, '0' => 4500],
        'heavy'       => ['1' => 5000, '0' => 4250],
    ];

    public $vehiculos_datos = [
        'pickup' => [
            'largo_mm'     => 5800,
            'ancho_mm'     => 2000,
            'alto_mm'      => 1900,
            'peso_kg'      => 2800,
        ],
        'van' => [
            'largo_mm'     => 5700,
            'ancho_mm'     => 2200,
            'alto_mm'      => 2500,
            'peso_kg'      => 3200,
        ],
        'suv' => [
            'largo_mm'     => 5000,
            'ancho_mm'     => 1920,
            'alto_mm'      => 1800,
            'peso_kg'      => 2200,
        ],
        'wagon' => [
            'largo_mm'     => 4800,
            'ancho_mm'     => 1850,
            'alto_mm'      => 1500,
            'peso_kg'      => 1600,
        ],
        'minivan' => [
            'largo_mm'     => 5100,
            'ancho_mm'     => 1900,
            'alto_mm'      => 1750,
            'peso_kg'      => 1900,
        ],
        'sedan' => [
            'largo_mm'     => 4850,
            'ancho_mm'     => 1820,
            'alto_mm'      => 1450,
            'peso_kg'      => 1650,
        ],
        'convertible' => [
            'largo_mm'     => 4500,
            'ancho_mm'     => 1800,
            'alto_mm'      => 1350,
            'peso_kg'      => 1600,
        ],
        'coupe' => [
            'largo_mm'     => 4600,
            'ancho_mm'     => 1800,
            'alto_mm'      => 1400,
            'peso_kg'      => 1550,
        ],
        'hatchback' => [
            'largo_mm'     => 4100,
            'ancho_mm'     => 1750,
            'alto_mm'      => 1450,
            'peso_kg'      => 1350,
        ],
        'truck' => [
            'largo_mm'     => 7000,
            'ancho_mm'     => 2500,
            'alto_mm'      => 3000,
            'peso_kg'      => 5000,
        ],
        'bus' => [
            'largo_mm'     => 12000,
            'ancho_mm'     => 2550,
            'alto_mm'      => 3200,
            'peso_kg'      => 12000,
        ],
        'heavy' => [
            'largo_mm'     => 8000,
            'ancho_mm'     => 2500,
            'alto_mm'      => 3000,
            'peso_kg'      => 8000,
        ],
    ];


    public $departamentosAgrupados = [
        'amazonica' => [
            'label' => 'Zona Amazónica',
            'color' => 'text-yellow-300',
            'departamentos' => [
                ['value' => 'pando', 'nombre' => 'Pando'],
                ['value' => 'santa_cruz', 'nombre' => 'Santa Cruz'],
                ['value' => 'beni', 'nombre' => 'Beni'],
            ],
        ],
        'central' => [
            'label' => 'Zona Central',
            'color' => 'text-blue-300',
            'departamentos' => [
                ['value' => 'cochabamba', 'nombre' => 'Cochabamba'],
                ['value' => 'chuquisaca', 'nombre' => 'Chuquisaca'],
                ['value' => 'tarija', 'nombre' => 'Tarija'],
            ],
        ],
        'sur' => [
            'label' => 'Zona Sur',
            'color' => 'text-green-300',
            'departamentos' => [
                ['value' => 'oruro', 'nombre' => 'Oruro'],
                ['value' => 'potosi', 'nombre' => 'Potosí'],
            ],
        ],
    ];


    public function mount()
    {
        $this->loadClasesVehiculo();
        $qParam = request()->query('q') ?? $this->q;

        if ($qParam) {
            try {
                $decoded = json_decode(base64_decode($qParam), true);

                if (is_array($decoded)) {
                    $this->temp_producto = $decoded['producto'] ?? '';
                    $this->temp_imagen = $decoded['imagen'] ?? '';

                    $this->temp_cantidad = isset($decoded['cantidad']) ? floatval($decoded['cantidad']) : 1;
                    $this->temp_piezas_por_carton = isset($decoded['piezas_por_carton']) ? floatval($decoded['piezas_por_carton']) : 1;

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
        $this->fetchP2P();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['clienteNombre', 'clienteEmail', 'clienteTelefono', 'clienteCiudad', 'clienteDireccion'])) {
            $this->validateOnly($propertyName, [
                'clienteNombre'    => 'required|string|min:3',
                'clienteCiudad'    => 'required|not_in:0',
                'clienteDireccion' => 'required|string|min:5',
                'clienteEmail'     => 'required|email',
                'clienteTelefono'  => 'required|string|min:7',
            ], [
                'clienteNombre.required'    => 'El nombre del cliente es obligatorio.',
                'clienteNombre.min'         => 'El nombre debe tener al menos 3 caracteres.',
                'clienteCiudad.required'    => 'Debe seleccionar una ciudad.',
                'clienteCiudad.not_in'      => 'Debe seleccionar una ciudad.',
                'clienteDireccion.required' => 'La dirección es obligatoria.',
                'clienteDireccion.min'      => 'La dirección debe tener al menos 5 caracteres.',
                'clienteEmail.required'     => 'El email es obligatorio.',
                'clienteEmail.email'        => 'El formato del email no es válido.',
                'clienteTelefono.required'  => 'El teléfono es obligatorio.',
                'clienteTelefono.min'       => 'El teléfono debe tener al menos 7 caracteres.',
            ]);
        }
    }

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

    public function fetchP2P()
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            ])->post("https://p2p.binance.com/bapi/c2c/v2/friendly/c2c/adv/search", [
                "asset" => "USDT",
                "fiat" => $this->fiat,
                "merchantCheck" => false,
                "page" => 1,
                "payTypes" => [],
                "publisherType" => null,
                "rows" => 1,
                "tradeType" => "BUY" // "BUY" muestra el precio al que los mercantes venden
            ]);

            if ($response->successful() && isset($response->json()['data'][0])) {
                $data = $response->json()['data'][0]['adv']['price'];
                $this->p2pPrice = $data + 0.03;
            }
        } catch (\Exception $e) {
            // Error silencioso para no romper la vista
            $this->p2pPrice = 'Error';
        }
    }

    public function selectArancel($codigo, $arancel)
    {
        $this->temp_hs_code = $codigo;
        $this->temp_hs_codeRoRo = $codigo;
        $this->temp_arancel = $arancel;
        $this->temp_arancelRoRo = $arancel;
        $this->arancelSuggestions = [];
        $this->arancelSuggestionsRoRo = [];
    }

    public function limpiarArancelSearch()
    {
        $this->arancelSuggestions = [];
    }

    public function agregarProducto()
    {
        $this->validate([
            'temp_manualImagen' => 'nullable|image|max:1024', // 1MB Max
            'temp_producto' => 'required|min:3',
            'temp_cantidad' => 'required|numeric|min:1',
            'temp_valor_unitario' => 'required|numeric|min:0',
            'temp_peso_unitario' => 'required|numeric|gt:0',
        ], [
            'temp_manualImagen.image' => 'El archivo debe ser una imagen',
            'temp_manualImagen.max' => 'La imagen no debe pesar más de 1MB',
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

        // Calculate dimensions including pallet if applicable
        $largoTotal = $this->temp_largo;
        $anchoTotal = $this->temp_ancho;
        $altoTotal = $this->temp_alto;
        $pesoAdicionalPallet = 0;

        if ($this->temp_con_pallet) {
            // El pallet define un área mínima (footprint). Si la carga es más grande, manda la carga.
            $largoTotal = max($this->temp_largo, $this->temp_pallet_largo);
            $anchoTotal = max($this->temp_ancho, $this->temp_pallet_ancho);
            // La altura siempre se suma (la carga va sobre el pallet)
            $altoTotal = $this->temp_alto + $this->temp_pallet_alto;
            // Se suma el peso del pallet
            $pesoAdicionalPallet = $this->temp_pallet_peso;
        }

        $cantidadTotalItem = $this->temp_cantidad * $this->temp_piezas_por_carton;

        $pesoTotalItem = ($this->temp_peso_unitario + $pesoAdicionalPallet) * $cantidadTotalItem;
        $valorTotalItem = $this->temp_valor_unitario * $cantidadTotalItem;


        if ($this->temp_largo > 0 && $this->temp_ancho > 0 && $this->temp_alto > 0) {
            $this->volumenUnitario = ($largoTotal * $anchoTotal * $altoTotal) / 1000000;
        } elseif ($this->temp_cbm > 0) {
            $this->volumenUnitario = $this->temp_cbm;
        } else {

            $this->volumenUnitario = 0.01;
        }


        $prefix = strtoupper(substr(trim($this->clienteNombre ?: 'PROD'), 0, 3));
        $number = str_pad(count($this->productos) + 1, 2, '0', STR_PAD_LEFT);

        $this->productos[] = [
            'id' => "$prefix-$number",
            'producto' => $this->temp_producto,
            'imagen' => $imagenUrl,
            'cantidad' => $this->temp_cantidad,
            'piezas_por_carton' => $this->temp_piezas_por_carton,
            'peso_unitario' => $this->temp_peso_unitario,
            'largo' => $this->temp_largo,
            'ancho' => $this->temp_ancho,
            'alto' => $this->temp_alto,
            'cbm' => $this->temp_cbm,
            'volumen_unitario' => $this->volumenUnitario,
            'valor_unitario' => $this->temp_valor_unitario,
            'total_peso' => $pesoTotalItem,
            'cbm_unitario' => $this->cbmTotalUnitario,
            'total_valor' => $valorTotalItem,
            'hs_code' => $this->temp_hs_code,
            'con_arancel' => $this->temp_con_arancel,

            'costo_interno' => $this->temp_costo_interno,
            'con_pallet' => $this->temp_con_pallet,
            'pallet_largo' => $this->temp_pallet_largo,
            'pallet_ancho' => $this->temp_pallet_ancho,
            'pallet_alto' => $this->temp_pallet_alto
        ];
        $this->pesoKGTotal = $pesoTotalItem;
        $this->cantidadTotal = $this->temp_cantidad;

        $this->temp_producto = '';
        $this->temp_imagen = '';
        $this->temp_manualImagen = null;
        $this->temp_cantidad = 1;
        $this->temp_piezas_por_carton = 1;
        $this->temp_peso_unitario = 0;
        $this->temp_largo = 0;
        $this->temp_ancho = 0;
        $this->temp_alto = 0;
        $this->temp_cbm = 0;
        $this->temp_valor_unitario = 0;
        $this->temp_hs_code = '';
        $this->temp_costo_interno = 0;
        $this->temp_con_pallet = false;
        $this->temp_pallet_largo = 110;
        $this->temp_pallet_ancho = 110;
        $this->temp_pallet_alto = 15;
        $this->temp_pallet_peso = 15;

        $this->calcularTotales();
    }

    public function eliminarProducto($index)
    {
        if (isset($this->productos[$index])) {
            unset($this->productos[$index]);
            $this->productos = array_values($this->productos);

            // Re-generar IDs para mantener la secuencia
            $prefix = strtoupper(substr(trim($this->clienteNombre ?: 'PROD'), 0, 3));
            foreach ($this->productos as $idx => &$item) {
                $number = str_pad($idx + 1, 2, '0', STR_PAD_LEFT);
                $item['id'] = "$prefix-$number";
            }

            $this->calcularTotales();
        }
    }

    public function calcularTotales()
    {
        $pesoTotal = 0;
        $this->volumenTotal = 0; // Resetear acumulador
        $valorTotal = 0;
        $cantidadTotal = 0;
        $cantidadTotalPiezas = 0;
        $costoInterno = 0;

        $this->totalCBMCobrables = 0; // Reset
        $this->valorCBMKG = 0;

        foreach ($this->productos as $prod) {
            $costoInterno += $prod['costo_interno'];
            $cantidadTotalPiezas += $prod['piezas_por_carton'] * $prod['cantidad'];
            $pesoTotal += $prod['peso_unitario'] * $cantidadTotalPiezas;
            $this->volumenTotal += $prod['volumen_unitario'] * $cantidadTotalPiezas;
            $valorTotal += $prod['total_valor'];
            $cantidadTotal += $cantidadTotalPiezas;


            $largo = $prod['largo'] ?? 0;
            $ancho = $prod['ancho'] ?? 0;
            $alto = $prod['alto'] ?? 0;
            $cbm = $prod['cbm'] ?? 0;
            $this->volumetricWeight = $this->obtenerPesoVolumetrico($largo, $ancho, $alto, $pesoTotal, $cbm);
            if ($cbm > 0) {
                $this->totalCBMCobrables += $cbm;
            } elseif ($largo > 0 && $ancho > 0 && $alto > 0) {
                $volumenInfo = $this->obtenerVolumenTotal($largo, $ancho, $alto, 0, $cantidadTotal);
                if ($volumenInfo['tipoCbm'] == 'CBM') {
                    $this->totalCBMCobrables += $volumenInfo['cbm'];
                    $this->valorCBMKG = $pesoTotal;
                    $this->tipoCobro = 'CBM';
                } else {
                    $this->totalCBMCobrables += $volumenInfo['kg'];
                    $this->valorCBMKG +=  $volumenInfo['kg'];
                    $this->tipoCobro = 'KG';
                }
            }
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

    function obtenerPesoVolumetrico($largo, $alto, $ancho, $peso, $cbm)
    {
        $largo = floatval($largo);
        $alto = floatval($alto);
        $ancho = floatval($ancho);

        if ($largo == 0 && $alto == 0 && $ancho == 0) {
            return $cbm;
        }

        $volumen_cbm = ($largo * $alto * $ancho) / 1_000_000;
        if ($volumen_cbm < 0.01) {
            $cbmPorVolumen = ($largo * $alto * $ancho) / 5000;
            return max($cbmPorVolumen, $peso);
        }

        return $volumen_cbm;
    }

    function obtenerVolumenTotal($largo, $alto, $ancho, $cbm, $qty)
    {
        $largo = floatval($largo);
        $alto = floatval($alto);
        $ancho = floatval($ancho);

        if ($largo == 0 && $alto == 0 && $ancho == 0) {
            return $cbm;
        }

        $volumenCalculado = (($largo * $alto * $ancho) / 1_000_000) * $qty;
        if ($volumenCalculado < 0.01) {
            $cbmPorVolumen = (($largo * $alto * $ancho) / 5000) * $qty;
            return [
                'kg' => $cbmPorVolumen,
                'tipoCbm' => 'KG'
            ];
        }
        return [
            'cbm' => $volumenCalculado,
            'tipoCbm' => 'CBM'
        ];
    }


    public function calcularResultado()
    {
        $this->calcular();
        $this->mostrarDesglose = true;
        $this->dispatch('scroll-to-result');
    }
    public function calcularResultadoRoRo()
    {
        $this->calcularRoRo();
        $this->mostrarDesglose = true;
    }


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
        $this->calculateDesgloseFCL();
    }
    public function updatedVerificacionCalidad()
    {
        $this->calcular();
        $this->calculateDesgloseFCL();
    }
    public function updatedSeguroCarga()
    {
        $this->calcular();
        $this->calculateDesgloseFCL();
    }
    public function updatedVerificacionEmpresaDigital()
    {
        $this->calcular();
        $this->calculateDesgloseFCL();
    }
    public function updatedVerificacionSustanciasPeligrosas()
    {
        $this->calcular();
        $this->calculateDesgloseFCL();
    }
    public function updatedVerificacionEmpresaPresencial()
    {
        $this->calcular();
        $this->calculateDesgloseFCL();
    }
    public function updatedTransporteTerrestre()
    {
        $this->calculateDesgloseFCL();
    }
    public function updatedRequierePagoInternacional()
    {
        $this->calcular();
        $this->calculateDesgloseFCL();
    }
    public function updatedRepresentacionImportacionFCL()
    {
        $this->calculateDesgloseFCL();
    }

    public function updatedTempManualImagen()
    {
        $this->calculateDesgloseFCL();
    }

    public function updatedTempProducto()
    {
        $this->calculateDesgloseFCL();
    }

    public function selectRate($index, $container)
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


        // Recuperar la tarifa usando el index que viene de la vista
        $rate = $this->fclRates[$index] ?? null;

        if (!$rate) {
            session()->flash('error', 'No se pudo encontrar la tarifa seleccionada.');
            return;
        }

        $this->selectedRate = $rate;
        $this->selectedContainer = $container;

        Cliente::create([
            'clienteNombre'    => $this->clienteNombre,
            'clienteEmail'     => $this->clienteEmail,
            'clienteTelefono'  => $this->clienteTelefono,
            'clienteDireccion' => $this->clienteDireccion,
            'clienteCiudad'    => $this->clienteCiudad,
        ]);

        $this->calculateDesgloseFCLonlyFlete();

        $this->dispatch('scroll-to-result');

        session()->flash('success', 'Cotización generada correctamente.');
    }
    public function calculateDesgloseFCL()
    {
        $rate = $this->selectedRate;
        $container = $this->selectedContainer;

        if (!$rate || !$container) return;

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
        $gastosLocales = $this->getGastosLocales();
        $subtotalPortuaria = array_sum($gastosLocales);

        $valorMercancia = floatval($this->valorMercancia);

        // Agencias y Despacho
        $agencia_despachante = $this->calculate_tiered_charge($valorMercancia);
        $despachante = 768.68;

        // Costos Adicionales
        $adicionales = $this->calculateAdditionalCosts($valorMercancia);
        $costoAdicionales = $adicionales['total'];

        // Transporte
        $transporte_terrestre = $this->transporteTerrestre ? 3600 : 0;

        // Impuestos
        $taxes = $this->calculateCifAndTaxes($valorMercancia, $transporte_terrestre);
        $impuesto = $taxes['impuesto'];
        $gravamenArancelario = $taxes['gravamen'];
        $iva = $taxes['iva'];
        $ice = $taxes['ice'];
        $desconsolidacion = $this->desconsolidacionFCL == '0' ? 1800 : 0;



        // Construir Desglose UI
        $this->desglose = [
            "Flete Marítimo ({$shippingLine} - {$containerName})" => $precioBase,
            '─ GESTIÓN PORTUARIA Y LOGÍSTICA' => null,
        ];

        foreach ($gastosLocales as $concepto => $monto) {
            $this->desglose[$concepto] = number_format($monto, 2);
        }

        $this->desglose = array_merge($this->desglose, [
            '   Valor de la Mercancía' => number_format($valorMercancia, 2, '.', ''),
            '   Gestión Portuaria' => number_format($subtotalPortuaria, 2),
            '   Booking' => number_format($precioBase * 0.05, 2),
        ]);
        $this->desglose['   Cargo de Desconsolidacion'] = number_format($desconsolidacion, 2);
        if ($this->transporteTerrestre) {
            $this->desglose['   Transporte Terrestre'] = number_format($transporte_terrestre, 2);
        }

        // Merge de desglose de adicionales
        $this->desglose = array_merge($this->desglose, $adicionales['desglose']);
        $this->desglose =  array_merge($this->desglose, [
            '   Cargos de importacion y despacho' => number_format($despachante, 2),
            '   Agencia Despachante' => number_format($agencia_despachante, 2),
            '   GA' => number_format($gravamenArancelario, 2),
            '   IVA' => number_format($iva, 2),
            '   ICE' => number_format($ice, 2),
        ]);
        // Resultado Final
        $this->resultado = $precioBase + $subtotalPortuaria + ($precioBase * 0.05) + $valorMercancia + $transporte_terrestre + $impuesto + $agencia_despachante + $despachante + $costoAdicionales + $desconsolidacion;

        // Construir Reporte
        $this->desglose_reporte = $this->buildReportData(
            $container,
            $containerName,
            $shippingLine,
            $precioBase,
            $valorMercancia,
            $subtotalPortuaria,
            $agencia_despachante,
            $despachante,
            $transporte_terrestre,
            $taxes,
            $costoAdicionales,
            $adicionales,
            $desconsolidacion
        );

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

    public function calculateDesgloseFCLonlyFlete()
    {
        $rate = $this->selectedRate;
        $container = $this->selectedContainer;

        if (!$rate || !$container) return;

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
        $valorMercancia = floatval($this->valorMercancia);
        // Construir Desglose UI
        $this->desglose = [
            "Flete Marítimo ({$shippingLine} - {$containerName})" => $precioBase,
            '─ GESTIÓN PORTUARIA Y LOGÍSTICA' => null,
        ];

        $this->desglose = array_merge($this->desglose, [
            '   Valor de la Mercancía' => number_format($valorMercancia, 2, '.', '')
        ]);

        $this->desglose_reporte = $this->buildReportDataFCLonlyFlete(
            $container,
            $containerName,
            $shippingLine,
            $precioBase,
            $valorMercancia
        );

        $this->resultado = $precioBase   + $valorMercancia;

        $this->tipoCobroActual = 'Contenedor Completo (FCL)';
        $this->unidadActual = "Contenedor " . $containerName;
        $this->valorFacturadoActual = $precioBase;
        $this->cbmFacturadoActual = $containerName;

        $this->mostrarPregunta = true;
        $this->mostrarDesglose = true;
        $this->respuestaUsuario = null;

        session()->flash('success', 'Cotización generada correctamente.');
    }

    private function getGastosLocales()
    {
        return [
            '   ├─ Manipulación en Terminal' => 159.00,
            '   ├─ Gastos de Documentación' => 72.00,
            '   ├─ Gastos Portuarios Varios' => 102.00,
            '   ├─ Control de Equipo (EIR)' => 22.00,
            '   ├─ Sello de Seguridad' => 10.00,
            '   ├─ Despacho de Aduana' => 40.00,
            '   └─ Gasto por Liberación Digital' => 65.00
        ];
    }

    private function calculateCifAndTaxes($valorMercancia, $transporte_terrestre)
    {
        $seguroParaImpuestos = $valorMercancia * 0.02; // 2% fijo para base imponible
        $valorFlete = $valorMercancia * 0.20; // 20% teórico

        $baseCIF = $valorMercancia + $valorFlete + $transporte_terrestre + $seguroParaImpuestos;
        $gravamen = $baseCIF * ($this->temp_arancel / 100);
        $iva = ($baseCIF + $gravamen) * (14.94 / 100);
        $ice = $baseCIF * (15 / 100);

        return [
            'gravamen' => $gravamen,
            'iva' => $iva,
            'ice' => $ice,
            'impuesto' => $iva + $gravamen + $ice
        ];
    }

    private function calculateAdditionalCosts($valorMercancia)
    {
        $total = 0;
        $desgloseAux = [];
        $desconsolidacion = 0;
        $costoSwift = 0;
        $seguroEstimado = 0;


        if ($this->verificacionSustanciasPeligrosas) {
            $desgloseAux['   Envio de producto peligroso'] = number_format(250.00, 2);
            $total += 250.00;
        }

        if ($this->requierePagoInternacional) {
            $tasaSwift = ($this->pagosInternacionalesSwift === 'swift') ? 0.01 : 0.025;
            $costoSwift = $valorMercancia * $tasaSwift;
            $desgloseAux['   Pago Internacional'] = number_format($costoSwift, 2);
            $total += $costoSwift;
        }

        if ($this->seguroCarga) {
            $seguroEstimado = $valorMercancia * 0.02;
            $desgloseAux['   Seguro de Carga'] = number_format($seguroEstimado, 2);
            $total += $seguroEstimado;
        }

        if ($this->representacionImportacionFCL) {
            $desgloseAux['   Representación Importación'] = number_format(502.87, 2);
            $total += 502.87;
        }

        // Verificaciones
        $verificaciones = [
            'verificacionProducto' => ['costo' => 30.00, 'label' => 'Verificación del Producto'],
            'verificacionCalidad' => ['costo' => 50.00, 'label' => 'Verificación de Calidad del Producto'],
            'verificacionEmpresaDigital' => ['costo' => 100.00, 'label' => 'Verificación de Empresa Digital'],
            'verificacionEmpresaPresencial' => ['costo' => 350.00, 'label' => 'Verificación Presencial de Empresa']
        ];

        foreach ($verificaciones as $prop => $data) {
            if ($this->$prop) {
                $desgloseAux['   ' . $data['label']] = number_format($data['costo'], 2, '.', '');
                $total += $data['costo'];
            }
        }

        return [
            'total' => $total,
            'desglose' => $desgloseAux,
            'data_reporte' => [
                'desconsolidacion' => $desconsolidacion,
                'pagosInternacionales' => $costoSwift,
                'seguroCarga' => $seguroEstimado,
                'representacionImportacionFCL' => $this->representacionImportacionFCL ? 502.87 : 0,
                'verificacionProducto' => $this->verificacionProducto ? 30 : 0,
                'verificacionCalidad' => $this->verificacionCalidad ? 50 : 0,
                'verificacionEmpresaDigital' => $this->verificacionEmpresaDigital ? 100 : 0,
                'verificacionEmpresaPresencial' => $this->verificacionEmpresaPresencial ? 350 : 0,
                'verificacionSustanciasPeligrosas' => $this->verificacionSustanciasPeligrosas ? 250.00 : 0,
            ]
        ];
    }

    private function buildReportDataFCLonlyFlete($container, $containerName, $shippingLine, $precioBase, $valorMercancia)
    {
        return [
            'ref' => $this->id_producto ?: 'FCL-' . strtoupper($container),
            'descripcion' => $this->temp_producto ?: "Flete Marítimo ({$shippingLine} - {$containerName})",
            'cantidad' => 1,
            'unidad' => 'PCS',
            'precio' => $precioBase,
            'total' => $precioBase,
            'imagen' => $this->temp_manualImagen ? $this->temp_manualImagen->temporaryUrl() : ($this->temp_imagen ?: null),
            'valorMercancia' => $valorMercancia,
        ];
    }

    private function buildReportData($container, $containerName, $shippingLine, $precioBase, $valorMercancia, $subtotalPortuaria, $agencia_despachante, $despachante, $transporte_terrestre, $taxes, $costoAdicionales, $adicionales, $desconsolidacion)
    {
        return [
            'ref' => $this->id_producto ?: 'FCL-' . strtoupper($container),
            'descripcion' => $this->temp_producto ?: "Flete Marítimo ({$shippingLine} - {$containerName})",
            'cantidad' => 1,
            'unidad' => 'PCS',
            'precio' => $precioBase,
            'total' => $precioBase,
            'imagen' => $this->temp_manualImagen ? $this->temp_manualImagen->temporaryUrl() : ($this->temp_imagen ?: null),
            'valorMercancia' => $valorMercancia,
            'gestionPortuaria' => $subtotalPortuaria,
            'booking' => $precioBase * 0.05,
            'agencia_despachante' => $agencia_despachante,
            'despachante' => $despachante,
            'transporte_terrestre' => $transporte_terrestre,
            'gravamen' => $taxes['gravamen'],
            'costo_adicionales' => $costoAdicionales,
            'iva' => $taxes['iva'],
            'impuestos' => $taxes['impuesto'],
            'seguro_carga' => $adicionales['data_reporte']['seguroCarga'],
            'representacion' => $adicionales['data_reporte']['representacionImportacionFCL'],
            'comision_swift' => $adicionales['data_reporte']['pagosInternacionales'],
            'desconsolidacion' => $desconsolidacion,
            'verificacion_sustancias_peligrosas' => $adicionales['data_reporte']['verificacionSustanciasPeligrosas'],
            'verificacion_producto' => $adicionales['data_reporte']['verificacionProducto'],
            'verificacion_calidad' => $adicionales['data_reporte']['verificacionCalidad'],
            'verificacion_empresa_digital' => $adicionales['data_reporte']['verificacionEmpresaDigital'],
            'verificacion_empresa_presencial' => $adicionales['data_reporte']['verificacionEmpresaPresencial']
        ];
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

            'beni' => ['costo' => 62.96, 'nombre' => 'Beni'],
            'pando' => ['costo' => 62.96, 'nombre' => 'Pando'],

            'cochabamba' => ['costo' => 62.96, 'nombre' => 'Cochabamba'],
            'santa_cruz' => ['costo' => 62.96, 'nombre' => 'Santa Cruz'],

            'chuquisaca' => ['costo' => 80.00, 'nombre' => 'Chuquisaca'],
            'potosi' => ['costo' => 80.00, 'nombre' => 'Potosí'],
            'oruro' => ['costo' => 48.48, 'nombre' => 'Oruro'],
            'tarija' => ['costo' => 80.00, 'nombre' => 'Tarija'],
        ];
    }

    public function getPaisesProperty()
    {
        return [
            ['code' => '+591', 'flag' => '🇧🇴', 'name' => 'Bolivia'],
            ['code' => '+54',  'flag' => '🇦🇷', 'name' => 'Argentina'],
            ['code' => '+55',  'flag' => '🇧🇷', 'name' => 'Brasil'],
            ['code' => '+56',  'flag' => '🇨🇱', 'name' => 'Chile'],
            ['code' => '+57',  'flag' => '🇨🇴', 'name' => 'Colombia'],
            ['code' => '+51',  'flag' => '🇵🇪', 'name' => 'Perú'],
            ['code' => '+52',  'flag' => '🇲🇽', 'name' => 'México'],
            ['code' => '+1',   'flag' => '🇺🇸', 'name' => 'USA'],
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
                'clienteNombre.min' => 'El nombre debe tener al menos 3 caracteres.',
                'clienteCiudad.required' => 'Debe seleccionar una ciudad.',
                'clienteCiudad.not_in' => 'Debe seleccionar una ciudad.',
                'clienteDireccion.required' => 'La dirección es obligatoria.',
                'clienteDireccion.min' => 'La dirección debe tener al menos 5 caracteres.',
                'clienteEmail.required' => 'El email es obligatorio.',
                'clienteEmail.email' => 'El formato del email no es válido.',
                'clienteTelefono.required' => 'El teléfono es obligatorio.',
                'clienteTelefono.min' => 'El teléfono debe tener al menos 7 caracteres.',
            ]);
        }

        $this->mostrarPregunta = false;
        $this->respuestaUsuario = null;

        $this->fecha = now()->toDateTimeString();

        // ---------------------------------------------------------
        // LOGIC TO HANDLE "DRAFT" CALCULATION (User hasn't clicked Add)
        // ---------------------------------------------------------

        if ($this->tipoCobro == 'CBM') {
            $shippingPackage = $this->calculateShippingPackage($this->pesoKGTotal, $this->totalCBMCobrables);
        } else {
            $maxPeso = max($this->totalCBMCobrables, $this->pesoKGTotal);
            $shippingPackage = $this->calculateShippingPackage($maxPeso, $this->totalCBMCobrables);
        }

        $this->resultado = (float) $shippingPackage['costo'];
        $this->tipoCobroActual = $shippingPackage['tipo'] ?? '';
        $this->unidadActual = $shippingPackage['unidad'] ?? '';
        $this->valorFacturadoActual = $shippingPackage['valor_facturado'] ?? 0;
        $this->cbmFacturadoActual = $shippingPackage['cbm_facturado'] ?? 0;
        $this->costoDestino = $shippingPackage['costo_destino'] ?? 0;
        $this->nombreDestino = $shippingPackage['nombre_destino'] ?? '';

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
                'imagen' => $this->imagen,
                'costo_destino' => $this->costoDestino,
                'nombre_destino' => $this->nombreDestino,
            ];
        }
        if ($isFinal) {
            Cliente::create([
                'clienteNombre'    => $this->clienteNombre,
                'clienteEmail'     => $this->clienteEmail,
                'clienteTelefono'  => $this->clienteTelefono,
                'clienteDireccion' => $this->clienteDireccion,
                'clienteCiudad'    => $this->clienteCiudad,
            ]);
        }

        session()->flash('success', 'Cálculo completado exitosamente.');
    }

    public function calcularRoRo()
    {
        $this->mostrarPregunta = false;
        $this->respuestaUsuario = null;

        $this->fecha = now()->toDateTimeString();

        $detalleRoRo = $this->obtenerDetalleRoRo();

        $this->resultado = $detalleRoRo['costo'];

        $this->mostrarPregunta = true;

        session()->flash('success', 'Cálculo completado exitosamente.');
    }

    public function obtenerDetalleRoRo()
    {

        $costoVerificacion = 0;
        $comision = 0.03;
        $comisionBolivia = 0.035;
        $desconsolidacion = 0;
        $costo_envio = 0;
        $transporte_terrestre = 0;
        $costo_embalaje = 0;
        $documentacion_exportacion = 500;
        $grua_china = 500;


        if ($this->desconsolidacionAutos == '1' || $this->desconsolidacionAutos == '0') {
            if (!empty($this->tipoCarroceria) && isset($this->precios_envio_por_tipo[$this->tipoCarroceria])) {
                $costo_envio = $this->precios_envio_por_tipo[$this->tipoCarroceria][$this->desconsolidacionAutos] ?? 0;
            } else {
                $costo_envio = 0;
            }
        } else {
            $costo_envio = 0;
        }

        $totalLogisticaChina = ($this->valorMercanciaRoRo + $costo_envio) * $comision;
        $totalLogisticaBolivia = ($this->valorMercanciaRoRo + $costo_envio) * $comisionBolivia;
        $iva = $this->valorMercanciaRoRo * 0.1494;
        $gravamen_arancelario = $this->valorMercanciaRoRo * 0.10;
        $ice = ($this->valorMercanciaRoRo + $gravamen_arancelario) * 0.15;
        $poliza_importacion = $iva + $gravamen_arancelario + $ice;
        $agencia = $this->calculate_tiered_charge($this->valorMercanciaRoRo);


        $costoPagoInternacional = 0;
        if ($this->requierePagoInternacionalAutos) {
            $tasaPagoInternacional = ($this->pagosInternacionalesSwiftAutos === 'swift') ? 0.01 : 0.025;
            $costoPagoInternacional = $this->valorMercanciaRoRo > 0 ? $this->valorMercanciaRoRo * $tasaPagoInternacional : 0;
        }
        $tasaSeguro = $this->seguroCargaAutos ? 0.02 : 0;
        $costoSeguro = $this->valorMercanciaRoRo > 0 ? $this->valorMercanciaRoRo * $tasaSeguro : 0;

        if ($this->desconsolidacionAutos == '1') {
            $desconsolidacion = 1600;
        } elseif ($this->desconsolidacionAutos == '0') {
            $desconsolidacion = 1800;
        } else {
            $desconsolidacion = 0;
        }

        $this->desglose = [
            'Valor de Mercancía' => $this->valorMercanciaRoRo,
            'Costo de Envío Internacional' => number_format($costo_envio, 2, '.', ''),
        ];

        if ($this->requierePagoInternacionalAutos) {
            $this->desglose['Pago Internacional'] = number_format($costoPagoInternacional, 2, '.', '');
        }

        $this->desglose['Gestión Logística'] = number_format($totalLogisticaBolivia, 2, '.', '');
        $this->desglose['Brokers en China'] = number_format($totalLogisticaChina, 2, '.', '');

        if ($this->seguroCargaAutos) {
            $this->desglose['Seguro de la Carga'] = number_format($costoSeguro, 2, '.', '');
        }
        if ($this->desconsolidacionAutos == '0' || $this->desconsolidacionAutos == '1') {
            $this->desglose['Cargo de Desconsolidacion'] = number_format($desconsolidacion, 2, '.', '');
        }

        if ($this->desconsolidacionAutos == '2') {
            $this->desglose['Costo de caja de embalaje'] = 500;
            $costo_embalaje = 500;
        }
        $this->desglose['Documentacion Exportacion'] = $documentacion_exportacion;
        $this->desglose['Grua en China'] = $grua_china;

        if ($this->verificacionProductoRoRo) {
            $costoVerificacion = $this->calculateVerificationCost();
            $this->desglose['Verificación del Producto'] = number_format($costoVerificacion, 2, '.', '');
        }
        if ($this->verificacionCalidadRoRo) {
            $this->desglose['Verificación de Calidad del Producto'] = 50.00;
            $costoVerificacion += 50.00;
        }

        if ($this->verificacionEmpresaDigitalRoRo) {
            $this->desglose['Verificación de Empresa Digital'] = 100.00;
            $costoVerificacion += 100.00;
        }
        if ($this->verificacionEmpresaPresencialRoRo) {
            $this->desglose['Verificación Presencial de Empresa'] = 350.00;
            $costoVerificacion += 350.00;
        }
        if ($this->verificacionEmisionesGasesRoRo) {
            $this->desglose['Verificación de Emisiones de Gases'] = 680.00;
            $costoVerificacion += 680.00;
        }
        if ($this->requiereReexpidicionVehiculoRoRo) {
            $this->desglose['Reexpidicion del vehiculo'] = 650.00;
            $costoVerificacion += 650.00;
        }
        if ($this->verificacionSustanciasPeligrosasRoRo) {
            $this->desglose['Envio de producto peligroso'] = 250.00;
            $costoVerificacion += 250.00;
        }
        if ($this->verificacionSustanciasPeligrosasRoRo) {
            $this->desglose['Envio de producto peligroso'] = 250.00;
            $costoVerificacion += 250.00;
        }
        $this->desglose['GA'] = number_format($gravamen_arancelario, 2, '.', '');
        $this->desglose['Agencia Despachante'] = number_format($agencia, 2, '.', '');
        $this->desglose['IVA'] = number_format($iva, 2, '.', '');
        $this->desglose['ICE'] = number_format($ice, 2, '.', '');
        $this->desglose['Poliza de Importacion'] = number_format($poliza_importacion, 2, '.', '');
        if ($this->requiereTransporteTerrestreRoRo) {
            if ($this->desconsolidacionAutos == '1') {
                $this->desglose['Transporte Terrestre'] = 1600;
                $transporte_terrestre = 1600;
            } elseif ($this->desconsolidacionAutos == '2') {
                $this->desglose['Transporte Terrestre'] = 1800;
                $transporte_terrestre = 1800;
            } else {
                $this->desglose['Transporte Terrestre'] = 2100;
                $transporte_terrestre = 2100;
            }
        }
        $total = $this->valorMercanciaRoRo + $costoVerificacion + $gravamen_arancelario + $iva + $ice + $transporte_terrestre + $costo_embalaje + $poliza_importacion + $grua_china + $documentacion_exportacion + $totalLogisticaChina + $totalLogisticaBolivia + $costo_envio + $costoPagoInternacional + $costoSeguro + $desconsolidacion;
        return [
            'costo' => (float) number_format($total, 2, '.', ''),
        ];
    }

    public function aplicarDimensiones($tipo)
    {
        $total = floatval($this->temp_dimension_total);
        if ($total <= 0) return;
        if ($total <= 0) return;

        switch ($tipo) {
            case 'square':
                $this->temp_largo = number_format($total, 2, '.', '');
                $this->temp_ancho = number_format($total, 2, '.', '');
                $this->temp_alto = number_format($total, 2, '.', '');
                break;
            case 'rectangular':
                $largo = $total;
                $ancho = $total / 2;
                $alto = $total / 2;
                $this->temp_largo = number_format($largo, 2, '.', '');
                $this->temp_ancho = number_format($ancho, 2, '.', '');
                $this->temp_alto = number_format($alto, 2, '.', '');
                break;
            case 'flat': // Baja y ancha
                $largo = $total;
                $ancho = $total / 2;
                $alto = $total / 4;
                $this->temp_largo = number_format($largo, 2, '.', '');
                $this->temp_ancho = number_format($ancho, 2, '.', '');
                $this->temp_alto = number_format($alto, 2, '.', '');
                break;
            case 'long': // Alargada
                $largo = $total;
                $ancho = 4;
                $alto = 2;
                $this->temp_largo = number_format($largo, 2, '.', '');
                $this->temp_ancho = number_format($ancho, 2, '.', '');
                $this->temp_alto = number_format($alto, 2, '.', '');
                break;
        }
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
            ['min' => 20,   'precio' => 164],
            ['min' => 15,   'precio' => 174],
            ['min' => 11,   'precio' => 184],
            ['min' => 8,    'precio' => 194],
            ['min' => 5,    'precio' => 204],
            ['min' => 3,    'precio' => 214],
            ['min' => 1,    'precio' => 224],
            ['min' => 0.5,  'precio' => 137],
            ['min' => 0.25, 'precio' => 70]
        ];

        $costoFinal = 0.0;
        $tipoCobro  = '';
        $valorUsado = 0.0;
        $costoInterno = 0;

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

            $tarifasAsc = array_reverse($TARIFA_POR_CBM);

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
        $costoDestino = $resultadoDestino['costo'] * $this->volumenTotal;
        $nombreDestino = $resultadoDestino['nombre'];

        $unidad = str_contains($tipoCobro, 'Peso') ? 'kg' : 'm³';
        if ($unidad === 'kg') {
            $valorFacturado = $costoFinal * $this->cantidadTotal;
        } else {
            $valorFacturado = $costoFinal * $this->cantidadTotal;
        }


        foreach ($this->productos as $prod) {
            $costoInterno += $prod['costo_interno'];
            $this->totalLogisticaBolivia = ($prod['total_valor'] + $valorFacturado  + $costoInterno) * 0.03;
            $this->totalBrokersChina = ($prod['total_valor'] + $valorFacturado  + $costoInterno) * 0.035;
        }
        $seguro = $this->valorMercancia * 0.02;
        $valorFlete = $this->valorMercancia * 0.20;
        $baseCIF = $this->valorMercancia + $seguro + $valorFlete;
        $arancelPct = $this->temp_con_arancel ? (isset($this->temp_arancel) ? floatval($this->temp_arancel) : 0) : 0;
        $totalArancel =  ($this->valorMercancia + $seguro + $valorFlete) * ($arancelPct / 100);
        $iva = ($this->temp_con_arancel && $this->valorMercancia != 0) ? ($totalArancel + $baseCIF) * 0.1494 : 0;
        $impuesto = ($this->temp_con_arancel && $this->valorMercancia != 0) ? ($totalArancel + $iva) : 0;


        // CÁLCULO DE COMISIÓN POR PAGO INTERNACIONAL
        $swiftFee = 0;
        $tasaSwift = 0;

        if ($this->requierePagoInternacional) {
            if ($this->pagosInternacionalesSwift === 'swift') {
                $tasaSwift = 0.01; // 1%
            } elseif ($this->pagosInternacionalesSwift === 'sin_swift') {
                $tasaSwift = 0.025; // 2.5%
            }
            $swiftFee = $this->valorMercancia == 0 ? 0 : $this->valorMercancia * $tasaSwift;
        }
        $almacen = 3.59;
        $documentacion = 17.24;
        $formularios = 14.37;
        $examenPrevio = 35;
        $addServices = 0;
        $costoVerificacion = 0;

        $representacion = ($this->valorMercancia * 3500) / 25000;


        $despacho = $this->valorMercancia == 0 ? 0 : $almacen + $documentacion + $formularios + $this->valorCBMKG + $representacion + $examenPrevio;

        $total_tiered_charge = $this->valorMercancia == 0 ? 0 : $this->calculate_tiered_charge($this->valorMercancia);

        // =====================================================
        // NUEVO: DESGLOSE DETALLADO DEL FLETE MARÍTIMO POR CBM
        // =====================================================
        $desgloseFleteMaritimo = [];
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

        // =====================================================
        // CONSTRUCCIÓN FINAL DEL DESGLOSE
        // =====================================================

        $this->gastosAdicionales = [];
        if ($this->valorMercancia === 0 || $this->valorMercancia === null) {
            $this->desglose = [
                'Costo de Envío Interno' => number_format($costoInterno, 2, '.', ''),
                'Costo de Envío Internacional' => number_format($valorFacturado, 2, '.', ''),
                'Gestión Logística' => number_format($this->totalLogisticaBolivia, 2, '.', ''),
                'Brokers en China' => number_format($this->totalBrokersChina, 2, '.', ''),

            ];
        } else {
            $this->desglose = [
                'Valor de Mercancía' => number_format($this->valorMercancia, 2, '.', ''),
                'Costo de Envío Interno' => number_format($costoInterno, 2, '.', ''),
                'Costo de Envío Internacional' => number_format($valorFacturado, 2, '.', ''),
            ];

            if ($this->requierePagoInternacional) {
                $this->desglose['Pago Internacional'] = number_format($swiftFee, 2, '.', '');
            }

            $this->desglose['Gestión Logística'] = number_format($this->totalLogisticaBolivia, 2, '.', '');
            $this->desglose['Brokers en China'] = number_format($this->totalBrokersChina, 2, '.', '');


            if ($impuesto !== 0) {
                $this->desglose['Cargos de importacion y despacho'] = number_format($despacho, 2, '.', '');
                $this->desglose['Agencia Despachante'] = number_format($total_tiered_charge, 2, '.', '');
                $this->desglose['GA'] = number_format($totalArancel, 2, '.', '');
                $this->desglose['IVA'] = number_format($iva, 2, '.', '');
            }
        }
        $this->desglose = array_merge($this->desglose, $desgloseFleteMaritimo);

        if ($this->recojoAlmacen) {
            $this->desglose['Recojo desde Almacén'] = number_format($costoRecojo * $this->volumenTotal, 2, '.', '');
            $addServices = (float) number_format($costoRecojo * $this->volumenTotal, 2, '.', '');
        }
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
        if ($this->seguroCarga && $this->valorMercancia !== 0) {
            $this->desglose['Seguro de la Carga'] = number_format($this->valorMercancia * 0.02, 2, '.', '');
            $costoVerificacion += $this->valorMercancia * 0.02;
        }
        if ($this->verificacionSustanciasPeligrosas) {
            $this->desglose['Envio de producto peligroso'] = 250.00;
            $costoVerificacion += 250.00;
        }

        if ($costoDestino > 0 && $nombreDestino) {
            $this->desglose["Entrega a " . $nombreDestino] = number_format($costoDestino, 2, '.', '');
        }
        if ($this->recojoAlmacen) $this->gastosAdicionales['Recojo desde Almacén'] = number_format($costoRecojo * $this->volumenTotal, 2, '.', '');
        $this->gastosAdicionales['Costo de Envío Interno'] = number_format($costoInterno, 2, '.', '');
        $this->gastosAdicionales['Cargos de importacion y despacho'] = number_format($despacho, 2, '.', '');
        $this->gastosAdicionales['Agencia Despachante'] = number_format($total_tiered_charge, 2, '.', '');
        $this->gastosAdicionales['Gravamen Arancelario'] = number_format($totalArancel, 2, '.', '');
        $this->gastosAdicionales['Impuesto IVA'] = number_format($iva, 2, '.', '');
        if ($this->requierePagoInternacional) {
            $this->gastosAdicionales['Pago Internacional'] = number_format($swiftFee, 2, '.', '');
        }

        if ($this->verificacionProducto) $this->gastosAdicionales['Verificación de Producto'] = number_format($this->calculateVerificationCost(), 2, '.', '');
        if ($this->verificacionCalidad) $this->gastosAdicionales['Verificación de Calidad'] = 50.00;
        if ($this->seguroCarga) $this->gastosAdicionales['Seguro de la Carga'] = number_format($this->valorMercancia * 0.02, 2, '.', '');
        if ($this->verificacionEmpresaDigital) $this->gastosAdicionales['Verificación de Empresa Digital'] = 100.00;
        if ($this->verificacionEmpresaPresencial) $this->gastosAdicionales['Verificación Presencial de Empresa'] = 350.00;
        if ($this->verificacionSustanciasPeligrosas) $this->gastosAdicionales['Envio de producto peligroso'] = 250.00;

        $total = $this->valorMercancia + $valorFacturado + $this->totalLogisticaBolivia + $this->totalBrokersChina + $addServices + $costoDestino + $costoVerificacion + $totalArancel + $iva + $total_tiered_charge + $despacho + $costoInterno + $swiftFee;

        return [
            'costo'  => number_format($total, 2, '.', ''),
            'tipo'   => $tipoCobro,
            'unidad' => $unidad,
            'costo_destino' => $costoDestino,
            'nombre_destino' => $nombreDestino,
            'valor_facturado' => $valorFacturado,
            'cbm_facturado' => $tipoCobro === 'CBM' ? $valorUsado : null,
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
        if ($this->tipoCarga === 'uld') {
            $agenteSeleccionado = collect($this->agentes)->firstWhere('id', $this->agenteIdRoRo);
            $this->obtenerDetalleRoRo();
        }

        // Prepare parameters based on cargo type
        $params = [
            'tipoCarga' => $this->tipoCarga,
            'peso' => $this->peso,
            'volumen' => $this->volumen,
            'p2pPrice' => $this->p2pPrice,
            'agente' => json_encode($agenteSeleccionado),
            'gastosAdicionales' => json_encode($this->gastosAdicionales),
            'desglose' => json_encode($this->desglose),
            'resultado' => $this->resultado,
        ];

        if ($this->tipoCarga === 'uld') {
            // RoRo Specific Data
            $params['clienteNombre'] = $this->clienteNombreRoRo;
            $params['clienteEmail'] = $this->clienteEmailRoRo;
            $params['clienteTelefono'] = $this->clienteTelefonoRoRo;
            $params['clienteDireccion'] = $this->clienteDireccionRoRo;
            $params['clienteCiudad'] = $this->clienteCiudadRoRo;

            $params['valorMercancia'] = $this->valorMercanciaRoRo;

            // For RoRo, extra expenses are in breakdown
            $params['gastosAdicionales'] = json_encode($this->desglose);

            $params['vehiculo'] = json_encode([
                'nombre' => $this->nombreVehiculoRoRo,
                'imagen' => $this->temp_imagenRoRo ? $this->temp_imagenRoRo : ($this->temp_manualImagenRoRo ? $this->temp_manualImagenRoRo->temporaryUrl() : ''),
            ]);
        } else {
            // LCL/FCL Data
            $params = array_merge($params, [
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
                'p2pPrice' => $this->p2pPrice,
            ]);
        }

        return redirect()->route('cotizacion.pdf', $params);
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

    private function loadClasesVehiculo()
    {
        try {
            $response = Http::withoutVerifying()->get('https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctclasevehiculo');
            if ($response->successful()) {
                $this->clasesVehiculoOptions = $response->json()['result'] ?? [];
            }
        } catch (\Exception $e) {
            Log::error("Error al cargar clases de vehículo: " . $e->getMessage());
            $this->clasesVehiculoOptions = [];
        }
    }

    public function updatedClaseVehiculo($value)
    {
        $this->marcaVehiculo = '';
        $this->marcasVehiculoOptions = [];
        if ($value) {
            $this->loadMarcasVehiculo($value);
        }
    }

    private function loadMarcasVehiculo($claseId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctmarcavehiculo/{$claseId}");
            if ($response->successful()) {
                $this->marcasVehiculoOptions = $response->json()['result'] ?? [];
            }
        } catch (\Exception $e) {
            Log::error("Error al cargar marcas de vehículo: " . $e->getMessage());
            $this->marcasVehiculoOptions = [];
        }
    }

    public function updatedMarcaVehiculo($value)
    {
        $this->tipoVehiculo = '';
        $this->tiposVehiculoOptions = [];
        if ($value && $this->claseVehiculo) {
            if ($value === 'NINGUNA') {
                $this->loadPaisDosVehiculo($this->claseVehiculo);
            } else {
                $this->loadTiposVehiculo($this->claseVehiculo, $value);
            }
        }
    }

    private function loadPaisDosVehiculo($claseId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctpaisorigendosvehiculo/{$claseId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->paisVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->paisVehiculoOptions = [];
        }
    }

    private function loadTiposVehiculo($claseId, $marcaId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distincttipovehiculo/{$claseId}/{$marcaId}");
            if ($response->successful()) {
                $this->tiposVehiculoOptions = $response->json()['result'] ?? [];
            }
        } catch (\Exception $e) {
            Log::error("Error al cargar tipos de vehículo: " . $e->getMessage());
            $this->tiposVehiculoOptions = [];
        }
    }

    public function updatedTipoVehiculo($value)
    {
        $this->subtipoVehiculo = '';
        $this->subtiposVehiculoOptions = [];
        if ($value && $this->claseVehiculo && $this->marcaVehiculo) {
            if ($value === 'NINGUNA') {
                $this->subtipoVehiculo = '0'; // Placeholder to satisfy dependencies
                $this->loadCilindradaTresVehiculo($this->claseVehiculo, $this->marcaVehiculo);
            } else {
                $this->loadSubtiposVehiculo($this->claseVehiculo, $this->marcaVehiculo, $value);
            }
        }
    }

    private function loadCilindradaTresVehiculo($claseId, $marcaId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctcilindradatresvehiculo/{$claseId}/{$marcaId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->cilindradaVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->cilindradaVehiculoOptions = [];
        }
    }

    private function loadSubtiposVehiculo($claseId, $marcaId, $tipoId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctsubtipovehiculo/{$claseId}/{$marcaId}/{$tipoId}");
            if ($response->successful()) {
                $this->subtiposVehiculoOptions = $response->json()['result'] ?? [];
            }
        } catch (\Exception $e) {
            Log::error("Error al cargar subtipos de vehículo: " . $e->getMessage());
            $this->subtiposVehiculoOptions = [];
        }
    }

    public function updatedSubtipoVehiculo($value)
    {
        $this->cilindradaVehiculo = '';
        $this->cilindradaVehiculoOptions = [];
        if ($value && $this->claseVehiculo && $this->marcaVehiculo && $this->tipoVehiculo) {
            if ($value === 'NINGUNA') {
                $this->loadCilindradaCuatroVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->tipoVehiculo);
            } else {
                $this->loadCilindradaVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->tipoVehiculo, $value);
            }
        }
    }

    private function loadCilindradaCuatroVehiculo($claseId, $marcaId, $tipoId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctcilindradacuatrovehiculo/{$claseId}/{$marcaId}/{$tipoId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->cilindradaVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->cilindradaVehiculoOptions = [];
        }
    }

    private function loadCilindradaVehiculo($claseId, $marcaId, $tipoId, $subtipoId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctcilindradavehiculo/{$claseId}/{$marcaId}/{$tipoId}/{$subtipoId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->cilindradaVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->cilindradaVehiculoOptions = [];
        }
    }

    public function updatedCilindradaVehiculo($value)
    {
        $this->traccionVehiculo = '';
        $this->traccionVehiculoOptions = [];

        if ($value !== '' && $this->claseVehiculo) {
            if ($this->marcaVehiculo === 'NINGUNA') {
                $this->loadTraccionDosVehiculo($this->claseVehiculo, $this->paisVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->tipoVehiculo === 'NINGUNA') {
                $this->loadTraccionTresVehiculo($this->claseVehiculo, $this->marcaVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->subtipoVehiculo === 'NINGUNA') {
                $this->loadTraccionCuatroVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->tipoVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->tipoVehiculo && $this->subtipoVehiculo) {
                $this->loadTraccionVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->tipoVehiculo, $this->subtipoVehiculo, $value);
            }
        }
    }

    private function loadTraccionDosVehiculo($claseId, $paisId, $cilindradaId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distincttracciondosvehiculo/{$claseId}/{$paisId}/{$cilindradaId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->traccionVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->traccionVehiculoOptions = [];
        }
    }

    private function loadTraccionCuatroVehiculo($claseId, $marcaId, $tipoId, $cilindradaId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distincttraccioncuatrovehiculo/{$claseId}/{$marcaId}/{$tipoId}/{$cilindradaId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->traccionVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->traccionVehiculoOptions = [];
        }
    }

    private function loadTraccionTresVehiculo($claseId, $marcaId, $cilindradaId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distincttracciontresvehiculo/{$claseId}/{$marcaId}/{$cilindradaId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->traccionVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->traccionVehiculoOptions = [];
        }
    }

    private function loadTraccionVehiculo($claseId, $marcaId, $tipoId, $subtipoId, $cilindradaId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distincttraccionvehiculo/{$claseId}/{$marcaId}/{$tipoId}/{$subtipoId}/{$cilindradaId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->traccionVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            Log::error("Error al cargar tracción de vehículo: " . $e->getMessage());
            $this->traccionVehiculoOptions = [];
        }
    }

    public function updatedTraccionVehiculo($value)
    {
        $this->transmisionVehiculo = '';
        $this->transmisionVehiculoOptions = [];

        if ($value !== '' && $this->claseVehiculo) {
            if ($this->marcaVehiculo === 'NINGUNA') {
                $this->loadTransmisionDosVehiculo($this->claseVehiculo, $this->paisVehiculo, $this->cilindradaVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->tipoVehiculo === 'NINGUNA') {
                $this->loadTransmisionTresVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->cilindradaVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->subtipoVehiculo === 'NINGUNA') {
                $this->loadTransmisionCuatroVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->tipoVehiculo, $this->cilindradaVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->tipoVehiculo && $this->subtipoVehiculo && $this->cilindradaVehiculo !== '') {
                $this->loadTransmisionVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->tipoVehiculo, $this->subtipoVehiculo, $this->cilindradaVehiculo, $value);
            }
        }
    }

    private function loadTransmisionDosVehiculo($claseId, $paisId, $cilindradaId, $traccionId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distincttransmisiondosvehiculo/{$claseId}/{$paisId}/{$cilindradaId}/{$traccionId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->transmisionVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->transmisionVehiculoOptions = [];
        }
    }

    private function loadTransmisionCuatroVehiculo($claseId, $marcaId, $tipoId, $cilindradaId, $traccionId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distincttransmisioncuatrovehiculo/{$claseId}/{$marcaId}/{$tipoId}/{$cilindradaId}/{$traccionId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->transmisionVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->transmisionVehiculoOptions = [];
        }
    }

    private function loadTransmisionTresVehiculo($claseId, $marcaId, $cilindradaId, $traccionId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distincttransmisiontresvehiculo/{$claseId}/{$marcaId}/{$cilindradaId}/{$traccionId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->transmisionVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->transmisionVehiculoOptions = [];
        }
    }
    private function loadTransmisionVehiculo($claseId, $marcaId, $tipoId, $subtipoId, $cilindradaId, $traccionId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distincttransmisionvehiculo/{$claseId}/{$marcaId}/{$tipoId}/{$subtipoId}/{$cilindradaId}/{$traccionId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->transmisionVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            Log::error("Error al cargar transmisión de vehículo: " . $e->getMessage());
            $this->transmisionVehiculoOptions = [];
        }
    }

    public function updatedTransmisionVehiculo($value)
    {
        $this->combustibleVehiculo = '';
        $this->combustibleVehiculoOptions = [];

        if ($value !== '' && $this->claseVehiculo) {
            if ($this->marcaVehiculo === 'NINGUNA') {
                $this->loadCombustibleDosVehiculo($this->claseVehiculo, $this->paisVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->tipoVehiculo === 'NINGUNA') {
                $this->loadCombustibleTresVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->subtipoVehiculo === 'NINGUNA') {
                $this->loadCombustibleCuatroVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->tipoVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->tipoVehiculo && $this->subtipoVehiculo && $this->cilindradaVehiculo !== '' && $this->traccionVehiculo !== '') {
                $this->loadCombustibleVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->tipoVehiculo, $this->subtipoVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $value);
            }
        }
    }

    private function loadCombustibleDosVehiculo($claseId, $paisId, $cilindradaId, $traccionId, $transmisionId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctcombustibledosvehiculo/{$claseId}/{$paisId}/{$cilindradaId}/{$traccionId}/{$transmisionId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->combustibleVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->combustibleVehiculoOptions = [];
        }
    }

    private function loadCombustibleCuatroVehiculo($claseId, $marcaId, $tipoId, $cilindradaId, $traccionId, $transmisionId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctcombustiblecuatrovehiculo/{$claseId}/{$marcaId}/{$tipoId}/{$cilindradaId}/{$traccionId}/{$transmisionId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->combustibleVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->combustibleVehiculoOptions = [];
        }
    }

    private function loadCombustibleTresVehiculo($claseId, $marcaId, $cilindradaId, $traccionId, $transmisionId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctcombustibletresvehiculo/{$claseId}/{$marcaId}/{$cilindradaId}/{$traccionId}/{$transmisionId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->combustibleVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->combustibleVehiculoOptions = [];
        }
    }

    private function loadCombustibleVehiculo($claseId, $marcaId, $tipoId, $subtipoId, $cilindradaId, $traccionId, $transmisionId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctcombustiblevehiculo/{$claseId}/{$marcaId}/{$tipoId}/{$subtipoId}/{$cilindradaId}/{$traccionId}/{$transmisionId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->combustibleVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            Log::error("Error al cargar combustible de vehículo: " . $e->getMessage());
            $this->combustibleVehiculoOptions = [];
        }
    }

    public function updatedCombustibleVehiculo($value)
    {
        $this->anioVehiculo = '';
        $this->anioVehiculoOptions = [];

        if ($value !== '' && $this->claseVehiculo) {
            if ($this->marcaVehiculo === 'NINGUNA') {
                $this->loadAnioDosVehiculo($this->claseVehiculo, $this->paisVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $this->transmisionVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->tipoVehiculo === 'NINGUNA') {
                $this->loadAnioTresVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $this->transmisionVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->subtipoVehiculo === 'NINGUNA') {
                $this->loadAnioCuatroVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->tipoVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $this->transmisionVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->tipoVehiculo && $this->subtipoVehiculo && $this->cilindradaVehiculo !== '' && $this->traccionVehiculo !== '' && $this->transmisionVehiculo !== '') {
                $this->loadAnioVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->tipoVehiculo, $this->subtipoVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $this->transmisionVehiculo, $value);
            }
        }
    }

    private function loadAnioDosVehiculo($claseId, $paisId, $cilindradaId, $traccionId, $transmisionId, $combustibleId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctaniomodelodosvehiculo/{$claseId}/{$paisId}/{$cilindradaId}/{$traccionId}/{$transmisionId}/{$combustibleId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->anioVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->anioVehiculoOptions = [];
        }
    }

    private function loadAnioCuatroVehiculo($claseId, $marcaId, $tipoId, $cilindradaId, $traccionId, $transmisionId, $combustibleId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctaniomodelocuatrovehiculo/{$claseId}/{$marcaId}/{$tipoId}/{$cilindradaId}/{$traccionId}/{$transmisionId}/{$combustibleId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->anioVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->anioVehiculoOptions = [];
        }
    }

    private function loadAnioTresVehiculo($claseId, $marcaId, $cilindradaId, $traccionId, $transmisionId, $combustibleId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctaniomodelotresvehiculo/{$claseId}/{$marcaId}/{$cilindradaId}/{$traccionId}/{$transmisionId}/{$combustibleId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->anioVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->anioVehiculoOptions = [];
        }
    }

    public function updatedAnioVehiculo($value)
    {
        // Removed global reset of paisVehiculo here to support DosVehiculo flow

        if ($value !== '' && $this->claseVehiculo) {
            if ($this->marcaVehiculo === 'NINGUNA') {
                // In DosVehiculo, Anio triggers loading Otras Caracteristicas.
                // We do NOT clear Pais here because it was selected earlier!
                $this->loadOtrasCaracteristicasDosVehiculo($this->claseVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $this->transmisionVehiculo, $this->combustibleVehiculo, $value, $this->paisVehiculo);
            } else {
                // Standard flows: Anio triggers Pais loading.
                // ONLY reset Pais here for standard flows
                $this->paisVehiculo = '';
                $this->paisVehiculoOptions = [];

                if ($this->marcaVehiculo) {
                    if ($this->tipoVehiculo === 'NINGUNA') {
                        $this->loadPaisTresVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $this->transmisionVehiculo, $this->combustibleVehiculo, $value);
                    } elseif ($this->subtipoVehiculo === 'NINGUNA') {
                        $this->loadPaisCuatroVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->tipoVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $this->transmisionVehiculo, $this->combustibleVehiculo, $value);
                    } elseif ($this->tipoVehiculo && $this->subtipoVehiculo && $this->cilindradaVehiculo !== '' && $this->traccionVehiculo !== '' && $this->transmisionVehiculo !== '' && $this->combustibleVehiculo !== '') {
                        $this->loadPaisVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->tipoVehiculo, $this->subtipoVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $this->transmisionVehiculo, $this->combustibleVehiculo, $value);
                    }
                }
            }
        }
    }

    private function loadOtrasCaracteristicasDosVehiculo($claseId, $cilindradaId, $traccionId, $transmisionId, $combustibleId, $anioId, $paisId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctotrascaracteristicasdosvehiculo/{$claseId}/{$cilindradaId}/{$traccionId}/{$transmisionId}/{$combustibleId}/{$anioId}/{$paisId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->otrasCaracteristicasVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->otrasCaracteristicasVehiculoOptions = [];
        }
    }

    private function loadPaisCuatroVehiculo($claseId, $marcaId, $tipoId, $cilindradaId, $traccionId, $transmisionId, $combustibleId, $anioId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctpaisorigencuatrovehiculo/{$claseId}/{$marcaId}/{$tipoId}/{$cilindradaId}/{$traccionId}/{$transmisionId}/{$combustibleId}/{$anioId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->paisVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->paisVehiculoOptions = [];
        }
    }

    private function loadPaisTresVehiculo($claseId, $marcaId, $cilindradaId, $traccionId, $transmisionId, $combustibleId, $anioId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctpaisorigentresvehiculo/{$claseId}/{$marcaId}/{$cilindradaId}/{$traccionId}/{$transmisionId}/{$combustibleId}/{$anioId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->paisVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->paisVehiculoOptions = [];
        }
    }

    private function loadPaisVehiculo($claseId, $marcaId, $tipoId, $subtipoId, $cilindradaId, $traccionId, $transmisionId, $combustibleId, $anioId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctpaisorigenvehiculo/{$claseId}/{$marcaId}/{$tipoId}/{$subtipoId}/{$cilindradaId}/{$traccionId}/{$transmisionId}/{$combustibleId}/{$anioId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->paisVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            Log::error("Error al cargar país de vehículo: " . $e->getMessage());
            $this->paisVehiculoOptions = [];
        }
    }

    public function updatedTipoCarroceria($value)
    {
        if (isset($this->vehiculos_datos[$value])) {
            $datos = $this->vehiculos_datos[$value];
            // Convert to meters (divide by 1000) for dimensions
            $this->largoVehiculo = $datos['largo_mm'] / 1000;
            $this->anchoVehiculo = $datos['ancho_mm'] / 1000;
            $this->altoVehiculo = $datos['alto_mm'] / 1000;
            // Weight is in KG
            $this->pesoVehiculo = $datos['peso_kg'];
        }
    }

    public function updatedPaisVehiculo($value)
    {
        $this->otrasCaracteristicasVehiculo = '';
        $this->otrasCaracteristicasVehiculoOptions = [];
        if ($value !== '' && $this->claseVehiculo) {
            if ($this->marcaVehiculo === 'NINGUNA') {
                $this->loadCilindradaDosVehiculo($this->claseVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->tipoVehiculo === 'NINGUNA') {
                $this->loadOtrasCaracteristicasTresVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $this->transmisionVehiculo, $this->combustibleVehiculo, $this->anioVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->subtipoVehiculo === 'NINGUNA') {
                $this->loadOtrasCaracteristicasCuatroVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->tipoVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $this->transmisionVehiculo, $this->combustibleVehiculo, $this->anioVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->tipoVehiculo && $this->subtipoVehiculo) {
                // Ensure all previous fields are set for standard flow
                if ($this->cilindradaVehiculo !== '' && $this->traccionVehiculo !== '' && $this->transmisionVehiculo !== '' && $this->combustibleVehiculo !== '' && $this->anioVehiculo !== '') {
                    $this->loadOtrasCaracteristicasVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->tipoVehiculo, $this->subtipoVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $this->transmisionVehiculo, $this->combustibleVehiculo, $this->anioVehiculo, $value);
                }
            }
        }
    }

    private function loadCilindradaDosVehiculo($claseId, $paisId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctcilindradadosvehiculo/{$claseId}/{$paisId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->cilindradaVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->cilindradaVehiculoOptions = [];
        }
    }

    private function loadOtrasCaracteristicasCuatroVehiculo($claseId, $marcaId, $tipoId, $cilindradaId, $traccionId, $transmisionId, $combustibleId, $anioId, $paisId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctotrascaracteristicascuatrovehiculo/{$claseId}/{$marcaId}/{$tipoId}/{$cilindradaId}/{$traccionId}/{$transmisionId}/{$combustibleId}/{$anioId}/{$paisId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->otrasCaracteristicasVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->otrasCaracteristicasVehiculoOptions = [];
        }
    }

    private function loadOtrasCaracteristicasTresVehiculo($claseId, $marcaId, $cilindradaId, $traccionId, $transmisionId, $combustibleId, $anioId, $paisId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctotrascaracteristicastresvehiculo/{$claseId}/{$marcaId}/{$cilindradaId}/{$traccionId}/{$transmisionId}/{$combustibleId}/{$anioId}/{$paisId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->otrasCaracteristicasVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            $this->otrasCaracteristicasVehiculoOptions = [];
        }
    }

    public function updatedOtrasCaracteristicasVehiculo($value)
    {
        if ($value !== '' && $this->claseVehiculo) {
            if ($this->marcaVehiculo === 'NINGUNA') {
                $this->loadValorMercanciaDosVehiculo($this->claseVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $this->transmisionVehiculo, $this->combustibleVehiculo, $this->anioVehiculo, $this->paisVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->tipoVehiculo === 'NINGUNA') {
                $this->loadValorMercanciaTresVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $this->transmisionVehiculo, $this->combustibleVehiculo, $this->anioVehiculo, $this->paisVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->subtipoVehiculo === 'NINGUNA') {
                $this->loadValorMercanciaCuatroVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->tipoVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $this->transmisionVehiculo, $this->combustibleVehiculo, $this->anioVehiculo, $this->paisVehiculo, $value);
            } elseif ($this->marcaVehiculo && $this->tipoVehiculo && $this->subtipoVehiculo && $this->cilindradaVehiculo !== '' && $this->traccionVehiculo !== '' && $this->transmisionVehiculo !== '' && $this->combustibleVehiculo !== '' && $this->anioVehiculo !== '' && $this->paisVehiculo !== '') {
                $this->loadValorMercanciaVehiculo($this->claseVehiculo, $this->marcaVehiculo, $this->tipoVehiculo, $this->subtipoVehiculo, $this->cilindradaVehiculo, $this->traccionVehiculo, $this->transmisionVehiculo, $this->combustibleVehiculo, $this->anioVehiculo, $this->paisVehiculo, $value);
            }
        }
    }

    private function loadValorMercanciaDosVehiculo($claseId, $cilindradaId, $traccionId, $transmisionId, $combustibleId, $anioId, $paisId, $otrasId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/listavehiculodosconsulta/{$claseId}/{$cilindradaId}/{$traccionId}/{$transmisionId}/{$combustibleId}/{$anioId}/{$paisId}/{$otrasId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];

                $this->vehiculosEncontrados = $result;
                $this->selectedVehicleIndex = null;
                $this->valorMercanciaRoRo = 0;

                $maxValor = 0;
                foreach ($result as $item) {
                    if (isset($item['valorSus']) && $item['valorSus'] > $maxValor) {
                        $maxValor = $item['valorSus'];
                    }
                }
                if ($maxValor > 0) {
                    $this->valorMercanciaRoRo = $maxValor;
                }
            }
        } catch (\Exception $e) {
            Log::error("Error al cargar valor mercancía de vehículo (DOS): " . $e->getMessage());
            $this->vehiculosEncontrados = [];
        }
    }

    public function seleccionarVehiculo($index)
    {
        if (isset($this->vehiculosEncontrados[$index])) {
            $vehiculo = $this->vehiculosEncontrados[$index];
            $this->valorMercanciaRoRo = $vehiculo['valorSus'] ?? 0;
            // Construct a descriptive name
            $marca = $vehiculo['codigoMarcaDescripcion'] ?? '';
            $modelo = $vehiculo['codigoTipoDescripcion'] ?? '';
            $subtipo = $vehiculo['codigoSubtipoDescripcion'] ?? '';
            $this->nombreVehiculoRoRo = trim("$marca $modelo $subtipo");

            $this->selectedVehicleIndex = $index;
        }
    }

    private function loadValorMercanciaCuatroVehiculo($claseId, $marcaId, $tipoId, $cilindradaId, $traccionId, $transmisionId, $combustibleId, $anioId, $paisId, $otrasId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/listavehiculocuatroconsulta/{$claseId}/{$marcaId}/{$tipoId}/{$cilindradaId}/{$traccionId}/{$transmisionId}/{$combustibleId}/{$anioId}/{$paisId}/{$otrasId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];

                $this->vehiculosEncontrados = $result;
                $this->selectedVehicleIndex = null;
                $this->valorMercanciaRoRo = 0;

                $maxValor = 0;

                foreach ($result as $item) {
                    if (isset($item['valorSus']) && $item['valorSus'] > $maxValor) {
                        $maxValor = $item['valorSus'];
                    }
                }

                if ($maxValor > 0) {
                    $this->valorMercanciaRoRo = $maxValor;
                }
            }
        } catch (\Exception $e) {
            Log::error("Error al cargar valor mercancía de vehículo (CUATRO): " . $e->getMessage());
        }
    }

    private function loadValorMercanciaTresVehiculo($claseId, $marcaId, $cilindradaId, $traccionId, $transmisionId, $combustibleId, $anioId, $paisId, $otrasId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/listavehiculotresconsulta/{$claseId}/{$marcaId}/{$cilindradaId}/{$traccionId}/{$transmisionId}/{$combustibleId}/{$anioId}/{$paisId}/{$otrasId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];

                $this->vehiculosEncontrados = $result;
                $this->selectedVehicleIndex = null;
                $this->valorMercanciaRoRo = 0;

                $maxValor = 0;

                foreach ($result as $item) {
                    if (isset($item['valorSus']) && $item['valorSus'] > $maxValor) {
                        $maxValor = $item['valorSus'];
                    }
                }

                if ($maxValor > 0) {
                    $this->valorMercanciaRoRo = $maxValor;
                }
            }
        } catch (\Exception $e) {
            Log::error("Error al cargar valor mercancía de vehículo (TRES): " . $e->getMessage());
        }
    }

    private function loadValorMercanciaVehiculo($claseId, $marcaId, $tipoId, $subtipoId, $cilindradaId, $traccionId, $transmisionId, $combustibleId, $anioId, $paisId, $otrasId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/listavehiculoconsulta/{$claseId}/{$marcaId}/{$tipoId}/{$subtipoId}/{$cilindradaId}/{$traccionId}/{$transmisionId}/{$combustibleId}/{$anioId}/{$paisId}/{$otrasId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];

                $this->vehiculosEncontrados = $result;
                $this->selectedVehicleIndex = null;
                $this->valorMercanciaRoRo = 0;

                $maxValor = 0;

                foreach ($result as $item) {
                    if (isset($item['valorSus']) && $item['valorSus'] > $maxValor) {
                        $maxValor = $item['valorSus'];
                    }
                }

                if ($maxValor > 0) {
                    $this->valorMercanciaRoRo = $maxValor;
                }
            }
        } catch (\Exception $e) {
            Log::error("Error al cargar valor mercancía de vehículo: " . $e->getMessage());
        }
    }

    private function loadOtrasCaracteristicasVehiculo($claseId, $marcaId, $tipoId, $subtipoId, $cilindradaId, $traccionId, $transmisionId, $combustibleId, $anioId, $paisId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctotrascaracteristicasvehiculo/{$claseId}/{$marcaId}/{$tipoId}/{$subtipoId}/{$cilindradaId}/{$traccionId}/{$transmisionId}/{$combustibleId}/{$anioId}/{$paisId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->otrasCaracteristicasVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            Log::error("Error al cargar otras características de vehículo: " . $e->getMessage());
            $this->otrasCaracteristicasVehiculoOptions = [];
        }
    }

    private function loadAnioVehiculo($claseId, $marcaId, $tipoId, $subtipoId, $cilindradaId, $traccionId, $transmisionId, $combustibleId)
    {
        try {
            $response = Http::withoutVerifying()->get("https://anapp.aduana.gob.bo:8443/wsprbctp-consultas/vtm1/distinctaniomodelovehiculo/{$claseId}/{$marcaId}/{$tipoId}/{$subtipoId}/{$cilindradaId}/{$traccionId}/{$transmisionId}/{$combustibleId}");
            if ($response->successful()) {
                $result = $response->json()['result'] ?? [];
                $this->anioVehiculoOptions = $result;
            }
        } catch (\Exception $e) {
            Log::error("Error al cargar año de vehículo: " . $e->getMessage());
            $this->anioVehiculoOptions = [];
        }
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
                if (count($this->fclRates) > 0) {
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

            if (count($this->fclRates) === 0) {
                $this->statusMessage = 'No se pudieron obtener tarifas en tiempo real. Inténtalo más tarde.';
            } else {
                $this->statusMessage = 'Mostrando tarifas guardadas (conexión fallida).';
            }
        }
        $this->loadingRates = false;
    }

    // public function buscarTarifasFCL()
    // {

    //     $this->reset(['rates', 'loadingRates', 'statusMessage', 'fclRates', 'currentPage']);
    //     $this->currentRunId = null;


    //     if (empty($this->polCode) || empty($this->podCode)) {
    //         $this->statusMessage = 'Selecciona origen y destino';
    //         return;
    //     }

    //     $this->loadingRates = true;
    //     // $this->statusMessage = 'Conectando con el proveedor de tarifas'; // Old message
    //     $this->statusMessage = 'Cargando tarifas locales...';

    //     // $originCode = strtolower(substr($this->polCode, 0, 5));
    //     // $destCode   = strtolower(substr($this->podCode, 0, 5));
    //     // $url = "https://www.5688.com.cn/fcl/{$originCode}-{$destCode}";
    //     // Mock URL for internal tracking
    //     $url = "local://database/mockup/data.json";

    //     try {
    //         /*
    //          * REEMPLAZO PETICIÓN HTTP POR LECTURA DE ARCHIVO LOCAL
    //          */
    //         $jsonPath = base_path('database/mockup/data.json');

    //         if (!File::exists($jsonPath)) {
    //             throw new \Exception("El archivo de datos simulados no existe en: $jsonPath");
    //         }

    //         $jsonContent = File::get($jsonPath);
    //         $responseArray = json_decode($jsonContent, true);

    //         if (!$responseArray || !($responseArray['success'] ?? false)) {
    //             throw new \Exception('El archivo JSON local no tiene el formato esperado o success=false.');
    //         }

    //         // Extract rates from the mockup structure
    //         $data = $responseArray['data']['json']['rates'] ?? [];

    //         if (empty($data)) {
    //             $this->statusMessage = 'No se encontraron tarifas en el archivo local.';
    //             $this->fclRates = collect();
    //         } else {
    //             $this->fclRates = collect($data);

    //             // Guardamos en BD para mantener la lógica de caché/historial si se desea
    //             $this->guardarTarifasEnBaseDeDatos($url, $data);

    //             $this->statusMessage = '¡Tarifas actualizadas (Local)! Se encontraron ' . count($data) . ' opciones.';
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('Error Cargando FCL Local: ' . $e->getMessage());
    //         $this->statusMessage = 'Error cargando datos locales: ' . $e->getMessage();

    //         // Fallback a BD antigua si falla lo local (opcional)
    //         $this->cargarTarifasDesdeBaseDeDatos();
    //     }
    //     $this->loadingRates = false;
    // }

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
            'seguroCarga',
            'verificacionEmpresaDigital',
            'verificacionSustanciasPeligrosas',
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
