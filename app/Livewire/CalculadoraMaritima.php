<?php

namespace App\Livewire;

use App\Models\Rate;
use App\Models\Search;
use App\Models\ShippingLine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

#[Layout('layouts.app')]
class CalculadoraMaritima extends Component
{
    // Tipo de carga visible en la UI (LCL por defecto)
    public $tipoCarga = 'lcl';

    // Inputs generales
    #[Url]
    public $peso;

    #[Url(as: 'cbm')]
    public $volumen;

    #[Url]
    public $valorMercancia;

    #[Url]
    public $cantidad;

    #[Url]
    public $cbm_directo;

    #[Url]
    public $q;

    public function mount()
    {
        // Decodificar parámetro 'q' si existe
        if ($this->q) {
            try {
                $decoded = json_decode(base64_decode($this->q), true);
                if (is_array($decoded)) {
                    $this->peso = $decoded['peso'] ?? $this->peso;
                    $this->volumen = $decoded['cbm'] ?? $this->volumen;
                    $this->valorMercancia = $decoded['valorMercancia'] ?? $this->valorMercancia;
                    $this->cantidad = $decoded['cantidad'] ?? $this->cantidad;
                    $this->cbm_directo = $decoded['cbm_directo'] ?? $this->cbm_directo;
                }
            } catch (\Exception $e) {
                // Silenciosamente fallar si la decodificación falla
            }
        }

        if ($this->cbm_directo) {
            $this->metodoVolumen = 'cbm_directo';
        }
    }

    public $largo, $ancho, $alto;
    public $origen = '';
    public $destino = '';

    // Calculador de volumen LCL
    public $volumenCalculado = null;
    public $metodoVolumen = 'dimensiones';

    public $resultado = null;
    public $desglose = [];
    public $fecha; // Fecha de generación de cotización

    // Estado de interacción con precio
    public $mostrarPregunta = false;
    public $respuestaUsuario = null; // 'si' o 'no'

    // FCL: Búsqueda de puertos
    public $searchPOL = ''; // Puerto Origen búsqueda
    public $searchPOD = ''; // Puerto Destino búsqueda
    public $polCode = ''; // Código seleccionado
    public $podCode = ''; // Código seleccionado
    public $polSuggestions = [];
    public $podSuggestions = [];
    public $showPOLDropdown = false;
    public $showPODDropdown = false;

    // FCL: Resultados de cotizaciones
    public $fclRates = [];
    public $perPage = 5;
    public $currentPage = 1;
    public $loadingRates = false;
    public $message = null;
    public $rates = null;
    public $progress = null;
    public $currentRunId = null;

    public $selectedContainer = 'gp20';
    public $selectedRate = null;
    public $mostrarModal = false;

    public $freight = 51.29;
    public $importation = 8.58;
    public $total_import_costs = 47.32;
    public $insurance = 38.18;
    public $customs_clearance_cost = 17.09;
    public $logistics_expenses = 33.70;


    public $recojoAlmacen = false;
    public $destinoFinal = 'tarija'; // 'tarija' o 'otros'
    public $departamentoDestino = '';

    public $selectedRateIndex = null;

