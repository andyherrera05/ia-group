<?php

namespace App\Livewire;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class CalculadoraImpuestos extends Component
{
    // =======================================================
    //              DATOS DE ENTRADA - FORMULARIO LCL
    // =======================================================
    public $peso = '';
    public $volumen = '';
    public $valorMercancia = '';
    public $valorFlete = '';
    public $valorSeguro = '';
    
    // Calculador de volumen
    public $largo = '';
    public $ancho = '';
    public $alto = '';
    public $cantidadBultos = 1;
    
    // =======================================================
    //              BUSCADOR DE PRODUCTOS/ARANCELES
    // =======================================================
    public $searchProducto = '';
    public $productosSugeridos = [];
    public $showProductoDropdown = false;
    public $productoSeleccionado = null;
    public $codigoHS = '';
    public $descripcionProducto = '';
    
    // Diccionario de sinónimos para búsqueda inteligente
    private $sinonimos = [
        // Animales
        'vaca' => ['bovino', 'bovinos', 'vacuno', 'ganado', 'res'],
        'vacas' => ['bovino', 'bovinos', 'vacuno', 'ganado', 'res'],
        'toro' => ['bovino', 'bovinos', 'vacuno', 'ganado'],
        'res' => ['bovino', 'bovinos', 'vacuno', 'carne'],
        'chancho' => ['cerdo', 'porcino', 'porcinos', 'cochino', 'marrano'],
        'cerdo' => ['porcino', 'porcinos', 'chancho', 'cochino', 'marrano'],
        'puerco' => ['cerdo', 'porcino', 'porcinos', 'chancho'],
        'pollo' => ['gallina', 'ave', 'aves', 'gallos'],
        'gallina' => ['pollo', 'ave', 'aves', 'gallos'],
        'oveja' => ['ovino', 'ovinos', 'cordero', 'borrego'],
        'cordero' => ['ovino', 'ovinos', 'oveja', 'borrego'],
        'cabra' => ['caprino', 'caprinos', 'chivo'],
        'chivo' => ['caprino', 'caprinos', 'cabra'],
        'caballo' => ['equino', 'caballar', 'yegua', 'potro'],
        'burro' => ['asno', 'mula', 'mular'],
        'llama' => ['camélido', 'camélidos', 'alpaca', 'guanaco'],
        'alpaca' => ['camélido', 'camélidos', 'llama', 'guanaco'],
        'pez' => ['pescado', 'pescados', 'peces'],
        'pescado' => ['pez', 'peces', 'pescados'],
        'camarón' => ['crustáceo', 'crustáceos', 'langostino', 'gambas'],
        'langosta' => ['crustáceo', 'crustáceos', 'bogavante'],
        
        // Carnes
        'carne' => ['res', 'bovino', 'cerdo', 'pollo', 'carnes'],
        'bistec' => ['carne', 'bovino', 'res', 'filete'],
        'chuleta' => ['carne', 'cerdo', 'porcino', 'costilla'],
        'tocino' => ['cerdo', 'porcino', 'bacon', 'panceta'],
        'jamón' => ['cerdo', 'porcino', 'pierna'],
        'salchicha' => ['embutido', 'cerdo', 'porcino'],
        
        // Lácteos
        'leche' => ['lácteo', 'lácteos', 'nata', 'crema'],
        'queso' => ['lácteo', 'lácteos', 'requesón'],
        'yogurt' => ['lácteo', 'lácteos', 'yogur', 'fermentado'],
        'yogur' => ['lácteo', 'lácteos', 'yogurt', 'fermentado'],
        'mantequilla' => ['lácteo', 'lácteos', 'manteca', 'grasa'],
        'crema' => ['nata', 'lácteo', 'lácteos'],
        
        // Huevos
        'huevo' => ['huevos', 'yema', 'clara', 'gallina'],
        'huevos' => ['huevo', 'yema', 'clara', 'gallina'],
        
        // Miel
        'miel' => ['abeja', 'apícola', 'natural'],
        
        // Frutas
        'manzana' => ['fruta', 'frutas', 'poma'],
        'naranja' => ['fruta', 'frutas', 'cítrico', 'cítricos'],
        'limón' => ['fruta', 'frutas', 'cítrico', 'cítricos', 'lima'],
        'banana' => ['fruta', 'frutas', 'plátano', 'banano'],
        'plátano' => ['fruta', 'frutas', 'banana', 'banano'],
        'uva' => ['fruta', 'frutas', 'vid', 'viña'],
        'fresa' => ['fruta', 'frutas', 'frutilla', 'baya'],
        'frutilla' => ['fruta', 'frutas', 'fresa', 'baya'],
        'palta' => ['aguacate', 'fruta', 'frutas'],
        'aguacate' => ['palta', 'fruta', 'frutas'],
        'piña' => ['fruta', 'frutas', 'ananá', 'ananás'],
        'ananá' => ['fruta', 'frutas', 'piña'],
        
        // Verduras
        'papa' => ['patata', 'tubérculo', 'verdura'],
        'patata' => ['papa', 'tubérculo', 'verdura'],
        'tomate' => ['verdura', 'hortaliza', 'jitomate'],
        'jitomate' => ['tomate', 'verdura', 'hortaliza'],
        'cebolla' => ['verdura', 'hortaliza', 'bulbo'],
        'ajo' => ['verdura', 'hortaliza', 'bulbo'],
        'zanahoria' => ['verdura', 'hortaliza', 'raíz'],
        'lechuga' => ['verdura', 'hortaliza', 'hoja'],
        'choclo' => ['maíz', 'elote', 'cereal'],
        'maíz' => ['choclo', 'elote', 'cereal'],
        'elote' => ['maíz', 'choclo', 'cereal'],
        'poroto' => ['frijol', 'judía', 'legumbre', 'alubia'],
        'frijol' => ['poroto', 'judía', 'legumbre', 'alubia'],
        'arveja' => ['guisante', 'chícharo', 'legumbre'],
        'guisante' => ['arveja', 'chícharo', 'legumbre'],
        
        // Cereales y granos
        'trigo' => ['cereal', 'cereales', 'grano', 'harina'],
        'arroz' => ['cereal', 'cereales', 'grano'],
        'avena' => ['cereal', 'cereales', 'grano'],
        'cebada' => ['cereal', 'cereales', 'grano', 'malta'],
        'quinua' => ['cereal', 'cereales', 'grano', 'quinoa'],
        'quinoa' => ['quinua', 'cereal', 'cereales', 'grano'],
        'soya' => ['soja', 'legumbre', 'oleaginosa'],
        'soja' => ['soya', 'legumbre', 'oleaginosa'],
        
        // Tecnología
        'celular' => ['teléfono', 'móvil', 'smartphone', 'telefono'],
        'teléfono' => ['celular', 'móvil', 'smartphone', 'telefono'],
        'telefono' => ['celular', 'móvil', 'smartphone', 'teléfono'],
        'computadora' => ['ordenador', 'pc', 'laptop', 'computador'],
        'computador' => ['computadora', 'ordenador', 'pc', 'laptop'],
        'laptop' => ['portátil', 'notebook', 'computadora'],
        'notebook' => ['laptop', 'portátil', 'computadora'],
        'tablet' => ['tableta', 'ipad'],
        'tableta' => ['tablet', 'ipad'],
        'televisor' => ['tv', 'televisión', 'tele'],
        'tv' => ['televisor', 'televisión', 'tele'],
        'pantalla' => ['monitor', 'display', 'lcd', 'led'],
        'monitor' => ['pantalla', 'display'],
        'cámara' => ['camara', 'fotográfica', 'fotografica'],
        'camara' => ['cámara', 'fotográfica', 'fotografica'],
        'impresora' => ['printer', 'impresor'],
        'auriculares' => ['audífonos', 'audifonos', 'headphones', 'cascos'],
        'audífonos' => ['auriculares', 'audifonos', 'headphones'],
        
        // Textiles
        'ropa' => ['vestimenta', 'prendas', 'textil', 'confección'],
        'camisa' => ['prenda', 'textil', 'ropa'],
        'pantalón' => ['prenda', 'textil', 'ropa', 'pantalon'],
        'vestido' => ['prenda', 'textil', 'ropa'],
        'zapatos' => ['calzado', 'zapato'],
        'calzado' => ['zapatos', 'zapato', 'botas'],
        'tela' => ['tejido', 'textil', 'género'],
        'algodón' => ['algodon', 'textil', 'fibra'],
        'lana' => ['textil', 'fibra', 'oveja'],
        
        // Vehículos
        'auto' => ['carro', 'coche', 'automóvil', 'vehículo'],
        'carro' => ['auto', 'coche', 'automóvil', 'vehículo'],
        'coche' => ['auto', 'carro', 'automóvil', 'vehículo'],
        'moto' => ['motocicleta', 'motociclo'],
        'motocicleta' => ['moto', 'motociclo'],
        'camión' => ['camion', 'truck', 'vehículo'],
        'camion' => ['camión', 'truck', 'vehículo'],
        
        // Maquinaria
        'máquina' => ['maquina', 'maquinaria', 'equipo'],
        'maquina' => ['máquina', 'maquinaria', 'equipo'],
        'tractor' => ['maquinaria', 'agrícola', 'agricola'],
        'motor' => ['motores', 'mecánico'],
        
        // Químicos y farmacéuticos
        'medicamento' => ['medicina', 'fármaco', 'farmaco', 'droga'],
        'medicina' => ['medicamento', 'fármaco', 'farmaco'],
        'jabón' => ['jabon', 'detergente', 'limpieza'],
        'jabon' => ['jabón', 'detergente', 'limpieza'],
        'perfume' => ['fragancia', 'colonia', 'cosmético'],
        'cosmético' => ['cosmetico', 'maquillaje', 'belleza'],
        
        // Bebidas
        'cerveza' => ['bebida', 'alcohol', 'fermentado'],
        'vino' => ['bebida', 'alcohol', 'uva'],
        'whisky' => ['bebida', 'alcohol', 'destilado', 'whiskey'],
        'ron' => ['bebida', 'alcohol', 'destilado'],
        'vodka' => ['bebida', 'alcohol', 'destilado'],
        'refresco' => ['gaseosa', 'soda', 'bebida'],
        'gaseosa' => ['refresco', 'soda', 'bebida'],
        'jugo' => ['zumo', 'bebida', 'fruta'],
        'zumo' => ['jugo', 'bebida', 'fruta'],
        'café' => ['cafe', 'bebida'],
        'cafe' => ['café', 'bebida'],
        'té' => ['te', 'bebida', 'infusión'],
        'te' => ['té', 'bebida', 'infusión'],
    ];
    
