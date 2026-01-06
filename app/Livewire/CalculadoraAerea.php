<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Layout;


use Livewire\Attributes\Url;

use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class CalculadoraAerea extends Component
{
    use WithFileUploads;

    #[Url(as: 'q')]
    public $encodedItems = '';

    public $items = [];

    // Propiedades temporales para el formulario de agregar (Estilo LCL)
    public $temp_producto = '';
    public $temp_imagen = '';
    public $temp_manualImagen;
    public $temp_cantidad = 1;
    public $temp_valor_unitario = '';
    public $temp_peso_unitario = '';
    public $temp_largo = '';
    public $temp_ancho = '';
    public $temp_alto = '';

    // Estado local
    public $urgente = false;
    public $diasAlmacen = 7;

    // Tarifas configurables
    public $tarifaBase = 800;
    public $tarifaPorKg = 5;
    public $tarifaPorM3 = 150;

    // Resultado
    public $resultado = null;
    public $desglose = [];
    public $pesoTotalCalculado = 0;
    public $pesoDimensionalTotalCalculado = 0;

    // Estado de interacción con precio
    public $mostrarPregunta = false;
    public $respuestaUsuario = null;

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

    public function mount()
    {
        // 1. Prioridad: Si hay items codificados en la URL
        if ($this->encodedItems) {
            try {
                $decoded = json_decode(base64_decode($this->encodedItems), true);
                
                if (is_array($decoded) && count($decoded) > 0) {
                    // Si el primer elemento no es un array, asumimos que es un objeto único (ej. desde landing page)
                    $firstKey = array_key_first($decoded);
                    if (!is_array($decoded[$firstKey])) {
                        // Transformar objeto único (desde Landing Page)
                        $item = $decoded;
                        
                        // Mapear campos con fallbacks
                        $nombreProducto = $item['producto'] ?? ($item['id_producto'] ?? 'Producto');
                        $imagenUrl = $item['imagen'] ?? ($item['image'] ?? ''); // Fallback de key 'image'
                        
                        // Poblar los campos temporales del formulario (Estilo Marítimo LCL)
                        // NO agregamos a $this->items todavía para evitar el efecto "doble" en la UI
                        $this->temp_producto = $nombreProducto;
                        $this->temp_imagen = $imagenUrl;
                        $this->temp_cantidad = intval($item['cantidad'] ?? 1);
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

        if (count($this->items) > 0) {
            $this->calcular(false);
        }
    }

    public function agregarProducto()
    {
        $this->validate([
            'temp_producto' => 'required|min:3',
            'temp_cantidad' => 'required|numeric|min:1',
            'temp_valor_unitario' => 'required|numeric|min:0',
            'temp_peso_unitario' => 'required|numeric|min:0',
        ], [
            'temp_producto.required' => 'El nombre es obligatorio',
            'temp_cantidad.required' => 'La cantidad es obligatoria',
            'temp_valor_unitario.required' => 'El valor es obligatorio',
            'temp_peso_unitario.required' => 'El peso es obligatorio',
        ]);

        $imagenUrl = '';
        if ($this->temp_manualImagen) {
            $imagenUrl = $this->temp_manualImagen->temporaryUrl();
        } elseif ($this->temp_imagen) {
            $imagenUrl = $this->temp_imagen;
        }

        $cantidad = intval($this->temp_cantidad);
        $valorUnit = floatval($this->temp_valor_unitario);
        $pesoUnit = floatval($this->temp_peso_unitario);
        
        $this->items[] = [
            'producto' => $this->temp_producto,
            'imagen' => $imagenUrl,
            'cantidad' => $cantidad,
            'valor_unitario' => $valorUnit,
            'peso_unitario' => $pesoUnit,
            'largo' => $this->temp_largo,
            'ancho' => $this->temp_ancho,
            'alto' => $this->temp_alto,
            'total_valor' => $valorUnit * $cantidad,
            'total_peso' => $pesoUnit * $cantidad,
        ];

        // Reset temps
        $this->temp_producto = '';
        $this->temp_imagen = '';
        $this->temp_manualImagen = null;
        $this->temp_cantidad = 1;
        $this->temp_valor_unitario = '';
        $this->temp_peso_unitario = '';
        $this->temp_largo = '';
        $this->temp_ancho = '';
        $this->temp_alto = '';

        $this->recalcular(false);
    }

    public function eliminarProducto($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // Reindexar
        $this->recalcular(false);
    }

    // Alias para compatibilidad si quedó algo colgado
    public function removeItem($index) {
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
            
            $cantidad = intval($item['cantidad'] ?? 1);
            
            // Usar keys nuevas (valor_unitario, peso_unitario)
            // Fallback a keys viajas si existen por compatibilidad de URL decodificada vieja
            $pesoUnitario = isset($item['peso_unitario']) ? floatval($item['peso_unitario']) : (floatval($item['peso'] ?? 0));
            $valorUnitario = isset($item['valor_unitario']) ? floatval($item['valor_unitario']) : (floatval($item['valorMercancia'] ?? 0));

            $largo = floatval($item['largo'] ?? 0);
            $ancho = floatval($item['ancho'] ?? 0);
            $alto = floatval($item['alto'] ?? 0);
            
            // Peso Total del item (con cantidad y margen)
            $pesoTotalItem = ($pesoUnitario * $cantidad) * $margen;
            $pesoTotalAcumulado += $pesoTotalItem;

            // Peso Volumétrico del item
            if ($largo > 0 && $ancho > 0 && $alto > 0) {
                $volumenUnitario = ($largo * $ancho * $alto);
                $volumenTotalItem = ($volumenUnitario * $cantidad) * $margen;
                $pesoDimensionalItem = $volumenTotalItem / 5000;
                $pesoDimensionalTotalAcumulado += $pesoDimensionalItem;
            }

            // Valor mercancía
            $valorMercanciaTotalAcumulado += ($valorUnitario * $cantidad);
        }

        $this->pesoTotalCalculado = number_format($pesoTotalAcumulado, 2);
        $this->pesoDimensionalTotalCalculado = number_format($pesoDimensionalTotalAcumulado, 2);

        $resultado = $this->calcularCostoAereo($valorMercanciaTotalAcumulado, $pesoTotalAcumulado, $pesoDimensionalTotalAcumulado);

        if ($resultado['costo'] !== null) {
             $this->resultado = number_format($resultado['costo'], 2, '.', ',');
             
             if ($forzarMostrar) {
                 $this->mostrarPregunta = true;
                 session()->flash('success', 'Cálculo completado para ' . count($this->items) . ' items.');
             }
        } else {
             $this->resultado = null;
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
        }
        else if ($pesoTotalKg !== null && ($pesoDimensionalTotal === null || $pesoDimensionalTotal == 0)) {
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

        // Buscar tarifa (igual que antes)
        $tarifaPorKg = end($tarifas)['tarifa'];
        foreach ($tarifas as $rango) {
            if ($pesoRedondeado <= $rango['maxKg']) {
                $tarifaPorKg = $rango['tarifa'];
                break;
            }
        }
        $costoFinal = $tarifaPorKg * $pesoRedondeado;
       

        $comision = $valorMercancia * 0.06;
        $factura  = 70;
        $seguro   = $valorMercancia * 0.02;
        $pagoInternacional = $valorMercancia * 0.01;
        $costoEnvioInterno = 15;


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
        $totalLogisticaChina = $pagoInternacional + $costoEnvioInterno + $comision + $factura + $seguro + $almacen + $impuestos;
        $totalGeneral = $valorMercancia + $costoFinal + $totalLogisticaChina ;

        Log::info('valorMercancia: ' . $valorMercancia);
        Log::info('totalLogisticaChina: ' . $totalLogisticaChina);
       
        $this->desglose = [
            'Valor de Mercancía' => number_format($valorMercancia, 2, '.', ''),
            'Costo de Envío de Paquete' => number_format($costoFinal, 2, '.', ''),
            'Gestión Logística en China' => number_format($totalLogisticaChina, 2, '.', ''),
            '─ DETALLE DE SERVICIOS EN ORIGEN' => null,
            '   ├─ Gestión Administrativa en China' => number_format($comision, 2),
            '   └─ Documentación y Packing List' => number_format($factura, 2),

            '─ DETALLE DE FLETE Y SEGURO' => null,
            '   ├─ Pago Internacional' => number_format($pagoInternacional, 2),
            '   ├─ Costo de Envío Interno' => number_format($costoEnvioInterno, 2),
            '   └─ Seguro y Protección de Carga' => number_format($seguro, 2),

            '─ DETALLE DE OPERACIÓN Y LOGÍSTICA' => null,
            '   ├─ Almacenaje en China (' . ($tarifaAlmacen ?? 0.5) . ' USD/día x' . ($dias ?? 7) . ' días)' => $aplicarAdicionales ? number_format($almacen, 2) : 'No aplica (< 5 kg)',
            '   └─ Tasas de Exportación y Aduana' => $aplicarAdicionales ? number_format($impuestos, 2) : 'No aplica (< 5 kg)',
        ];

        // Poblar gastosAdicionales para el PDF (con todo el detalle)
        $this->gastosAdicionales = [
            'Gestión Logística en China' => $totalLogisticaChina
        ];


        return [
            'costo' => number_format($totalGeneral, 2, '.', ''),
            'tipo' => $tipoCobro,
            'pesoCobrable' => $pesoRedondeado,
            'unidad' => 'kg',
            'errores' => $errores,
            'advertencias' => $advertencias,
        ];
    }

    /**
     * Responder a la pregunta del precio
     */
    public function responder($respuesta)
    {
        $this->respuestaUsuario = $respuesta;
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
            'Costo de Envío de Paquete' => $this->desglose['Costo de Envío de Paquete'] ?? 0,
            'Gestión Logística en China' => $this->desglose['Gestión Logística en China'] ?? 0,
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
        ]);
    }

    /**
     * Limpiar formulario
     */
    public function limpiar()
    {
        $this->reset(['peso', 'largo', 'ancho', 'alto', 'valorMercancia', 'cantidad', 'dimensiones', 'resultado', 'desglose', 'mostrarPregunta', 'respuestaUsuario', 'items', 
            'temp_producto', 'temp_imagen', 'temp_manualImagen', 'temp_cantidad', 'temp_valor_unitario', 'temp_peso_unitario', 'temp_largo', 'temp_ancho', 'temp_alto',
            'clienteNombre', 'clienteEmail', 'clienteTelefono', 'clienteDireccion', 'clienteCiudad', 'gastosAdicionales']);
        
        $this->items = [];
        $this->syncUrl();
        session()->flash('info', 'Formulario limpiado.');
    }

    public function render()
    {
        return view('livewire.calculadora-aerea');
    }
}