    // Detalle de cálculo para el PDF
    public $tipoCobroActual = '';
    public $unidadActual = '';
    public $valorFacturadoActual = 0;
    public $cbmFacturadoActual = 0;


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
                ['value' => 'la_paz', 'nombre' => 'La Paz'],
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
        // Forzar a 'otros' si es true (por el wire:model.live en Livewire 3)
        // O a 'tarija' si es false
        if ($value === true || $value === 'otros') {
            $this->destinoFinal = 'otros';
        } else {
            $this->destinoFinal = 'tarija';
            $this->departamentoDestino = ''; // Resetear departamento si se desmarca
        }
    }
    public function selectRate($index, $container)
    {
        // Obtener la tarifa seleccionada desde la colección
        $rate = $this->fclRates[$index]; // $rate es un array plano

        $this->mostrarPregunta = false;
        $this->respuestaUsuario = null;

        $this->selectedRate = $rate;
        $this->selectedContainer = $container;

        // Nombre legible del contenedor
        $containerName = match ($container) {
            'gp20' => "20' Standard",
            'gp40' => "40' Standard",
            'hq40' => "40' High Cube",
            default => "Contenedor",
        };

        // Precio base directamente del array plano
        $precioBase = $rate[$container] ?? 0;

        if ($precioBase <= 0) {
            session()->flash('error', 'Precio no disponible para este contenedor.');
            return;
        }

        // Naviera
        $shippingLine = $rate['shipping_line'] ?? 'Desconocida';

        // Construir desglose
        $this->desglose = [
            "Flete Marítimo ({$shippingLine} - {$containerName})" => $precioBase,
            'Manipulación en Terminal' => 159.00,
            'Gastos de Documentación' => 72.00,
            'DocumentaciónGastos Portuarios Varios' => 102.00,
            'Control de Equipo (EIR)' => 22.00,
            'Sello de Seguridad' => 10.00,
            'Despacho de Aduana' => 40.00,
            'Gasto por Liberación Digital' => 65.00
        ];

        // Información adicional (no numérica)
        $this->desglose['Tiempo de Tránsito'] = ($rate['transit_time'] ?? 'N/A') . ' días';
        $this->desglose['Válido hasta'] = $rate['valid_until'] ?? 'No especificado';
        $this->desglose['Cierre (cutoff)'] = ($rate['closing'] ?? 'N/A') . ' días';

        // Calcular total solo con valores numéricos
        $this->resultado = array_sum(array_filter($this->desglose, 'is_numeric'));

        $this->mostrarPregunta = true;
        $this->respuestaUsuario = null;

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
        // Si $this->recojoAlmacen es true, el costo es 26.91, si es false, es 0.
        return $this->recojoAlmacen ? 26.91 : 0.0;
    }

    private function getMapaCostosDestino(): array
    {
        return [
            // Zona Amazónica (Mayor costo)
            'beni' => ['costo' => 18.18, 'nombre' => 'Beni'],
            'pando' => ['costo' => 18.18, 'nombre' => 'Pando'],
            // Eje Central (Costo medio)
            'la_paz' => ['costo' => 18.18, 'nombre' => 'La Paz'],
            'cochabamba' => ['costo' => 18.18, 'nombre' => 'Cochabamba'],
            'santa_cruz' => ['costo' => 18.18, 'nombre' => 'Santa Cruz'],
            // Zona Sur (Costo estándar)
            'chuquisaca' => ['costo' => 18.18, 'nombre' => 'Chuquisaca'],
            'potosi' => ['costo' => 18.18, 'nombre' => 'Potosí'],
            'oruro' => ['costo' => 18.18, 'nombre' => 'Oruro'],
            'tarija' => ['costo' => 18.18, 'nombre' => 'Tarija'],
        ];
    }

    public function calcularCostoDestino(): array
    {
        // Solo calcula el costo si el destino es 'otros' y hay un departamento seleccionado.
        if ($this->destinoFinal !== 'otros' || empty($this->departamentoDestino)) {
            return ['costo' => 0.0, 'nombre' => ''];
        }

        $departamentos = $this->getMapaCostosDestino();
        $departamentoSeleccionado = $this->departamentoDestino;

        if (isset($departamentos[$departamentoSeleccionado])) {
            return $departamentos[$departamentoSeleccionado];
        }

        // Retorna valores predeterminados si el departamento no se encuentra
        return ['costo' => 0.0, 'nombre' => ''];
    }

    // =======================================================
    //              CALCULAR COTIZACIÓN PRINCIPAL
    // =======================================================
    public function calcular()
    {
        $this->validate([
            'peso' => 'nullable|numeric|min:0',
            'volumen' => 'nullable|numeric|min:0.01',
            'valorMercancia' => 'nullable|numeric|min:0',
        ]);

        // Reiniciar estado de pregunta
        $this->mostrarPregunta = false;
        $this->respuestaUsuario = null;

        $this->fecha = now()->format('d/m/Y H:i');

        $peso = floatval($this->peso);
        $largo = floatval($this->largo);
        $ancho = floatval($this->ancho);
        $alto = floatval($this->alto);
        $volumen = floatval($this->volumen);

        // Calculamos el peso volumétrico y el CBM solo si tenemos dimensiones completas
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
            Log::info('Peso1: ' . $this->peso . 'CBM1: ' . $CBM);
        } else {
            $shippingPackage = $this->calculateShippingPackage($this->peso, $CBM);
            Log::info('Peso2: ' . $this->peso . 'CBM2: ' . $CBM);
        }

        $this->resultado = (float) $shippingPackage['costo'];
        $this->tipoCobroActual = $shippingPackage['tipo'] ?? '';
        $this->unidadActual = $shippingPackage['unidad'] ?? '';
        $this->valorFacturadoActual = $shippingPackage['valor_facturado'] ?? 0;
        $this->cbmFacturadoActual = $shippingPackage['cbm_facturado'] ?? 0;

        $this->mostrarPregunta = true;

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
        // TARIFA POR PESO (cuando CBM < 0.1)
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

        // TARIFA POR M³ (cuando CBM ≥ 0.1)
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
            // COBRO POR CBM REAL
            $tipoCobro  = 'CBM';
            $valorUsado = $cbmReal;

            foreach ($TARIFA_POR_CBM as $tramo) {
                if ($cbmReal >= $tramo['min']) {
                    $costoFinal = $tramo['precio'];
                    break;
                }
            }
            // Si es menor a 0.25 → se cobra como 0.25
            if ($costoFinal === 0) {
                $costoFinal = 60;
            }
        }

        $costoRecojo = $this->costoRecojo;
        $resultadoDestino = $this->calcularCostoDestino();
        $costoDestino = $resultadoDestino['costo'];
        $nombreDestino = $resultadoDestino['nombre'];

        $valorFacturado = 0;
        $unidad = str_contains($tipoCobro, 'Peso') ? 'kg' : 'm³';
        if ($unidad == 'kg') {
            $valorFacturado = $costoFinal * $this->peso; // o $valorUsado si prefieres
        } else {
            $valorFacturado = $costoFinal * $this->cantidad;
        }

        $total_tiered_charge = $this->calculate_tiered_charge($this->valorMercancia);

        // =====================================================
        // NUEVO: DESGLOSE DETALLADO DEL FLETE MARÍTIMO POR CBM
        // =====================================================
        $desgloseFleteMaritimo = [];

        if ($tipoCobro === 'CBM') {
            // Porcentajes de distribución (ajústalos a tu realidad)
            $distribucion = [
                'grupo1' => 0.60,  // 60% → Costos principales de naviera
                'grupo2' => 0.25,  // 25% → Gastos operativos en origen
                'grupo3' => 0.15,  // 15% → Margen, comisiones y otros
            ];

            $grupo1 = $costoFinal * $distribucion['grupo1'];
            $grupo2 = $costoFinal * $distribucion['grupo2'];
            $grupo3 = $costoFinal * $distribucion['grupo3'];

            // Grupo 1: Costos Naviera y Documentación
            $desgloseFleteMaritimo['─ EJE LOGÍSTICO INTERNACIONAL'] = null;
            $desgloseFleteMaritimo['   ├─ Flete Marítimo (Puerto a Puerto)'] = number_format($grupo1 * 0.85, 2); // Aumentamos flete
            $desgloseFleteMaritimo['   ├─ Booking & Space Guarantee'] = number_format($grupo1 * 0.10, 2); // Suena a "asegurar espacio"
            $desgloseFleteMaritimo['   └─ Protección de Carga (Standard)'] = number_format($grupo1 * 0.05, 2); // En lugar de solo "seguro"
            $desgloseFleteMaritimo['   Subtotal Eje Logístico Internacional'] = number_format($grupo1, 2);

            // Grupo 2: Gastos Operativos en Origen
            $desgloseFleteMaritimo['─ GESTIÓN DE SALIDA (ORIGEN)'] = null;
            $desgloseFleteMaritimo['   ├─ Coordinación de Embarque & Handling'] = number_format($grupo2 * 0.50, 2);
            $desgloseFleteMaritimo['   ├─ Maniobras Portuarias (THC/Gate In)'] = number_format($grupo2 * 0.30, 2);
            $desgloseFleteMaritimo['   └─ Emisión Digital de Documentos (BL)'] = number_format($grupo2 * 0.20, 2);
            $desgloseFleteMaritimo['   Subtotal Gestion de Salida (Origen)'] = number_format($grupo2, 2);

            // Grupo 3: Margen y Gastos Adicionales
            $desgloseFleteMaritimo['─ SERVICIOS INTEGRALES Y SEGURIDAD'] = null;
            $desgloseFleteMaritimo['   ├─ Gestión Administrativa de Importación'] = number_format($grupo3 * 0.60, 2); // En lugar de "comisión"
            $desgloseFleteMaritimo['   ├─ Transferencia y Cobertura Cambiaria'] = number_format($grupo3 * 0.25, 2); // Suena a protección financiera
            $desgloseFleteMaritimo['   └─ Monitoreo y Soporte 24/7'] = number_format($grupo3 * 0.15, 2); // Valor percibido alto
            $desgloseFleteMaritimo['   Subtotal Servicios Integrales y Seguridad'] = number_format($grupo3, 2);
        }

        // =====================================================
        // CONSTRUCCIÓN FINAL DEL DESGLOSE
        // =====================================================
        $this->desglose = [
            'Valor de Mercancía' => number_format($this->valorMercancia, 2, '.', ''),
            'Costo de Envío de Paquete' => number_format($valorFacturado, 2, '.', ''),
        ];

        // Si es por CBM → insertamos el desglose detallado del flete
        if ($tipoCobro === 'CBM' && !empty($desgloseFleteMaritimo)) {
            $this->desglose = array_merge($this->desglose, $desgloseFleteMaritimo);
        } else {
            // Si es por peso, puedes poner un mensaje simple
            $this->desglose['Costo por Peso'] = number_format($valorFacturado, 2, '.', '');
            $this->desglose['Peso Facturado'] = ceil($pesoKg) . ' kg';
        }

        // Servicios adicionales
        $addServices = 0;
        if ($this->recojoAlmacen) {
            $this->desglose['Agencia Despachante'] = number_format($total_tiered_charge, 2, '.', '');
            $this->desglose['Recojo desde Almacén'] = number_format($costoRecojo * $valorUsado, 2, '.', '');
            $addServices = number_format($costoRecojo * $valorUsado, 2, '.', '') + number_format($total_tiered_charge, 2, '.', '');
        }

        if ($costoDestino > 0 && $nombreDestino) {
            $this->desglose["Entrega a " . $nombreDestino] = number_format($costoDestino, 2, '.', '');
        }

        // Total general
        $total = $this->valorMercancia + $valorFacturado + $addServices + $costoDestino;
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

        // CBM mínimo facturable
        $cbmFacturable = max($cbmReal, 0.25);

        // Buscar tarifa por tramo (de mayor a menor)
        $precioPorCbm = 60; // mínimo por defecto
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

        // Porcentajes de distribución del costo unitario por CBM
        $distribucion = [
            'grupo1' => 0.60,  // 60% → Costos principales naviera
            'grupo2' => 0.25,  // 25% → Operativos en origen
            'grupo3' => 0.15,  // 15% → Margen y adicionales
        ];

        $grupo1 = $precioPorCbm * $distribucion['grupo1'];
        $grupo2 = $precioPorCbm * $distribucion['grupo2'];
        $grupo3 = $precioPorCbm * $distribucion['grupo3'];

        // Grupo 1: Costos Naviera y Documentación
        $desgloseFleteMaritimo['─ Grupo 1: Costos Naviera y Documentación'] = null;
        $desgloseFleteMaritimo['   ├─ Flete Marítimo Principal (Naviera)'] = number_format($grupo1 * 0.75, 2);
        $desgloseFleteMaritimo['   ├─ MBL y Documentación Oficial'] = number_format($grupo1 * 0.15, 2);
        $desgloseFleteMaritimo['   └─ Seguro de Flete Básico'] = number_format($grupo1 * 0.10, 2);
        $desgloseFleteMaritimo['   Subtotal Grupo 1'] = number_format($grupo1, 2);

        // Grupo 2: Gastos Operativos en Origen
        $desgloseFleteMaritimo['─ Grupo 2: Gastos Operativos en Origen'] = null;
        $desgloseFleteMaritimo['   ├─ Handling, Gate In y THC'] = number_format($grupo2 * 0.40, 2);
        $desgloseFleteMaritimo['   ├─ Comisión Giro China'] = number_format($grupo2 * 0.30, 2);
        $desgloseFleteMaritimo['   └─ BL Fee y Otros Operativos'] = number_format($grupo2 * 0.30, 2);
        $desgloseFleteMaritimo['   Subtotal Grupo 2'] = number_format($grupo2, 2);

        // Grupo 3: Margen y Gastos Adicionales
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

        // Insertamos el desglose detallado del flete
        $this->desglose = array_merge($this->desglose, $desgloseFleteMaritimo);

        // Servicios adicionales
        $costoRecojo = $this->costoRecojo;
        $resultadoDestino = $this->calcularCostoDestino();
        $costoDestino = $resultadoDestino['costo'];
        $nombreDestino = $resultadoDestino['nombre'];

        $total_tiered_charge = $this->calculate_tiered_charge($this->valorMercancia);
        $additional_services = 0;
        if ($this->recojoAlmacen) {
            $this->desglose['Agencia Despachante'] = number_format($total_tiered_charge, 2, '.', '');
            $this->desglose['Recojo desde Almacén'] = number_format($costoRecojo * $cbmReal, 2, '.', ''); // ajustado por cantidad
            $additional_services = $costoRecojo + $total_tiered_charge;
        }

        if ($costoDestino > 0 && $nombreDestino) {
            $this->desglose["Entrega a " . $nombreDestino] = number_format($costoDestino, 2, '.', '');
        }

        // Separador y total final
        $total = $this->valorMercancia + $costoFleteTotal + $additional_services + $costoDestino;

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

    /**
     * Responder a la pregunta del precio
     */
    public function responder($respuesta)
    {
        $this->respuestaUsuario = $respuesta;
    }

    /**
     * Generar PDF de cotización
     */
    public function descargarPDF()
    {
        return redirect()->route('cotizacion.pdf', [
            'tipoCarga' => $this->tipoCarga,
            'peso' => $this->peso,
            'volumen' => $this->volumen,
            'origen' => $this->origen,
            'destino' => $this->destino,
            'valorMercancia' => $this->valorMercancia,
            'resultado' => $this->resultado,
            'desglose' => json_encode($this->desglose),
            'tipoCobro' => $this->tipoCobroActual,
            'unidad' => $this->unidadActual,
            'valorFacturado' => $this->valorFacturadoActual,
            'cbmFacturado' => $this->cbmFacturadoActual,
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
        // Si ya hay un código seleccionado y coincide, no buscar
        if ($this->polCode && str_starts_with($value, $this->polCode . ' - ')) {
            return;
        }

        // Si el usuario borra el texto seleccionado, limpiar el código
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
        // Si ya hay un código seleccionado y coincide, no buscar
        if ($this->podCode && str_starts_with($value, $this->podCode . ' - ')) {
            return;
        }

        // Si el usuario borra el texto seleccionado, limpiar el código
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

        // Forzar la actualización del componente Livewire
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

        // Forzar la actualización del componente Livewire
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
        // 1. Limpiar estado anterior
        $this->reset(['rates', 'loadingRates', 'message', 'fclRates', 'currentPage']);
        $this->currentRunId = null;

        // 2. Validar puertos
        if (empty($this->polCode) || empty($this->podCode)) {
            $this->message = 'Selecciona origen y destino';
            return;
        }

        $this->loadingRates = true;
        $this->message = 'Conectando con el proveedor de tarifas';

        // Construir la URL dinámica como usa 5688.com.cn
        // Ejemplo: cnszn-clval → CNSZN = Shenzhen, CLVAL = Valparaiso
        $originCode = strtolower(substr($this->polCode, 0, 5)); // ej: CNSZN
        $destCode   = strtolower(substr($this->podCode, 0, 5));  // ej: CLVAL
        $url = "https://www.5688.com.cn/fcl/{$originCode}-{$destCode}";
        Log::info("URL construida: {$url}");

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
                                            'closing'       => ['type' => ['string', 'null']],
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
                $this->message = 'No se encontraron tarifas en tiempo real para esta ruta. Intentando recuperar historial...';
                $this->cargarTarifasDesdeBaseDeDatos();

                if ($this->fclRates->isNotEmpty()) {
                    $this->message = 'No hay tarifas en tiempo real nuevas. Mostrando tarifas guardadas anteriormente.';
                } else {
                    $this->message = 'No se encontraron tarifas para esta ruta en este momento.';
                }
            } else {
                // Convertir a colección para usar en la vista
                $this->fclRates = collect($data);

                // Guardar en base de datos para caché futura
                $this->guardarTarifasEnBaseDeDatos($url, $data);

                $this->message = '¡Tarifas actualizadas! Se encontraron ' . count($data) . ' opciones.';
            }
        } catch (\Exception $e) {
            Log::error('Error Firecrawl FCL: ' . $e->getMessage(), [
                'url' => $url,
                'pol' => $this->polCode,
                'pod' => $this->podCode,
            ]);

            // Fallback: intentar cargar desde base de datos si hay caché
            $this->cargarTarifasDesdeBaseDeDatos();

            if ($this->fclRates->isEmpty()) {
                $this->message = 'No se pudieron obtener tarifas en tiempo real. Inténtalo más tarde.';
            } else {
                $this->message = 'Mostrando tarifas guardadas (conexión fallida).';
            }
        }
        $this->loadingRates = false;
    }
    private function guardarTarifasEnBaseDeDatos($url, $rates)
    {
        try {
            // Crear o actualizar la búsqueda
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

            // Limpiar tarifas anteriores de esta búsqueda (opcional)
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
        // Reutiliza la lógica que ya teníamos antes
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
        // $payload llega serializado desde el stream (array con searchedAt, rates, best, etc)
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
        $this->reset(['peso', 'volumen', 'cantidad', 'largo', 'ancho', 'alto', 'valorMercancia', 'resultado', 'desglose', 'mostrarPregunta', 'respuestaUsuario']);

        // Limpiar también datos FCL
        if ($this->tipoCarga === 'fcl') {
            $this->reset(['searchPOL', 'searchPOD', 'polCode', 'podCode', 'fclRates', 'polSuggestions', 'podSuggestions', 'showPOLDropdown', 'showPODDropdown']);
        }
        if ($this->tipoCarga === 'lcl') {
            $this->recojoAlmacen = false;
            $this->destinoFinal = 'tarija';
            $this->departamentoDestino = '';
        }
    }

    public function render()
    {
        return view('livewire.calculadora-maritima');
    }
}