    // Tasas configurables
    public $tasaArancel = 10; // %
    public $tasaIVA = 14.94; // % (Bolivia: IVA 13% + IT 3% ≈ 14.94% efectivo)
    public $tasaGA = 0; // % Gravamen Arancelario (del JSON)
    public $tasaICE = 0; // % Impuesto al Consumo Específico
    
    // Opciones de cálculo automático Flete/Seguro
    public $calcularFleteAuto = false;
    public $calcularSeguroAuto = false;
    public $porcentajeFlete = 5; // % del FOB
    public $porcentajeSeguro = 2; // % del FOB
    public $tipoCambio = 6.96; // Bs por USD
    
    // Resultado
    public $resultado = null;
    public $desglose = [];
    
    // Estado de interacción
    public $mostrarPregunta = false;
    public $respuestaUsuario = null;
    
    // Aranceles cargados
    private $arancelesData = null;

    // =======================================================
    //              MOUNT - CARGAR ARANCELES
    // =======================================================
    public function mount()
    {
        $this->cargarAranceles();
    }

    private function cargarAranceles()
    {
        $path = base_path('database/mockup/aranceles.json');
        if (file_exists($path)) {
            $json = file_get_contents($path);
            $this->arancelesData = json_decode($json, true);
        }
    }

    // =======================================================
    //              BUSCAR PRODUCTOS
    // =======================================================
    public function updatedSearchProducto($value)
    {
        if (strlen($value) < 2) {
            $this->productosSugeridos = [];
            $this->showProductoDropdown = false;
            return;
        }

        $this->cargarAranceles();
        $this->buscarProductos($value);
        $this->showProductoDropdown = count($this->productosSugeridos) > 0;
    }

