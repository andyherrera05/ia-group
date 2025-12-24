<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Layout;


#[Layout('layouts.app')]
class CalculadoraAerea extends Component
{

    // Datos de entrada
    public $peso = '';
    public $largo = '';
    public $ancho = '';
    public $alto = '';
    public $valorMercancia = '';
    public $urgente = false;
    public $diasAlmacen = 7;

    // Tarifas configurables (más caras que marítimo)
    public $tarifaBase = 800;
    public $tarifaPorKg = 5;
    public $tarifaPorM3 = 150;

    // Resultado
    public $resultado = null;
    public $desglose = [];

    // Estado de interacción con precio
    public $mostrarPregunta = false;
    public $respuestaUsuario = null;

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

        $peso = floatval($this->peso);
        $largo = floatval($this->largo);
        $ancho = floatval($this->ancho);
        $alto = floatval($this->alto);
        $valorMercancia = floatval($this->valorMercancia);

        $resultado = $this->calcularCostoAereo($valorMercancia, $peso, $largo, $ancho, $alto);

        $this->resultado = number_format($resultado['costo'], 2, '.', ',');
        $this->mostrarPregunta = true;
        session()->flash('success', 'Cálculo completado exitosamente.');
    }
    /**
     * Cálculo para peso volumetrico
     */
    private function calcularPesoVolumetrico($longitudCm, $anchoCm, $alturaCm)
    {
        $volumen = ($longitudCm * $anchoCm * $alturaCm) / 5000;

        return $volumen;
    }


    function calcularCostoAereo(float $valorMercancia, ?float $pesoKg, ?float $longitudCm, ?float $anchoCm, ?float $alturaCm): array
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

        $pesoDimensional = null;
        $volumenCm3 = null;

        if (
            $longitudCm !== null && $anchoCm !== null && $alturaCm !== null &&
            $longitudCm > 0 && $anchoCm > 0 && $alturaCm > 0
        ) {
            $pesoDimensional = $this->calcularPesoVolumetrico($longitudCm, $anchoCm, $alturaCm);
        } else if ($longitudCm !== null || $anchoCm !== null || $alturaCm !== null) {
            $errores[] = "Faltan una o más dimensiones para calcular el peso dimensional.";
        }

        if ($pesoKg !== null && $pesoDimensional !== null) {
            $pesoCobrable = max($pesoKg, $pesoDimensional);
            $pesoRedondeado = ceil($pesoCobrable);
            $tipoCobro = ($pesoKg >= $pesoDimensional) ? 'Peso real' : 'Peso dimensional';
        } else if ($pesoKg === null && $pesoDimensional !== null) {
            $pesoCobrable = $pesoDimensional;
            $pesoRedondeado = ceil($pesoDimensional);
            $tipoCobro = 'Peso dimensional (estimado)';
            $advertencias[] = "No se ingresó peso real. Se cobra por volumen como mínimo. El costo final podría ser mayor si el paquete pesa más.";
        }
        // Caso 3: Solo peso → usar peso real, pero advertir
        else if ($pesoKg !== null && $pesoDimensional === null) {
            $pesoRedondeado = ceil($pesoKg);
            $tipoCobro = 'Peso real';
            $advertencias[] = "No se ingresaron dimensiones. Si el paquete es voluminoso, el costo final será mayor (se cobrará peso dimensional).";
        } else {
            return [
                'costo' => null,
                'tipo' => 'Datos insuficientes',
                'pesoCobrable' => null,
                'unidad' => 'kg',
                'errores' => ["Debe ingresar al menos el peso o las tres dimensiones del paquete."],
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

        // Grupo 1: Servicios en Origen (China)
        $subtotalOrigen = $comision + $factura;
        
        // Grupo 2: Eje Logístico Internacional
        $subtotalFlete = $costoFinal;
        
        // Grupo 3: Gestión y Protección
        $subtotalGestion = $seguro + $consolidacion + $almacen + $impuestos;

        // Cálculo de subtotales para el resumen
        $subtotalFleteAereo = $costoFinal;
        $subtotalGestionChina = $comision + $factura + $seguro + $consolidacion + $almacen + $impuestos;

        $this->desglose = [
            'Valor de la mercancía' => number_format($valorMercancia, 2),
            'Flete Aéreo Internacional' => number_format($subtotalFleteAereo, 2),
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
        $totalGeneral = $comision + $factura + $seguro + $totalAdicionalesPesados + $costoFlete;

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
            'detalle' => [
                'pesoReal' => $pesoKg !== null ? number_format($pesoKg, 2) : null,
                'pesoDimensional' => $pesoDimensional !== null ? number_format($pesoDimensional, 2) : null,
                'volumenCm3' => $volumenCm3
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
