<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Models\Cliente;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\Attributes\Layout;


use Livewire\Attributes\Url;

use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class CalculadoraAerea extends Component
{
    use WithFileUploads;

    public $encodedItems = '';

    public $items = [];

    public $p2pPrice;

    // Configura aquí tu moneda (USD, PEN, ARS, MXN, COP, etc.)
    public $fiat = 'BOB';

    // Propiedades temporales para el formulario de agregar (Estilo LCL)
    public $temp_producto = '';
    public $temp_imagen = '';
    public $temp_manualImagen;
    public $temp_cantidad = 1;
    public $temp_cantidad_cajas;
    public $temp_valor_unitario = '';
    public $temp_peso_unitario = '';
    public $temp_peso_unidad = 'kg';
    public $temp_largo = '';
    public $temp_ancho = '';
    public $temp_alto = '';
    public $temp_dimension_total = '';
    public $temp_medida_unidad = 'cm';
    public $temp_hs_code = '';
    public $temp_arancel = 0;
    public $temp_costo_envio_interno = '';
    public $arancelSuggestions = [];

    // Estado local
    public $urgente = false;
    public $diasAlmacen = 7;

    // Tarifas configurables
    public $tarifaBase = 800;
    public $tarifaPorKg = 5;
    public $tarifaPorM3 = 150;

    // Resultado
    public $resultado = null;
    public $resultadoRebajado = null;
    public $desglose = [];
    public $pesoTotalCalculado = 0;
    public $pesoDimensionalTotalCalculado = 0;

    // Estado de interacción con precio
    public $mostrarPregunta = false;
    public $respuestaUsuario = null;

    // Nuevas propiedades para servicios adicionales
    public $verificacionSustanciasPeligrosas = false;
    public $pagosInternacionalesSwift = 'swift'; // 'swift' (1%) o 'sin_swift' (2.5%)
    public $requierePagoInternacional = false;
    public $seguroCarga = false;
    public $examenPrevio = false;

    // Propiedades para PDF / Cliente
    public $clienteNombre = '';
    public $clienteEmail = '';
    public $clienteTelefono = '';
    public $clienteDireccion = '';
    public $clienteCiudad = '';
    public $agenteId = null;
    public $gastosAdicionales = []; // Detalle para PDF
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
            'telefono' => '64583783'
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

    public function mount()
    {
        // 1. Prioridad: Si hay items codificados en la URL
        if ($this->encodedItems) {
            try {
                $decoded = json_decode(base64_decode($this->encodedItems), true);

                if (is_array($decoded) && count($decoded) > 0) {
                    $firstKey = array_key_first($decoded);
                    if (!is_array($decoded[$firstKey])) {
                        $item = $decoded;

                        $nombreProducto = $item['producto'] ?? ($item['id_producto'] ?? 'Producto');
                        $imagenUrl = $item['imagen'] ?? ($item['image'] ?? '');

                        $this->temp_producto = $nombreProducto;
                        $this->temp_imagen = $imagenUrl;
                        $this->temp_cantidad_cajas = intval($item['cantidad_cajas'] ?? 1);
                        $this->temp_valor_unitario = floatval($item['valor_unitario'] ?? ($item['valorMercancia'] ?? 0));
                        $this->temp_peso_unitario = floatval($item['peso_unitario'] ?? ($item['peso'] ?? 0));

                        // Procesar dimensiones si vienen en string (ej. "10x20x30") o campos individuales
                        $this->temp_largo = $item['largo'] ?? '';
                        $this->temp_ancho = $item['ancho'] ?? '';
                        $this->temp_alto = $item['alto'] ?? '';

                        if (!empty($item['dimensiones']) && empty($this->temp_largo)) {
                            $dims = explode('x', str_replace(' ', '', strtolower($item['dimensiones'])));
                            if (count($dims) === 3) {
                                $this->temp_largo = $dims[0];
                                $this->temp_ancho = $dims[1];
                                $this->temp_alto = $dims[2];
                            }
                        }

                        // Log para depuración interna del desarrollador (invisible para el usuario final)
                        Log::info("CalculadoraAerea: Cargado producto desde landing", [
                            'producto' => $nombreProducto,
                            'tiene_imagen' => !empty($imagenUrl),
                            'imagen' => $imagenUrl
                        ]);
                    } else {
                        // Es un array de arrays (ya es una lista de la calculadora aérea)
                        $this->items = $decoded;
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error decoding air calculator params: " . $e->getMessage());
            }
        }
        shuffle($this->agentes);

        if (count($this->items) > 0) {
            $this->calcular(false);
        }
        $this->fetchP2P();
    }


    public function updated($propertyName)
    {
        if (in_array($propertyName, ['clienteNombre', 'clienteEmail', 'clienteTelefono', 'clienteCiudad', 'clienteDireccion'])) {
            $this->validateOnly($propertyName, [
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
    }


    public function agregarProducto()
    {
        $this->validate([
            'temp_producto' => 'required|min:3',
            'temp_cantidad' => 'required|numeric|min:1',
            'temp_cantidad_cajas' => 'required|numeric|min:1',
            'temp_valor_unitario' => 'required|numeric|min:0',
            'temp_peso_unitario' => 'required|numeric|min:0',
        ], [
            'temp_producto.required' => 'El nombre es obligatorio',
            'temp_cantidad.required' => 'La cantidad es obligatoria',
            'temp_cantidad_cajas.required' => 'La cantidad de cajas es obligatoria',
            'temp_valor_unitario.required' => 'El valor es obligatorio',
            'temp_peso_unitario.required' => 'El peso es obligatorio',
        ]);

        $cantidad = intval($this->temp_cantidad);
        $cantidadCajas = intval($this->temp_cantidad_cajas);
        $cantidadTotal = $cantidad * $cantidadCajas;
        $valorUnit = floatval($this->temp_valor_unitario);
        $pesoUnit = floatval($this->temp_peso_unitario);

        if ($this->temp_peso_unidad === 'lb') {
            $pesoUnit = $pesoUnit * 0.453592;
        }

        $largo = floatval($this->temp_largo);
        $ancho = floatval($this->temp_ancho);
        $alto = floatval($this->temp_alto);

        // Convertir pulgadas a cm si es necesario (1 pulgada = 2.54 cm)
        if ($this->temp_medida_unidad === 'in') {
            $largo = $largo * 2.54;
            $ancho = $ancho * 2.54;
            $alto  = $alto * 2.54;
        }

        $imagenUrl = '';
        if ($this->temp_manualImagen) {
            $imagenUrl = $this->temp_manualImagen->temporaryUrl();
        } elseif ($this->temp_imagen) {
            $imagenUrl = $this->temp_imagen;
        }

        $prefix = strtoupper(substr(trim($this->clienteNombre ?: 'PROD'), 0, 3));
        $number = str_pad(count($this->items) + 1, 2, '0', STR_PAD_LEFT);

        $this->items[] = [
            'id' => "$prefix-$number",
            'producto' => $this->temp_producto,
            'imagen' => $imagenUrl,
            'cantidad' => $cantidad,
            'cantidad_cajas' => $cantidadCajas,
            'cantidad_total' => $cantidadTotal,
            'valor_unitario' => $valorUnit,
            'peso_unitario' => $pesoUnit,
            'largo' => $largo,
            'ancho' => $ancho,
            'alto' => $alto,
            'total_valor' => $valorUnit * $cantidadTotal,
            'total_peso' => $pesoUnit * $cantidadTotal,
            'hs_code' => $this->temp_hs_code,
            'arancel' => $this->temp_arancel
        ];


        // Reset temps
        $this->temp_producto = '';
        $this->temp_imagen = '';
        $this->temp_manualImagen = null;
        $this->temp_cantidad = 1;
        $this->temp_cantidad_cajas = '';
        $this->temp_valor_unitario = '';
        $this->temp_peso_unitario = '';
        $this->temp_peso_unidad = 'kg';
        $this->temp_largo = '';
        $this->temp_ancho = '';
        $this->temp_alto = '';
        $this->temp_dimension_total = '';
        $this->temp_medida_unidad = 'cm';
        $this->temp_hs_code = '';
        $this->temp_arancel = 0;
        $this->arancelSuggestions = [];

        $this->recalcular(false);
    }

    public function aplicarDimensiones($tipo)
    {
        $total = floatval($this->temp_dimension_total);
        if ($total <= 0) return;
        if ($total <= 0) return;

        // Distribución según tipo de caja
        // Square: L=W=H => 3x = total => x = total/3
        // Rectangular: L=2x, W=x, H=x => 4x = total => x = total/4
        // Flat: L=4x, W=4x, H=x => 9x = total => x = total/9 (Aproximación para caja plana baja)

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

    public function eliminarProducto($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // Reindexar

        // Re-generar IDs para mantener la secuencia
        $prefix = strtoupper(substr(trim($this->clienteNombre ?: 'PROD'), 0, 3));
        foreach ($this->items as $idx => &$item) {
            $number = str_pad($idx + 1, 2, '0', STR_PAD_LEFT);
            $item['id'] = "$prefix-$number";
        }

        $this->recalcular(false);
    }

    // Alias para compatibilidad si quedó algo colgado
    public function removeItem($index)
    {
        $this->eliminarProducto($index);
    }

    public function recalcular($mostrar = false)
    {
        // Si estamos modificando inputs o items, ocultamos el resultado anterior porque los datos han cambiado
        // y el usuario pidió explícitamente solo mostrar con el botón CALCULAR.
        if (!$mostrar) {
            $this->mostrarPregunta = false;
        }

        $this->calcular($mostrar);
        $this->syncUrl();
    }

    public function syncUrl()
    {
        $this->encodedItems = base64_encode(json_encode($this->items));
    }

    private function checkIfCanCalculate()
    {
        return count($this->items) > 0;
    }

    /**
     * Método principal de cálculo
     */
    public function calcular($forzarMostrar = true)
    {
        if ($forzarMostrar) {
            $this->validate([
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

            $nuevoCliente = Cliente::create([
                'clienteNombre'    => $this->clienteNombre,
                'clienteEmail'     => $this->clienteEmail,
                'clienteTelefono'  => $this->clienteTelefono,
                'clienteDireccion' => $this->clienteDireccion,
                'clienteCiudad'    => $this->clienteCiudad,
            ]);
        }
        if (empty($this->items)) {
            $this->resultado = null;
            $this->desglose = [];
            return;
        }

        // Si forzamos mostrar (botón CALCULAR), reseteamos para preparar visualización
        // Si NO forzamos (ej. recalculo interno), nos aseguramos que NO se muestre si así se pide
        if ($forzarMostrar) {
            $this->mostrarPregunta = false;
            $this->respuestaUsuario = null;
        } else {
            // Aseguramos que se oculte si es un cálculo background (como al agregar item)
            $this->mostrarPregunta = false;
        }

        $margen = 1.08; // 8% margen de empaque

        $pesoTotalAcumulado = 0;
        $pesoDimensionalTotalAcumulado = 0;
        $valorMercanciaTotalAcumulado = 0;

        foreach ($this->items as $item) {
            // Defensa: Si por alguna razón un item no es array, lo saltamos para evitar el error "array offset on float"
            if (!is_array($item)) {
                Log::warning("CalculadoraAerea: Item detectado como " . gettype($item) . " en lugar de array.", ['item' => $item]);
                continue;
            }

            $cantidad_total = intval($item['cantidad_total'] ?? 1);

            // Usar keys nuevas (valor_unitario, peso_unitario)
            // Fallback a keys viajas si existen por compatibilidad de URL decodificada vieja
            $pesoUnitario = isset($item['peso_unitario']) ? floatval($item['peso_unitario']) : (floatval($item['peso'] ?? 0));
            $valorUnitario = isset($item['valor_unitario']) ? floatval($item['valor_unitario']) : (floatval($item['valorMercancia'] ?? 0));

            $largo = floatval($item['largo'] ?? 0);
            $ancho = floatval($item['ancho'] ?? 0);
            $alto = floatval($item['alto'] ?? 0);

            // Peso Total del item (con cantidad y margen)
            $pesoTotalItem = ($pesoUnitario * $cantidad_total) * $margen;
            $pesoTotalAcumulado += $pesoTotalItem;

            // Peso Volumétrico del item
            if ($largo > 0 && $ancho > 0 && $alto > 0) {
                $volumenUnitario = ($largo * $ancho * $alto);
                $volumenTotalItem = ($volumenUnitario * $cantidad_total) * $margen;
                $pesoDimensionalItem = $volumenTotalItem / 5000;
                $pesoDimensionalTotalAcumulado += $pesoDimensionalItem;
            }

            // Valor mercancía
            $valorMercanciaTotalAcumulado += ($valorUnitario * $cantidad_total);
        }

        $this->pesoTotalCalculado = number_format($pesoTotalAcumulado, 2);
        $this->pesoDimensionalTotalCalculado = number_format($pesoDimensionalTotalAcumulado, 2);

        $resultado = $this->calcularCostoAereo($valorMercanciaTotalAcumulado, $pesoTotalAcumulado, $pesoDimensionalTotalAcumulado);

        if ($resultado['costo'] !== null) {
            $this->resultado = number_format($resultado['costo'], 2, '.', ',');
            $this->resultadoRebajado = number_format($resultado['costoRebajado'], 2, '.', ',');

            if ($forzarMostrar) {
                $this->mostrarPregunta = true;
                session()->flash('success', 'Cálculo completado para ' . count($this->items) . ' items.');
            }
        } else {
            $this->resultado = null;
            $this->resultadoRebajado = null;
        }
    }


    function calcularCostoAereo(float $valorMercancia, ?float $pesoTotalKg, ?float $pesoDimensionalTotal): array
    {
        $tarifas = [
            ['maxKg' => 1,   'tarifa' => 88.5],
            ['maxKg' => 5,   'tarifa' => 37.80],
            ['maxKg' => 10,  'tarifa' => 26.35],
            ['maxKg' => 15,  'tarifa' => 23.40],
            ['maxKg' => 20,  'tarifa' => 22.93],
            ['maxKg' => 25,  'tarifa' => 21.34],
            ['maxKg' => 50,  'tarifa' => 21.00],
            ['maxKg' => 70,  'tarifa' => 20.15],
            ['maxKg' => 90,  'tarifa' => 18.67],
            ['maxKg' => 100, 'tarifa' => 18.16],
            ['maxKg' => 150, 'tarifa' => 15.77],
            ['maxKg' => 200, 'tarifa' => 14.80],
            ['maxKg' => PHP_INT_MAX, 'tarifa' => 14.80]
        ];

        $errores = [];
        $advertencias = [];

        if ($pesoTotalKg !== null && $pesoDimensionalTotal > 0) {
            $pesoCobrable = max($pesoTotalKg, $pesoDimensionalTotal);
            $pesoRedondeado = ceil($pesoCobrable);
            $tipoCobro = ($pesoTotalKg >= $pesoDimensionalTotal) ? 'Peso real total' : 'Peso dimensional total';
        } else if ($pesoTotalKg === null && $pesoDimensionalTotal > 0) {
            $pesoCobrable = $pesoDimensionalTotal;
            $pesoRedondeado = ceil($pesoDimensionalTotal);
            $tipoCobro = 'Peso dimensional total (estimado)';
            $advertencias[] = "No se ingresó peso real. Se cobra por volumen como mínimo.";
        } else if ($pesoTotalKg !== null && ($pesoDimensionalTotal === null || $pesoDimensionalTotal == 0)) {
            $pesoRedondeado = ceil($pesoTotalKg);
            $tipoCobro = 'Peso real total';
            $advertencias[] = "No se ingresaron dimensiones. Si los paquetes son voluminosos, el costo final será mayor.";
        } else {
            return [
                'costo' => null,
                'tipo' => 'Datos insuficientes',
                'pesoCobrable' => null,
                'unidad' => 'kg',
                'errores' => ["Debe ingresar al menos el peso o las dimensiones."],
                'advertencias' => [],
                'detalle' => []
            ];
        }


        $peso1 = 0;
        $tarifa1 = 0;
        $indexActual = 0;

        foreach ($tarifas as $index => $rango) {
            if ($pesoRedondeado <= $rango['maxKg']) {
                $indexActual = $index;
                break;
            }
        }

        $rangoActual = $tarifas[$indexActual];
        $tarifaPorKg = $rangoActual['tarifa'];
        $maxKg = $rangoActual['maxKg'];

        if ($indexActual > 0) {
            $peso1 = $tarifas[$indexActual - 1]['maxKg'];
            $tarifa1 = $tarifas[$indexActual - 1]['tarifa'];
        }
        $total_tarifa          = $tarifaPorKg - $tarifa1;
        $total_peso_tarifa     = $maxKg - $peso1;
        $total_peso_redondeado = $pesoRedondeado - $peso1;


        $precioUnitario = $tarifa1 + ($total_peso_redondeado * ($total_tarifa / $total_peso_tarifa));

        $costoFinal = $precioUnitario * $pesoRedondeado;
        $factura  = 70;
        $comision = 0.03; // Gestión Administrativa (3%)
        $comisionBolivia = 0.035; // Gestión Administrativa (3.5%)

        // Lógica de Pagos Internacionales
        // Swift (1%) o Sin Swift (2.5%)
        $costoPagoInternacional = 0;
        if ($this->requierePagoInternacional) {
            $tasaPagoInternacional = ($this->pagosInternacionalesSwift === 'swift') ? 0.01 : 0.025;
            $costoPagoInternacional = $valorMercancia > 0 ? $valorMercancia * $tasaPagoInternacional : 0;
        }

        // Seguro
        // 2% si está seleccionado (seguroCarga), sino 0
        $tasaSeguro = $this->seguroCarga ? 0.02 : 0;
        $costoSeguro = $valorMercancia > 0 ? $valorMercancia * $tasaSeguro : 0;

        // Costos Fijos Adicionales
        $costoSustanciasPeligrosas = $this->verificacionSustanciasPeligrosas ? 250.00 : 0;
        $costoExamenPrevio = $this->examenPrevio ? 35.00 : 0;

        $totalServiciosAdicionales = $costoSeguro + $costoPagoInternacional + $costoSustanciasPeligrosas + $costoExamenPrevio;


        $almacen       = 0;
        $impuestos     = 0;

        $aplicarAdicionales = ($pesoRedondeado > 5);

        if ($aplicarAdicionales) {

            // Almacén dinámico
            $dias = floatval($this->diasAlmacen) ?: 7;
            if ($pesoRedondeado <= 25) {
                $tarifaAlmacen = 0.50;
            } elseif ($pesoRedondeado <= 50) {
                $tarifaAlmacen = 1.00;
            } else {
                $tarifaAlmacen = 1.50;
            }
            $almacen = $tarifaAlmacen * $dias;

            $impuestos     = 25;
        }
        // Calcular Gravamen Arancelario e IVA (Basado en lógica marítima)
        $totalArancel = 0;
        $iva = 0;
        foreach ($this->items as $prod) {
            $arancelPct = isset($prod['arancel']) ? floatval($prod['arancel']) : 0;
            // Base imponible (CIF estimado): Valor + 5% flete + 2% seguro
            $valorItem = $prod['total_valor'];
            $fleteEstimado = $valorItem * 0.30;
            $seguroEstimado = $valorItem * 0.02;
            $baseCIF = $valorItem + $costoFinal + $seguroEstimado + $fleteEstimado;

            $totalArancel += $baseCIF * ($arancelPct / 100);
            $iva += $baseCIF * (14.94 / 100);
        }

        $despacho_almacenamiento = 3.59;
        $despacho_documentos = 17.24;
        $despacho_formulario = 14.37;
        $despacho_destibador = ceil($pesoRedondeado);
        $despacho_representacion = (ceil($valorMercancia) * 3500) / 25000;

        $total_tiered_charge = $valorMercancia == 0 ? 0 : $this->calculate_tiered_charge($valorMercancia);
        $totalDespacho = $despacho_almacenamiento + $despacho_documentos + $despacho_formulario + $despacho_destibador + $despacho_representacion;
        $totalDespacho = $valorMercancia == 0 ? 0 : $totalDespacho;

        $costo_envio_interno = floatval($this->temp_costo_envio_interno);
        $impuestoTotal =  $totalArancel + $iva;
        $impuestoTotal = $valorMercancia == 0 ? 0 : $impuestoTotal;

        $totalLogisticaChina = ($valorMercancia + $costoFinal + $costo_envio_interno) * $comision;
        $totalLogisticaBolivia = ($valorMercancia + $costoFinal + $costo_envio_interno) * $comisionBolivia;

        $precio_rebajado = $costoSeguro + $impuestos + ($valorMercancia * $comision) + $costoPagoInternacional + $almacen + $factura;

        $totalGeneral = $valorMercancia + $costoFinal + $totalLogisticaChina + $totalLogisticaBolivia + $totalServiciosAdicionales + $impuestoTotal + $costo_envio_interno + $totalDespacho + $total_tiered_charge;

        $totalGeneralRebajado = $valorMercancia + $precio_rebajado + $totalLogisticaChina + $totalLogisticaBolivia + $impuestoTotal + $costo_envio_interno + $totalDespacho + $total_tiered_charge;
        $totalBaseImponible = $valorMercancia + $totalArancel;
        if ($valorMercancia == 0) {
            $this->desglose = [
                'Costo de Envío Internacional' => number_format($costoFinal, 2, '.', ''),
                'Costo de Envío Interno' => number_format($costo_envio_interno, 2, '.', ''),
                'Brokers en China' => number_format($totalLogisticaChina, 2, '.', ''),
                'Gestión Logística en Bolivia' => number_format($totalLogisticaBolivia, 2, '.', ''),
            ];
        } else {
            $this->desglose = [
                'Valor de Mercancía' => number_format($valorMercancia, 2, '.', ''),
                'Costo de Envío Internacional' => number_format($costoFinal, 2, '.', ''),
                'Costo de Envío Interno' => number_format($costo_envio_interno, 2, '.', ''),
                'Brokers en China' => number_format($totalLogisticaChina, 2, '.', ''),
                'Gestión Logística en Bolivia' => number_format($totalLogisticaBolivia, 2, '.', ''),
                'Despacho' => number_format($totalDespacho, 2, '.', ''),
                'Agencia despachante' => number_format($total_tiered_charge, 2, '.', ''),
                'Impuesto' => number_format($impuestoTotal, 2, '.', ''),
                'Comisión Pago Internacional' => number_format($costoPagoInternacional, 2, '.', ''),
            ];
        }

        if ($this->seguroCarga && $valorMercancia > 0) {
            $this->desglose['Seguro de Carga'] = number_format($costoSeguro, 2, '.', '');
        }
        if ($this->verificacionSustanciasPeligrosas) {
            $this->desglose[' Envio de producto peligroso'] = number_format($costoSustanciasPeligrosas, 2, '.', '');
        }
        if ($this->examenPrevio) {
            $this->desglose[' Examen Previo'] = number_format($costoExamenPrevio, 2, '.', '');
        }

        if ($this->seguroCarga) {
            $this->desglose['─ DETALLE DE FLETE Y SEGURO'] = null;
            $this->desglose['   └─ Seguro'] = number_format($costoSeguro, 2);
        }

        $this->desglose = array_merge($this->desglose, [
            '─ DETALLE DE SERVICIOS EN ORIGEN' => null,
            '   ├─ Brokers en China' => number_format($totalLogisticaChina, 2),
            '   ├─ Documentación' => number_format($factura, 2),
            '   ├─ Pago Internacional' => number_format($costoPagoInternacional, 2),
            '─ DETALLE DE OPERACIÓN Y LOGÍSTICA' => null,
            '   ├─ Almacenaje (' . ($tarifaAlmacen ?? 0.5) . ' USD/día x' . ($dias ?? 7) . ' días)' => $aplicarAdicionales ? number_format($almacen, 2) : 'No aplica (< 5 kg)',
            '   └─ Tasas de Exportación' => $aplicarAdicionales ? number_format($impuestos, 2) : 'No aplica (< 5 kg)',
            '   └─ Consolidación' => $aplicarAdicionales ? number_format(10, 2) : 'No aplica (< 5 kg)',
        ]);

        if ($this->verificacionSustanciasPeligrosas) {
            $this->desglose['   └─ Envio de producto peligroso'] = number_format($costoSustanciasPeligrosas, 2);
        }
        if ($this->examenPrevio) {
            $this->desglose['   └─ Examen Previo'] = number_format($costoExamenPrevio, 2);
        }

        // Poblar gastosAdicionales para el PDF (con todo el detalle)
        $this->gastosAdicionales = [
            'Costo de Envío Interno' => $costo_envio_interno,
            'Despacho' => $totalDespacho,
            'Agencia despachante' => $total_tiered_charge,
            // Desglosados para la tabla de aduana
            'Gravamen Arancelario' => $totalArancel,
            'Impuesto IVA' => $iva,
            'Base Imponible' => $totalBaseImponible,
            // Additional Services
            'Comisión Pago Internacional' => $costoPagoInternacional,

        ];

        if ($this->verificacionSustanciasPeligrosas) {
            $this->gastosAdicionales['Envio de producto peligroso'] = $costoSustanciasPeligrosas;
        }
        if ($this->examenPrevio) {
            $this->gastosAdicionales['Examen Previo'] = $costoExamenPrevio;
        }


        return [
            'costo' => number_format($totalGeneral, 2, '.', ''),
            'costoRebajado' => number_format($totalGeneralRebajado, 2, '.', ''),
            'tipo' => $tipoCobro,
            'pesoCobrable' => $pesoRedondeado,
            'unidad' => 'kg',
            'errores' => $errores,
            'advertencias' => $advertencias,
        ];
    }

    function calculate_tiered_charge(float $evaluation_value): float
    {
        $rates = [
            [1999.00, 93.39, 1],
            [3999.00, 150.86, 1],
            [5000.00, 193.97, 1],
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

    /**
     * Responder a la pregunta del precio
     */
    public function responder($respuesta)
    {
        $this->respuestaUsuario = $respuesta;
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

    public function descargarPDF()
    {
        if (empty($this->items)) {
            session()->flash('error', 'No hay productos para cotizar.');
            return;
        }
        $agenteSeleccionado = collect($this->agentes)->firstWhere('id', $this->agenteId);

        $resumenPDF = [
            'Valor de Mercancía' => $this->desglose['Valor de Mercancía'] ?? 0,
            'Costo de Envío Internacional' => $this->desglose['Costo de Envío Internacional'] ?? 0,
            'Brokers en China' => $this->desglose['Brokers en China'] ?? 0,
            'Gestión Logística en Bolivia' => $this->desglose['Gestión Logística en Bolivia'] ?? 0,
        ];

        return redirect()->route('cotizacion.pdf', [
            'tipoCarga' => 'AEREO',
            'peso' => $this->pesoTotalCalculado,
            'volumen' => $this->pesoDimensionalTotalCalculado,
            'cantidad' => count($this->items),
            'origen' => 'China',
            'destino' => 'Bolivia',
            'valorMercancia' => floatval($resumenPDF['Valor de Mercancía']),
            'agente' => json_encode($agenteSeleccionado),
            'resultado' => str_replace(',', '', $this->resultado),
            'desglose' => json_encode($resumenPDF),
            'tipoCobro' => (floatval($this->pesoTotalCalculado) >= floatval($this->pesoDimensionalTotalCalculado)) ? 'Peso Real' : 'Peso Volumétrico',
            'unidad' => 'kg',
            'productos' => json_encode($this->items),
            'gastosAdicionales' => json_encode($this->gastosAdicionales),

            // Info Cliente
            'clienteNombre' => $this->clienteNombre,
            'clienteEmail' => $this->clienteEmail,
            'clienteTelefono' => $this->clienteTelefono,
            'clienteDireccion' => $this->clienteDireccion,
            'clienteCiudad' => $this->clienteCiudad,
            'p2pPrice' => $this->p2pPrice,
        ]);
    }

    /**
     * Limpiar formulario
     */
    public function limpiar()
    {
        $this->reset([
            'resultado',
            'desglose',
            'mostrarPregunta',
            'respuestaUsuario',
            'items',
            'temp_producto',
            'temp_imagen',
            'temp_manualImagen',
            'temp_cantidad',
            'temp_cantidad_cajas',
            'temp_valor_unitario',
            'temp_peso_unitario',
            'temp_peso_unidad',
            'temp_largo',
            'temp_ancho',
            'temp_alto',
            'temp_medida_unidad',
            'temp_dimension_total',
            'temp_hs_code',
            'temp_arancel',
            'temp_costo_envio_interno',
            'arancelSuggestions',
            'clienteNombre',
            'clienteEmail',
            'clienteTelefono',
            'clienteDireccion',
            'clienteCiudad',
            'gastosAdicionales'
        ]);

        $this->items = [];
        $this->syncUrl();
        session()->flash('info', 'Formulario limpiado.');
    }

    public function render()
    {
        return view('livewire.calculadora-aerea');
    }
}