    private function buscarProductos($termino)
    {
        $termino = strtolower(trim($termino));
        $resultados = [];
        $terminosBusqueda = [$termino];
        
        // Expandir con sinónimos
        foreach ($this->sinonimos as $palabra => $relacionados) {
            if (strpos($termino, $palabra) !== false) {
                $terminosBusqueda = array_merge($terminosBusqueda, $relacionados);
            }
            // También buscar si el término está en los relacionados
            foreach ($relacionados as $relacionado) {
                if (strpos($termino, $relacionado) !== false) {
                    $terminosBusqueda[] = $palabra;
                    $terminosBusqueda = array_merge($terminosBusqueda, $relacionados);
                }
            }
        }
        
        $terminosBusqueda = array_unique($terminosBusqueda);
        
        if ($this->arancelesData && isset($this->arancelesData['capitulos'])) {
            foreach ($this->arancelesData['capitulos'] as $capitulo) {
                if (isset($capitulo['items'])) {
                    foreach ($capitulo['items'] as $item) {
                        $descripcionLower = strtolower($item['descripcion'] ?? '');
                        $codigoHS = $item['codigo_hs'] ?? '';
                        
                        // Buscar por código HS
                        if (strpos($codigoHS, $termino) !== false) {
                            $resultados[] = [
                                'codigo_hs' => $codigoHS,
                                'descripcion' => $item['descripcion'],
                                'arancel' => $item['arancel'] ?? 0,
                                'capitulo' => $capitulo['numero']
                            ];
                            continue;
                        }
                        
                        // Buscar por descripción y sinónimos
                        foreach ($terminosBusqueda as $busqueda) {
                            if (strpos($descripcionLower, $busqueda) !== false) {
                                $resultados[] = [
                                    'codigo_hs' => $codigoHS,
                                    'descripcion' => $item['descripcion'],
                                    'arancel' => $item['arancel'] ?? 0,
                                    'capitulo' => $capitulo['numero']
                                ];
                                break;
                            }
                        }
                        
                        if (count($resultados) >= 15) break 2;
                    }
                }
            }
        }
        
        // Eliminar duplicados
        $unique = [];
        foreach ($resultados as $item) {
            $unique[$item['codigo_hs']] = $item;
        }
        
        $this->productosSugeridos = array_values($unique);
    }

