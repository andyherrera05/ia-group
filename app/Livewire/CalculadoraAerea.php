<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Layout;


use Livewire\Attributes\Url;

#[Layout('layouts.app')]
class CalculadoraAerea extends Component
{
    #[Url]
    public $q;

    #[Url]
    #[Url]
    public $producto = '';

    // Datos de entrada
    #[Url]
    public $peso = '';
    
    public $largo = '';
    public $ancho = '';
    public $alto = '';

    #[Url]
    public $valorMercancia = '';

    #[Url]
    public $cantidad = 1;

    #[Url]
    #[Url]
    public $dimensiones = '';
    public $urgente = false;
    public $diasAlmacen = 7;

    // Tarifas configurables (más caras que marítimo)
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

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['peso', 'cantidad', 'largo', 'ancho', 'alto', 'valorMercancia'])) {
            $this->calcular();
        }
    }

    public function mount()
    {
        // Capturar parámetros directamente del request
        $this->producto = request()->input('producto') ?? $this->producto;
        $this->peso = request()->input('peso') ?? $this->peso;
        $this->valorMercancia = request()->input('valorMercancia') ?? $this->valorMercancia;
        $this->cantidad = request()->input('cantidad') ?? $this->cantidad;
        $this->dimensiones = request()->input('dimensiones') ?? $this->dimensiones;

        if ($this->q) {
            try {
                $data = json_decode(base64_decode($this->q), true);
                if ($data) {
                    $this->peso = $data['peso'] ?? $this->peso;
                    $this->valorMercancia = $data['valorMercancia'] ?? $this->valorMercancia;
                    $this->cantidad = $data['cantidad'] ?? $this->cantidad;
                    $this->producto = $data['producto'] ?? $this->producto;
                    if (isset($data['dimensiones'])) {
                        $this->dimensiones = $data['dimensiones'];
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error decoding air calculator params: " . $e->getMessage());
            }
        }

        if ($this->dimensiones) {
            $dims = explode('x', str_replace(' ', '', strtolower($this->dimensiones)));
            if (count($dims) === 3) {
                $this->largo = $dims[0];
                $this->ancho = $dims[1];
                $this->alto = $dims[2];
            }
        }

        if ($this->peso || ($this->largo && $this->ancho && $this->alto)) {
            $this->calcular();
        }
    }

    /**
     * Método principal de cálculo
     */
    public function calcular()
    {
        if (empty($this->peso)) {
            session()->flash('error', 'Por favor completa todos los campos requeridos.');
            return;
        }

        // Reiniciar estado de pregunta
        $this->mostrarPregunta = false;
        $this->respuestaUsuario = null;

        $cantidad = intval($this->cantidad) ?: 1;
        $margen = 1.08; // 8% margen de empaque

        $pesoUnitario = floatval($this->peso);
        $largo = floatval($this->largo);
        $ancho = floatval($this->ancho);
        $alto = floatval($this->alto);
        
        // Peso Total con margen
        $pesoTotal = ($pesoUnitario * $cantidad) * $margen;
        
        // Peso Volumétrico Total con margen
        $pesoDimensionalTotal = 0;
        if ($largo > 0 && $ancho > 0 && $alto > 0) {
            $volumenUnitario = ($largo * $ancho * $alto);
            $volumenTotal = ($volumenUnitario * $cantidad) * $margen;
            $pesoDimensionalTotal = $volumenTotal / 5000;
        }

        $valorMercanciaUnidad = floatval($this->valorMercancia);
        $valorMercanciaTotal = $valorMercanciaUnidad * $cantidad;

        $this->pesoTotalCalculado = number_format($pesoTotal, 2);
        $this->pesoDimensionalTotalCalculado = number_format($pesoDimensionalTotal, 2);

        $resultado = $this->calcularCostoAereo($valorMercanciaTotal, $pesoTotal, $pesoDimensionalTotal);

        $this->resultado = number_format($resultado['costo'], 2, '.', ',');
        $this->mostrarPregunta = true;
        session()->flash('success', 'Cálculo completado considerando cantidad y margen de empaque.');
    }


    function calcularCostoAereo(float $valorMercancia, ?float $pesoTotalKg, ?float $pesoDimensionalTotal): array
    {
        $tarifas = [
            ['maxKg' => 1,   'tarifa' => 14.5],
            ['maxKg' => 5,   'tarifa' => 14.5],
            ['maxKg' => 10,  'tarifa' => 13.5],
            ['maxKg' => 15,  'tarifa' => 13.5],
            ['maxKg' => 20,  'tarifa' => 13.5],
            ['maxKg' => 25,  'tarifa' => 13.0],
            ['maxKg' => 26,  'tarifa' => 12.5],
            ['maxKg' => 30,  'tarifa' => 12.5],
            ['maxKg' => 35,  'tarifa' => 11.5],
            ['maxKg' => 40,  'tarifa' => 11.5],
            ['maxKg' => 45,  'tarifa' => 11.0],
            ['maxKg' => 50,  'tarifa' => 10.5],
            ['maxKg' => 55,  'tarifa' => 10.5],
            ['maxKg' => 60,  'tarifa' => 10.5],
            ['maxKg' => 70,  'tarifa' => 9.5],
            ['maxKg' => 80,  'tarifa' => 9.5],
            ['maxKg' => 90,  'tarifa' => 9.5],
            ['maxKg' => 100, 'tarifa' => 9.5],
            ['maxKg' => 150, 'tarifa' => 9.5],
            ['maxKg' => 200, 'tarifa' => 9.5],
            ['maxKg' => PHP_INT_MAX, 'tarifa' => 9.5]
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

        $consolidacion = 0;
        $almacen       = 0;
        $impuestos     = 0;

        $aplicarAdicionales = ($pesoRedondeado > 5);

        if ($aplicarAdicionales) {
            // Tabla de consolidación dinámica
            if ($pesoRedondeado <= 25) {
                $consolidacion = 10;
            } elseif ($pesoRedondeado <= 50) {
                $consolidacion = 18;
            } else {
                $consolidacion = 24;
            }
            
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

        $subtotalGestionChina = $comision + $factura + $seguro + $consolidacion + $almacen + $impuestos;

        $this->desglose = [
            'Valor de la mercancía' => number_format($valorMercancia, 2),
            'Flete Aéreo Internacional' => number_format($costoFinal, 2),
            'Gestión Logística en China' => number_format($subtotalGestionChina, 2),
            
            '─ DETALLE DE SERVICIOS EN ORIGEN' => null,
            '   ├─ Gestión Administrativa en China' => number_format($comision, 2),
            '   └─ Documentación y Packing List' => number_format($factura, 2),

            '─ DETALLE DE FLETE Y SEGURO' => null,
            '   ├─ Flete Internacional Aéreo Express' => number_format($tarifaPorKg, 2),
            '   └─ Seguro y Protección de Carga' => number_format($seguro, 2),

            '─ DETALLE DE OPERACIÓN Y LOGÍSTICA' => null,
            '   ├─ Consolidación y Verificación' => $aplicarAdicionales ? number_format($consolidacion, 2) : 'No aplica (< 5 kg)',
            '   ├─ Almacenaje en China (' . ($tarifaAlmacen ?? 0.5) . ' USD/día x' . ($dias ?? 7) . ' días)' => $aplicarAdicionales ? number_format($almacen, 2) : 'No aplica (< 5 kg)',
            '   └─ Tasas de Exportación y Aduana' => $aplicarAdicionales ? number_format($impuestos, 2) : 'No aplica (< 5 kg)',
        ];

        // Cálculo del total general
        $totalAdicionalesPesados = $consolidacion + $almacen + $impuestos;
        $costoFlete = $costoFinal;
        $totalGeneral = $valorMercancia + $comision + $factura + $seguro + $totalAdicionalesPesados + $costoFlete;

        if (!$aplicarAdicionales) {
            $this->desglose['Nota'] = 'Costos de consolidación, almacén e impuestos no aplican para envíos ≤ 5 kg cobrables.';
        }


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

    /**
     * Limpiar formulario
     */
    public function limpiar()
    {
        $this->reset(['peso', 'largo', 'ancho', 'alto', 'valorMercancia', 'urgente', 'resultado', 'desglose', 'mostrarPregunta', 'respuestaUsuario', 'diasAlmacen']);
        $this->diasAlmacen = 7; // Asegurar valor por defecto
        session()->flash('info', 'Formulario limpiado.');
    }

    public function render()
    {
        return view('livewire.calculadora-aerea');
    }
}
