<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CalculadoraMaritima extends Component
{
    // Tipo de carga visible en la UI (LCL por defecto)
    public $tipoCarga = 'lcl';

    // Inputs generales
    public $peso, $volumen, $valorMercancia, $cantidad;
    public $largo, $ancho, $alto;
    public $origen = '';
    public $destino = '';

    // Calculador de volumen LCL
    public $volumenCalculado = null;
    public $metodoVolumen = 'cbm_directo';

    // Resultado global
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


    public function seleccionarPrecio($tipo)
    {
        $this->selectedContainer = $tipo;
        $this->mostrarModal = true;
    }

    public function cerrarModal()
    {
        $this->mostrarModal = false;
        $this->selectedContainer = null;
    }


    protected $listeners = [
        'fcl-rates-ready' => 'handleRatesReady',
        'fcl-heartbeat'   => 'handleHeartbeat',
        'fcl-error'       => 'handleError',

    ];
    public function selectRate($data)
    {
        $this->mostrarPregunta = false;
        $this->respuestaUsuario = null;
        $rate = $data['rate'];
        $container = $data['container']; // 'gp20', 'gp40' o 'hq40'

        $this->selectedRate = $rate;
        $this->selectedContainer = $container;

        // Nombre legible del contenedor
        $containerName = match ($container) {
            'gp20' => "20' Standard",
            'gp40' => "40' Standard",
            'hq40' => "40' High Cube",
        };

        // Precio base según el contenedor elegido
        $precioBase = $rate['price'][$container];

        // Construyes el desglose
        $this->desglose = [
            "Flete Marítimo ({$rate['shippingLine']} - {$containerName})" => $precioBase,
            'Gastos en Origen' => 300.00,     // ajusta según tu lógica
            'Gastos en Destino' => 400.00,
            'Documentación' => 150.00,
            'Seguro (0.3%)' => round($precioBase * 0.003, 2),
        ];

        // Añades info adicional no numérica
        $this->desglose['Tiempo de Tránsito'] = $rate['transitTime'] . ' días';
        $this->desglose['Válido hasta'] = $rate['validUntil'];

        // Calculas el total (solo valores numéricos)
        $this->resultado = array_sum(array_filter($this->desglose, 'is_numeric'));

        $this->mostrarPregunta = true;
        $this->respuestaUsuario = null;
        session()->flash('success', 'Cálculo completado exitosamente.');
    }
    public function costs_import()
    {
        $freight = $this->freight;
        $importation = $this->importation;
        $total_import_costs = $this->total_import_costs;
        $insurance = $this->insurance;
        $customs_clearance_cost = $this->customs_clearance_cost;
        $logistics_expenses = $this->logistics_expenses;

        $total_costs_import = $freight + $importation + $total_import_costs + $insurance + $customs_clearance_cost + $logistics_expenses;
        return $total_costs_import;
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

        $volumetricWeight = 0;
        if (empty($largo) && empty($ancho) && empty($alto)) {
            $volumetricWeight = $volumen;
        } else {
            $volumetricWeight = (($largo * $ancho * $alto) / 5000);
        }

        if (empty($largo) && empty($ancho) && empty($alto)) {
            $CBM = $this->volumen;
        } else {
            $CBM = (($largo * $ancho * $alto) / 1000000);
        }

        $shippingPackage = 0;

        if (empty($this->peso) || $this->peso <= 0) {
            $shippingPackage =  $this->calculateShippingPackagePerDimensions($CBM);
        } elseif (empty($this->alto) || empty($this->largo) || empty($this->ancho)) {
            $shippingPackage = $this->calculateShippingPackage($volumetricWeight, $volumen);
        } else {
            $shippingPackage = $this->calculateShippingPackage($volumetricWeight, $CBM);
        }

        $this->resultado = (float) $shippingPackage['costo'];
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
        // TARIFA POR PESO (cuando CBM < 0.01)
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

        // TARIFA POR M³ (cuando CBM ≥ 0.01)
        $TARIFA_POR_CBM = [
            20   => 129,
            15   => 138,
            11   => 149,
            8    => 159,
            5    => 168,
            3    => 179,
            1    => 188,
            0.5  => 116,
            0.25 => 60
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

            // Ordenamos las claves de mayor a menor
            $claves = array_keys($TARIFA_POR_CBM);
            usort($claves, fn($a, $b) => $b <=> $a);

            foreach ($claves as $limite) {
                if ($cbmReal >= $limite) {
                    $costoFinal = $TARIFA_POR_CBM[$limite];
                    break;
                }
            }
            // Si es menor a 0.25 → se cobra como 0.25
            if ($costoFinal === 0) {
                $costoFinal = 60;
            }
        }

        $costoRecojo = $this->costoRecojo;

        // Calcular el costo de destino usando la nueva función
        $resultadoDestino = $this->calcularCostoDestino();
        $costoDestino = $resultadoDestino['costo'];
        $nombreDestino = $resultadoDestino['nombre'];

        $valorFacturado = 0;
        $unidad = str_contains($tipoCobro, 'Peso') ? 'kg' : 'm³';
        if ($unidad == 'kg') {
            $valorFacturado = $costoFinal;
        } else {
            $valorFacturado = $costoFinal * $this->cantidad;
        }
        
        $total_costs_import = $this->costs_import();
        $total_tiered_charge = $this->calculate_tiered_charge($this->valorMercancia);
        $total = $this->valorMercancia + $valorFacturado + $total_costs_import + $total_tiered_charge + $costoRecojo + $costoDestino;
        $this->desglose = [
            'Costo por paquetey (' . number_format($valorUsado, 3, '.', '') . ' ' . $tipoCobro . ' )' => number_format($valorFacturado, 2, '.', ''),
            'Costos de Flete Marítimo' => 51.29,
            'Cargos de Importación Local' => 8.58,
            'Costos Totales de Importación' => 47.32,
            'Seguro' => 38.18,
            'Costo de Despacho' => 17.09,
            'Gastos Logísticos Integrales' => 33.70,
            'Agencia Despachanche' => $total_tiered_charge
        ];
        // Agregar servicios adicionales al desglose si aplican
        if ($this->recojoAlmacen) {
            $this->desglose["Recojo desde Almacén"] = $costoRecojo;
        }

        if ($costoDestino > 0 && $nombreDestino) {
            $this->desglose["Entrega a " . $nombreDestino] = $costoDestino;
        }

        return [
            'costo'      => number_format($total, 2, '.', ''),
            'tipo'       => $tipoCobro,
            'unidad'     => str_contains($tipoCobro, 'Peso') ? 'kg' : 'm³'
        ];
    }

    /**
     * Calcula el costo del envío SOLO por CBM (volumen real)
     * Ideal para LCL cuando ya tienes el volumen en m³
     */
    private function calculateShippingPackagePerDimensions(float $cbmReal): array
    {
        $TARIFA_POR_CBM = [
            20   => 129,
            15   => 138,
            11   => 149,
            8    => 159,
            5    => 168,
            3    => 179,
            1    => 188,
            0.5  => 116,
            0.25 => 60,
        ];

        $cbmFacturable = max($cbmReal, 0.25);

        $precioPorCbm = 60;

        foreach ($TARIFA_POR_CBM as $min => $precio) {
            if ($cbmFacturable >= $min) {
                $precioPorCbm = $precio;
                break;
            }
        }
        /**
         * Gastos extra
         */
        $costoRecojo = $this->costoRecojo;

        // Calcular el costo de destino usando la nueva función
        $resultadoDestino = $this->calcularCostoDestino();
        $costoDestino = $resultadoDestino['costo'];
        $nombreDestino = $resultadoDestino['nombre'];

        $total_costs_import = $this->costs_import();
        $total_tiered_charge = $this->calculate_tiered_charge($this->valorMercancia) + $costoRecojo + $costoDestino;;
        $this->desglose = [
            'Costo por paquetex (' . number_format($cbmFacturable, 3, '.', '') . '  m³  )' => number_format($precioPorCbm * $this->cantidad, 2, '.', ''),
            'Costos de Flete Marítimo' => 51.29,
            'Cargos de Importación Local' => 8.58,
            'Costos Totales de Importación' => 47.32,
            'Seguro' => 38.18,
            'Costo de Despacho' => 17.09,
            'Gastos Logísticos Integrales' => 33.70,
            'Agencia Despachanche' => $total_tiered_charge
        ];

        // Agregar servicios adicionales al desglose si aplican
        if ($this->recojoAlmacen) {
            $this->desglose["Recojo desde Almacén"] = $costoRecojo;
        }

        if ($costoDestino > 0 && $nombreDestino) {
            $this->desglose["Entrega a " . $nombreDestino] = $costoDestino;
        }

        $total =  $this->valorMercancia + ($this->cantidad * $precioPorCbm) + $total_costs_import + $total_tiered_charge;
        return [
            'costo' =>  number_format($total, 2, '.', ''),
            'tipo'  => 'CBM'
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

        $this->polSuggestions = $this->searchPorts($value);
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

        $this->podSuggestions = $this->searchPorts($value);
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

    /**
     * Búsqueda de puertos (lista hardcoded común)
     * TODO: Reemplazar con API real o base de datos
     */
    private function searchPorts($query)
    {
        $ports = [
            // Sudeste asiático (China, Singapur, etc.)
            ['code' => 'CNSZN', 'name' => 'Shen Zhen', 'country' => 'China', 'region' => 'Sudeste asiático'],
            ['code' => 'CNGUA', 'name' => 'Guang Zhou', 'country' => 'China', 'region' => 'Sudeste asiático'],
            ['code' => 'CNSHA', 'name' => 'Shang Hai', 'country' => 'China', 'region' => 'Sudeste asiático'],
            ['code' => 'HKHKG', 'name' => 'Hong Kong', 'country' => 'Hong Kong', 'region' => 'Sudeste asiático'],
            ['code' => 'CNXMN', 'name' => 'Xia Men', 'country' => 'China', 'region' => 'Sudeste asiático'],
            ['code' => 'CNNBO', 'name' => 'Ning Bo', 'country' => 'China', 'region' => 'Sudeste asiático'],
            ['code' => 'CNQIN', 'name' => 'Qing Dao', 'country' => 'China', 'region' => 'Sudeste asiático'],
            ['code' => 'CNTJN', 'name' => 'Tian Jin', 'country' => 'China', 'region' => 'Sudeste asiático'],
            ['code' => 'SGSGP', 'name' => 'Singapore', 'country' => 'Singapore', 'region' => 'Sudeste asiático'],

            // Japón, Corea del Sur y Taiwán
            ['code' => 'JPTOK', 'name' => 'Tokyo', 'country' => 'Japón', 'region' => 'Japón, Corea del Sur y Taiwán'],
            ['code' => 'JPOSK', 'name' => 'Osaka', 'country' => 'Japón', 'region' => 'Japón, Corea del Sur y Taiwán'],
            ['code' => 'JPYOK', 'name' => 'Yokohama', 'country' => 'Japón', 'region' => 'Japón, Corea del Sur y Taiwán'],
            ['code' => 'JPKOB', 'name' => 'Kobe', 'country' => 'Japón', 'region' => 'Japón, Corea del Sur y Taiwán'],
            ['code' => 'KRBUS', 'name' => 'Busan', 'country' => 'Corea del Sur', 'region' => 'Japón, Corea del Sur y Taiwán'],
            ['code' => 'KRINC', 'name' => 'Inchon', 'country' => 'Corea del Sur', 'region' => 'Japón, Corea del Sur y Taiwán'],
            ['code' => 'TWKAO', 'name' => 'Kaohsiung', 'country' => 'Taiwán', 'region' => 'Japón, Corea del Sur y Taiwán'],
            ['code' => 'TWKEE', 'name' => 'Keelung', 'country' => 'Taiwán', 'region' => 'Japón, Corea del Sur y Taiwán'],

            // Australia
            ['code' => 'AUSYD', 'name' => 'Sydney', 'country' => 'Australia', 'region' => 'Australia'],
            ['code' => 'AUMEL', 'name' => 'Melbourne', 'country' => 'Australia', 'region' => 'Australia'],
            ['code' => 'AUBRI', 'name' => 'Brisbane', 'country' => 'Australia', 'region' => 'Australia'],
            ['code' => 'AUFRE', 'name' => 'Fremantle', 'country' => 'Australia', 'region' => 'Australia'],
            ['code' => 'AUADE', 'name' => 'Adelaida', 'country' => 'Australia', 'region' => 'Australia'],
            ['code' => 'NZAUC', 'name' => 'Auckland', 'country' => 'Nueva Zelanda', 'region' => 'Australia'],
            ['code' => 'NZLYT', 'name' => 'Lyttelton', 'country' => 'Nueva Zelanda', 'region' => 'Australia'],
            ['code' => 'NZTAU', 'name' => 'Tauranga', 'country' => 'Nueva Zelanda', 'region' => 'Australia'],

            // Europa
            ['code' => 'GBFEL', 'name' => 'Felixstowe', 'country' => 'Reino Unido', 'region' => 'Europa'],
            ['code' => 'FRLEH', 'name' => 'Le Havre', 'country' => 'Francia', 'region' => 'Europa'],
            ['code' => 'DEHAM', 'name' => 'Hamburg', 'country' => 'Alemania', 'region' => 'Europa'],
            ['code' => 'NLROT', 'name' => 'Rotterdam', 'country' => 'Países Bajos', 'region' => 'Europa'],
            ['code' => 'BEANT', 'name' => 'Antwerp', 'country' => 'Bélgica', 'region' => 'Europa'],
            ['code' => 'GBSOU', 'name' => 'Southampton', 'country' => 'Reino Unido', 'region' => 'Europa'],
            ['code' => 'BEZEE', 'name' => 'Zeebrugge', 'country' => 'Bélgica', 'region' => 'Europa'],
            ['code' => 'RULED', 'name' => 'St.Petersburg', 'country' => 'Rusia', 'region' => 'Europa'],
            ['code' => 'NOOSL', 'name' => 'Oslo', 'country' => 'Noruega', 'region' => 'Europa'],

            // América
            ['code' => 'USLGB', 'name' => 'Long Beach', 'country' => 'Estados Unidos', 'region' => 'América'],
            ['code' => 'USLAX', 'name' => 'Los Angeles', 'country' => 'Estados Unidos', 'region' => 'América'],
            ['code' => 'USNYC', 'name' => 'New York', 'country' => 'Estados Unidos', 'region' => 'América'],
            ['code' => 'USSEA', 'name' => 'Seattle', 'country' => 'Estados Unidos', 'region' => 'América'],
            ['code' => 'USOAK', 'name' => 'Oakland', 'country' => 'Estados Unidos', 'region' => 'América'],
            ['code' => 'USTAC', 'name' => 'Tacoma', 'country' => 'Estados Unidos', 'region' => 'América'],
            ['code' => 'USNFK', 'name' => 'Norfolk', 'country' => 'Estados Unidos', 'region' => 'América'],
            ['code' => 'USSAV', 'name' => 'Savannah', 'country' => 'Estados Unidos', 'region' => 'América'],
            ['code' => 'USMIA', 'name' => 'Miami', 'country' => 'Estados Unidos', 'region' => 'América'],
            ['code' => 'USCHA', 'name' => 'Charleston', 'country' => 'Estados Unidos', 'region' => 'América'],
            ['code' => 'CAVCR', 'name' => 'Vancouver', 'country' => 'Canadá', 'region' => 'América'],
            ['code' => 'CATOR', 'name' => 'Toronto', 'country' => 'Canadá', 'region' => 'América'],
            ['code' => 'CAMTL', 'name' => 'Montreal', 'country' => 'Canadá', 'region' => 'América'],

            // El Mar Negro en el Mediterráneo
            ['code' => 'ITGOA', 'name' => 'Genova', 'country' => 'Italia', 'region' => 'El Mar Negro en el Mediterráneo'],
            ['code' => 'ITNAP', 'name' => 'Napoles', 'country' => 'Italia', 'region' => 'El Mar Negro en el Mediterráneo'],
            ['code' => 'ESBCN', 'name' => 'Barcelona', 'country' => 'España', 'region' => 'El Mar Negro en el Mediterráneo'],
            ['code' => 'ESVAL', 'name' => 'Valencia', 'country' => 'España', 'region' => 'El Mar Negro en el Mediterráneo'],
            ['code' => 'FRMRS', 'name' => 'Marsella', 'country' => 'Francia', 'region' => 'El Mar Negro en el Mediterráneo'],
            ['code' => 'ROCON', 'name' => 'Constanza', 'country' => 'Rumania', 'region' => 'El Mar Negro en el Mediterráneo'],
            ['code' => 'SIKOP', 'name' => 'Koper', 'country' => 'Eslovenia', 'region' => 'El Mar Negro en el Mediterráneo'],
            ['code' => 'UAODE', 'name' => 'Odessa', 'country' => 'Ucrania', 'region' => 'El Mar Negro en el Mediterráneo'],
            ['code' => 'TRIST', 'name' => 'Estambul', 'country' => 'Turquía', 'region' => 'El Mar Negro en el Mediterráneo'],
            ['code' => 'HRRIJ', 'name' => 'Rijeka', 'country' => 'Croacia', 'region' => 'El Mar Negro en el Mediterráneo'],

            // Oriente Medio
            ['code' => 'AEJEA', 'name' => 'Jebel Ali', 'country' => 'Emiratos Árabes', 'region' => 'Oriente Medio'],
            ['code' => 'BHBAH', 'name' => 'Bahrein', 'country' => 'Bahrein', 'region' => 'Oriente Medio'],
            ['code' => 'IRBND', 'name' => 'Abbas', 'country' => 'Irán', 'region' => 'Oriente Medio'],
            ['code' => 'KWKUW', 'name' => 'Kuwait', 'country' => 'Kuwait', 'region' => 'Oriente Medio'],
            ['code' => 'SAJED', 'name' => 'Jeddah', 'country' => 'Arabia Saudita', 'region' => 'Oriente Medio'],
            ['code' => 'SADAM', 'name' => 'Damasco', 'country' => 'Arabia Saudita', 'region' => 'Oriente Medio'],
            ['code' => 'SDPSU', 'name' => 'Port Sudan', 'country' => 'Sudán', 'region' => 'Oriente Medio'],
            ['code' => 'JOAQJ', 'name' => 'Aqaba', 'country' => 'Jordania', 'region' => 'Oriente Medio'],
            ['code' => 'EGPSD', 'name' => 'Port Said', 'country' => 'Egipto', 'region' => 'Oriente Medio'],
            ['code' => 'ILHFA', 'name' => 'Haifa', 'country' => 'Israel', 'region' => 'Oriente Medio'],
            ['code' => 'LBBEY', 'name' => 'Beirut', 'country' => 'Líbano', 'region' => 'Oriente Medio'],
            ['code' => 'SYLAT', 'name' => 'Latakia', 'country' => 'Siria', 'region' => 'Oriente Medio'],

            // India y Pakistán
            ['code' => 'LKCMB', 'name' => 'Colombo', 'country' => 'Sri Lanka', 'region' => 'India y Pakistán'],
            ['code' => 'INNSA', 'name' => 'Nhava Sheva', 'country' => 'India', 'region' => 'India y Pakistán'],
            ['code' => 'INDEL', 'name' => 'New Delhi', 'country' => 'India', 'region' => 'India y Pakistán'],
            ['code' => 'INMAA', 'name' => 'Chennai', 'country' => 'India', 'region' => 'India y Pakistán'],
            ['code' => 'INMUN', 'name' => 'Mundra', 'country' => 'India', 'region' => 'India y Pakistán'],
            ['code' => 'INBLR', 'name' => 'Bangalore', 'country' => 'India', 'region' => 'India y Pakistán'],
            ['code' => 'INCCU', 'name' => 'Calcutta', 'country' => 'India', 'region' => 'India y Pakistán'],
            ['code' => 'BDCGP', 'name' => 'Chittagong', 'country' => 'Bangladesh', 'region' => 'India y Pakistán'],
            ['code' => 'PKKHI', 'name' => 'Karachi', 'country' => 'Pakistán', 'region' => 'India y Pakistán'],

            // África
            ['code' => 'KEMBA', 'name' => 'Mombasa', 'country' => 'Kenia', 'region' => 'África'],
            ['code' => 'MZMPM', 'name' => 'Maputo', 'country' => 'Mozambique', 'region' => 'África'],
            ['code' => 'TZDAR', 'name' => 'Dar es Salaam', 'country' => 'Tanzania', 'region' => 'África'],
            ['code' => 'ZADUR', 'name' => 'Durban', 'country' => 'Sudáfrica', 'region' => 'África'],
            ['code' => 'ZACPT', 'name' => 'Ciudad del Cabo', 'country' => 'Sudáfrica', 'region' => 'África'],
            ['code' => 'BJCOO', 'name' => 'Cotonu', 'country' => 'Benin', 'region' => 'África'],
            ['code' => 'GHTEM', 'name' => 'Tema', 'country' => 'Ghana', 'region' => 'África'],
            ['code' => 'NGLOS', 'name' => 'Lagos', 'country' => 'Nigeria', 'region' => 'África'],
            ['code' => 'TGLFM', 'name' => 'Lome', 'country' => 'Togo', 'region' => 'África'],
            ['code' => 'MACAS', 'name' => 'Casablanca', 'country' => 'Marruecos', 'region' => 'África'],
            ['code' => 'TNTUN', 'name' => 'Tunez', 'country' => 'Túnez', 'region' => 'África'],
            ['code' => 'DZALG', 'name' => 'Algiers', 'country' => 'Argelia', 'region' => 'África'],

            // América Central y del Sur
            ['code' => 'ARBUE', 'name' => 'Buenos Aires', 'country' => 'Argentina', 'region' => 'América Central y del Sur'],
            ['code' => 'UYMVD', 'name' => 'Montevideo', 'country' => 'Uruguay', 'region' => 'América Central y del Sur'],
            ['code' => 'BRSSZ', 'name' => 'Santos', 'country' => 'Brasil', 'region' => 'América Central y del Sur'],
            ['code' => 'BRRIG', 'name' => 'Río Grande', 'country' => 'Brasil', 'region' => 'América Central y del Sur'],
            ['code' => 'BRRIO', 'name' => 'Río de Janeiro', 'country' => 'Brasil', 'region' => 'América Central y del Sur'],
            ['code' => 'PYASU', 'name' => 'Asuncion', 'country' => 'Paraguay', 'region' => 'América Central y del Sur'],
            ['code' => 'COBUN', 'name' => 'Buenaventura', 'country' => 'Colombia', 'region' => 'América Central y del Sur'],
            ['code' => 'PECLL', 'name' => 'Callao', 'country' => 'Perú', 'region' => 'América Central y del Sur'],
            ['code' => 'ECGYE', 'name' => 'Guayaquil', 'country' => 'Ecuador', 'region' => 'América Central y del Sur'],
            ['code' => 'CLIQQ', 'name' => 'Iquique', 'country' => 'Chile', 'region' => 'América Central y del Sur'],
            ['code' => 'CLVAL', 'name' => 'Valparaiso', 'country' => 'Chile', 'region' => 'América Central y del Sur'],
            ['code' => 'CLSAN', 'name' => 'San Antonio', 'country' => 'Chile', 'region' => 'América Central y del Sur'],
            ['code' => 'MXZLO', 'name' => 'Manzanillo', 'country' => 'México', 'region' => 'América Central y del Sur'],
            ['code' => 'PAPTY', 'name' => 'Panama City', 'country' => 'Panamá', 'region' => 'América Central y del Sur'],
            ['code' => 'MXGDL', 'name' => 'Guadalajara', 'country' => 'México', 'region' => 'América Central y del Sur'],
            ['code' => 'PRSJU', 'name' => 'San Juan', 'country' => 'Puerto Rico', 'region' => 'América Central y del Sur'],
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
        $this->reset(['rates', 'loadingRates', 'message', 'fclRates']);
        $this->currentRunId = null;

        // 2. Validar que tengamos puertos
        if (empty($this->polCode) || empty($this->podCode)) {
            $this->message = 'Selecciona origen y destino';
            return;
        }

        $this->loadingRates = true;
        $this->message = 'Iniciando búsqueda en tiempo real...';
        $this->rates = null;
        $this->progress = null;

        try {
            // Creamos una request "falsa" pero válida
            $fakeRequest = new \Illuminate\Http\Request();
            $fakeRequest->setMethod('POST');
            $fakeRequest->request->add([
                'polCode'       => $this->polCode,
                'podCode'       => $this->podCode,
                'transportType' => 'FCL',
                'maxRetries'    => 2,
            ]);

            // Instanciamos el controlador y llamamos directamente al método scrape()
            $controller = new \App\Http\Controllers\TransportController();
            $response = $controller->scrape($fakeRequest);

            Log::info('Respuesta del controlador scrape:', [
                'status'  => $response->getStatusCode(),
                'content' => $response->getContent(),           // string JSON
                'data'    => $response->getData(true),          // ya convertido a array
            ]);


            // $response es un JsonResponse
            $data = $response->getData(true); // devuelve array

            if (isset($data['runId'])) {
                // Apify devolvió el run correctamente
                $this->currentRunId = $data['runId'];

                // Le decimos al frontend que abra el SSE
                $this->dispatch('start-sse', runId: $data['runId']);

                $this->message = 'Buscando tarifas... (puede tardar 30–90 segundos)';
            } else {
                throw new \Exception($data['message'] ?? 'Respuesta inesperada de Apify');
            }
        } catch (\Exception $e) {
            Log::error('FCL Scrape error: ' . $e->getMessage());
            $this->message = 'Error al conectar con el proveedor de tarifas. Intenta de nuevo.';
            $this->loadingRates = false;
        }
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

    public function limpiar()
    {
        $this->reset(['peso', 'volumen', 'largo', 'ancho', 'alto', 'valorMercancia', 'resultado', 'desglose', 'mostrarPregunta', 'respuestaUsuario']);

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