    public function seleccionarProducto($codigoHS, $descripcion, $arancel)
    {
        $this->codigoHS = $codigoHS;
        $this->descripcionProducto = $descripcion;
        $this->tasaGA = $arancel;
        $this->searchProducto = $descripcion;
        $this->productoSeleccionado = [
            'codigo_hs' => $codigoHS,
            'descripcion' => $descripcion,
            'arancel' => $arancel
        ];
        $this->showProductoDropdown = false;
        $this->productosSugeridos = [];
    }

    public function cerrarDropdownProducto()
    {
        $this->showProductoDropdown = false;
    }

    // =======================================================
    //              CALCULAR VOLUMEN
    // =======================================================
    public function calcularVolumen()
    {
        if ($this->largo && $this->ancho && $this->alto) {
            $this->volumen = number_format(($this->largo * $this->ancho * $this->alto) / 1000000, 3, '.', '');
        }
    }

    // =======================================================
    //              CÁLCULO PRINCIPAL DE IMPUESTOS
    // =======================================================
    public function calcular()
    {
        if (empty($this->valorMercancia)) {
            session()->flash('error', 'Por favor ingresa el valor de la mercancía (FOB).');
            return;
        }
        
        // Reiniciar estado de pregunta
        $this->mostrarPregunta = false;
        $this->respuestaUsuario = null;
        
        $valorMercancia = floatval($this->valorMercancia);
        
        // Calcular Flete y Seguro automáticamente si está habilitado
        $valorFlete = $this->calcularFleteAuto 
            ? $valorMercancia * ($this->porcentajeFlete / 100)
            : floatval($this->valorFlete ?: 0);
            
        $valorSeguro = $this->calcularSeguroAuto 
            ? $valorMercancia * ($this->porcentajeSeguro / 100)
            : floatval($this->valorSeguro ?: 0);
        
        // Si hay producto seleccionado, usar su arancel
        if ($this->productoSeleccionado) {
            $this->tasaArancel = $this->productoSeleccionado['arancel'];
        }
        
        // Calcular Base Imponible (CIF: Cost, Insurance, Freight)
        $baseImponible = $valorMercancia + $valorFlete + $valorSeguro;
        
        // Tipo de cambio
        $tc = floatval($this->tipoCambio ?: 6.96);
        
        // Base imponible en Bolivianos
        $baseImponibleBs = $baseImponible * $tc;
        
        // Calcular Gravamen Arancelario (GA)
        $gravamenArancelario = $baseImponibleBs * ($this->tasaArancel / 100);
        
        // Base para IVA (CIF_Bs + GA)
        $baseIVA = $baseImponibleBs + $gravamenArancelario;
        
        // Calcular IVA (14.94% en Bolivia)
        $iva = $baseIVA * ($this->tasaIVA / 100);
        
        // Total de impuestos (en Bs)
        $totalImpuestos = $gravamenArancelario + $iva;
        
        // Total impuestos en USD
        $totalImpuestosUSD = $totalImpuestos / $tc;
        
        // Valor total a pagar (CIF + impuestos) en USD
        $totalAPagar = $baseImponible + $totalImpuestosUSD;
        
        $this->desglose = [
            'Valor FOB (Mercancía)' => number_format($valorMercancia, 2, '.', ','),
            'Valor Flete' => number_format($valorFlete, 2, '.', ','),
            'Valor Seguro' => number_format($valorSeguro, 2, '.', ','),
            'CIF (USD)' => number_format($baseImponible, 2, '.', ','),
            'Tipo de Cambio' => number_format($tc, 2) . ' Bs/USD',
            'CIF (Bs)' => number_format($baseImponibleBs, 2, '.', ','),
        ];
        
        // Agregar info del producto si está seleccionado
        if ($this->productoSeleccionado) {
            $this->desglose['Código HS'] = $this->codigoHS;
            $this->desglose['Producto'] = substr($this->descripcionProducto, 0, 40) . '...';
        }
        
        $this->desglose['GA (' . $this->tasaArancel . '%)'] = 'Bs ' . number_format($gravamenArancelario, 2, '.', ',');
        $this->desglose['Base IVA (CIF+GA)'] = 'Bs ' . number_format($baseIVA, 2, '.', ',');
        $this->desglose['IVA (' . $this->tasaIVA . '%)'] = 'Bs ' . number_format($iva, 2, '.', ',');
        $this->desglose['Total Impuestos (Bs)'] = 'Bs ' . number_format($totalImpuestos, 2, '.', ',');
        $this->desglose['Total Impuestos (USD)'] = number_format($totalImpuestosUSD, 2, '.', ',');
        $this->desglose['Total a Pagar (USD)'] = number_format($totalAPagar, 2, '.', ',');
        
        $this->resultado = number_format($totalImpuestosUSD, 2, '.', ',');
        $this->mostrarPregunta = true;
        session()->flash('success', 'Cálculo de impuestos completado.');
    }

