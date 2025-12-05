<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CalculadoraMaritima extends Component
{

    // Tipo de carga
    public $tipoCarga = 'lcl'; // lcl, fcl, uld

    // Datos de entrada
    public $peso = '';
    public $largo = '';
    public $ancho = '';
    public $alto = '';
    public $origen = '';
    public $destino = '';
    public $valorMercancia = '';
    public $cantidad = '';

    // Tarifas configurables
    public $tarifaBase = 500;
    public $tarifaPorKg = 2.5;
    public $tarifaPorM3 = 50;
    public $seguro = 0;

    // Resultado
    public $resultado = null;
    public $desglose = [];

    // Estado de interacción con precio
    public $mostrarPregunta = false;
    public $respuestaUsuario = null; // 'si' o 'no'

    protected $rules = [
        'origen'         => 'required|string',
        'destino'        => 'required|string',
        'valorMercancia' => 'required|numeric|min:0',
        'peso'           => 'required|numeric|min:0',
        'largo'          => 'nullable|numeric|min:0',
        'ancho'          => 'nullable|numeric|min:0',
        'alto'           => 'nullable|numeric|min:0',
    ];

    /**
     * Método principal de cálculo
     * Implementa fórmulas básicas que puedes ajustar según tu lógica de negocio
     */
    public function calcular()
    {
        // Validación básica
        if (empty($this->peso)) {
            session()->flash('error', 'Por favor completa todos los campos requeridos.');
            return;
        }

        $peso = floatval($this->peso);
        $largo = floatval($this->largo);
        $ancho = floatval($this->ancho);
        $alto = floatval($this->alto);
        $valorMercancia = floatval($this->valorMercancia);
        $cantidad = $this->cantidad;

        // Reiniciar estado de pregunta
        $this->mostrarPregunta = false;
        $this->respuestaUsuario = null;

        $volumetricWeight = ($peso / 5000);
        $CBM = (($largo * $ancho * $alto) / 1000000);

        $shippingPackage = $this->calculateShippingPackage($volumetricWeight, $CBM);

        $total = (number_format($shippingPackage['costo'], 2, '.', ',') * $cantidad) + $valorMercancia;


        $this->resultado = number_format($total, 2, '.', ',');
        $this->mostrarPregunta = true;
        session()->flash('success', 'Cálculo completado exitosamente.');
    }

    /**
     * Cálculo para LCL (Less than Container Load)
     * Fórmula básica: tarifa base + (peso * tarifa por kg) + (volumen * tarifa por m3)
     */
    private function calculateShippingPackage(float $pesoKg, ?float $cbmReal)
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

        if ($cbmReal === null || $cbmReal < 0.01) {
            $tipoCobro  = 'Peso (W/M)';
            $valorUsado = ceil($pesoKg); // redondea hacia arriba

            if ($valorUsado >= 1 && $valorUsado <= 16 && isset($TARIFA_POR_KG[$valorUsado])) {
                $costoFinal = $TARIFA_POR_KG[$valorUsado];
            } else {
                $costoFinal = 2.5;
            }

            $costoFinal = $costoFinal * $valorUsado;
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
        $this->desglose = [
            'Tarifa Base' => number_format($this->valorMercancia, 2, '.', ','),
            'Costo por paquete (' . number_format($valorUsado, 3, '.', '') .' '. $tipoCobro.' )' => number_format($costoFinal, 2, '.', ''),
        ];

        return [
            'costo'      => number_format($costoFinal, 2, '.', ''),
            'tipo'       => $tipoCobro,
            'unidad'     => str_contains($tipoCobro, 'Peso') ? 'kg' : 'm³'
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
    public function cleanForm()
    {
        $this->reset(['peso', 'largo', 'ancho', 'alto', 'origen', 'destino', 'valorMercancia', 'resultado', 'desglose', 'mostrarPregunta', 'respuestaUsuario']);
        session()->flash('info', 'Formulario limpiado.');
    }
    public function render()
    {
        return view('livewire.calculadora-maritima');
    }
}