    /**
     * Responder a la pregunta sobre el precio
     */
    public function responder($respuesta)
    {
        $this->respuestaUsuario = $respuesta;
    }
    
    /**
     * Limpiar formulario
     */
    public function limpiar()
    {
        $this->reset([
            'peso', 'volumen', 'valorMercancia', 'valorFlete', 'valorSeguro',
            'largo', 'ancho', 'alto', 'cantidadBultos',
            'searchProducto', 'productosSugeridos', 'showProductoDropdown',
            'productoSeleccionado', 'codigoHS', 'descripcionProducto',
            'resultado', 'desglose', 'mostrarPregunta', 'respuestaUsuario',
            'calcularFleteAuto', 'calcularSeguroAuto'
        ]);
        $this->cantidadBultos = 1;
        $this->tasaArancel = 10;
        $this->tasaGA = 0;
        $this->tipoCambio = 6.96;
        $this->porcentajeFlete = 5;
        $this->porcentajeSeguro = 2;
        session()->flash('info', 'Formulario limpiado.');
    }

    // =======================================================
    //              DESCARGAR PDF PROFORMA
    // =======================================================
    public function descargarProforma()
    {
        if (!$this->resultado) {
            session()->flash('error', 'Primero debes calcular los impuestos.');
            return;
        }

        $valorFOB = floatval($this->valorMercancia);
        
        // Calcular Flete y Seguro según configuración
        $valorFlete = $this->calcularFleteAuto 
            ? $valorFOB * ($this->porcentajeFlete / 100)
            : floatval($this->valorFlete ?: 0);
            
        $valorSeguro = $this->calcularSeguroAuto 
            ? $valorFOB * ($this->porcentajeSeguro / 100)
            : floatval($this->valorSeguro ?: 0);
            
        $valorCIF = $valorFOB + $valorFlete + $valorSeguro;
        
        // Tipo de cambio
        $tc = floatval($this->tipoCambio ?: 6.96);

        $data = [
            'tipo' => 'proforma',
            'titulo' => 'PROFORMA DE IMPUESTOS DE IMPORTACIÓN',
            'fecha' => now()->format('d/m/Y H:i'),
            'numero' => 'PRO-' . date('Ymd') . '-' . rand(1000, 9999),
            'producto' => [
                'codigo_hs' => $this->codigoHS,
                'descripcion' => $this->descripcionProducto,
                'arancel' => $this->productoSeleccionado['arancel'] ?? $this->tasaArancel,
            ],
            'carga' => [
                'peso' => $this->peso,
                'volumen' => $this->volumen,
                'bultos' => $this->cantidadBultos,
                'dimensiones' => $this->largo && $this->ancho && $this->alto 
                    ? "{$this->largo} x {$this->ancho} x {$this->alto} cm" 
                    : 'N/A',
            ],
            'valores' => [
                'fob' => $valorFOB,
                'flete' => $valorFlete,
                'seguro' => $valorSeguro,
                'cif' => $valorCIF,
                'tc' => $tc,
            ],
            'desglose' => $this->desglose,
            'resultado' => $this->resultado,
            'tasas' => [
                'ga' => $this->tasaArancel,
                'iva' => $this->tasaIVA,
            ],
        ];

        $pdf = Pdf::loadView('pdf.impuestos-proforma', $data);
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'proforma-impuestos-' . date('Ymd-His') . '.pdf');
    }

    // =======================================================
    //              DESCARGAR PDF LIQUIDACIÓN
    // =======================================================
    public function descargarLiquidacion()
    {
        if (!$this->resultado) {
            session()->flash('error', 'Primero debes calcular los impuestos.');
            return;
        }

        $valorFOB = floatval($this->valorMercancia);
        
        // Calcular Flete y Seguro según configuración
        $valorFlete = $this->calcularFleteAuto 
            ? $valorFOB * ($this->porcentajeFlete / 100)
            : floatval($this->valorFlete ?: 0);
            
        $valorSeguro = $this->calcularSeguroAuto 
            ? $valorFOB * ($this->porcentajeSeguro / 100)
            : floatval($this->valorSeguro ?: 0);
            
        $valorCIF = $valorFOB + $valorFlete + $valorSeguro;
        
        // Tipo de cambio
        $tc = floatval($this->tipoCambio ?: 6.96);
        $valorCIFBs = $valorCIF * $tc;
        
        // Cálculos en Bolivianos
        $gravamenArancelario = $valorCIFBs * ($this->tasaArancel / 100);
        $baseIVA = $valorCIFBs + $gravamenArancelario;
        $iva = $baseIVA * ($this->tasaIVA / 100);
        $totalImpuestosBs = $gravamenArancelario + $iva;
        $totalImpuestosUSD = $totalImpuestosBs / $tc;
        $totalAPagar = $valorCIF + $totalImpuestosUSD;

        $data = [
            'tipo' => 'liquidacion',
            'titulo' => 'LIQUIDACIÓN DE TRIBUTOS ADUANEROS',
            'fecha' => now()->format('d/m/Y H:i'),
            'numero' => 'LIQ-' . date('Ymd') . '-' . rand(1000, 9999),
            'producto' => [
                'codigo_hs' => $this->codigoHS,
                'descripcion' => $this->descripcionProducto,
                'arancel' => $this->productoSeleccionado['arancel'] ?? $this->tasaArancel,
            ],
            'carga' => [
                'peso' => $this->peso,
                'volumen' => $this->volumen,
                'bultos' => $this->cantidadBultos,
                'dimensiones' => $this->largo && $this->ancho && $this->alto 
                    ? "{$this->largo} x {$this->ancho} x {$this->alto} cm" 
                    : 'N/A',
            ],
            'valores' => [
                'fob' => $valorFOB,
                'flete' => $valorFlete,
                'seguro' => $valorSeguro,
                'cif' => $valorCIF,
                'cif_bs' => $valorCIFBs,
                'tc' => $tc,
            ],
            'tributos' => [
                'ga' => [
                    'tasa' => $this->tasaArancel,
                    'base' => $valorCIFBs,
                    'monto' => $gravamenArancelario,
                ],
                'iva' => [
                    'tasa' => $this->tasaIVA,
                    'base' => $baseIVA,
                    'monto' => $iva,
                ],
            ],
            'totales' => [
                'impuestos_bs' => $totalImpuestosBs,
                'impuestos_usd' => $totalImpuestosUSD,
                'impuestos' => $totalImpuestosUSD,
                'total_pagar' => $totalAPagar,
            ],
            'desglose' => $this->desglose,
            'resultado' => $this->resultado,
        ];

        $pdf = Pdf::loadView('pdf.impuestos-liquidacion', $data);
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'liquidacion-impuestos-' . date('Ymd-His') . '.pdf');
    }

    public function render()
    {
        return view('livewire.calculadora-impuestos')
            ->layout('layouts.app', ['title' => 'Calculadora de Impuestos']);
    }
}
