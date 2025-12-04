<?php

namespace App\Livewire;

use Livewire\Component;

class CalculadoraMaritima extends Component
{
    // Tipo de carga
    public $tipoCarga = 'lcl'; // lcl, fcl, uld
    
    // Datos de entrada
    public $peso = '';
    public $volumen = '';
    public $largo = '';
    public $ancho = '';
    public $alto = '';
    public $origen = '';
    public $destino = '';
    public $valorMercancia = '';
    
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
    
    /**
     * Método principal de cálculo
     * Implementa fórmulas básicas que puedes ajustar según tu lógica de negocio
     */
    public function calcular()
    {
        // Validación básica
        if (empty($this->peso) || empty($this->volumen)) {
            session()->flash('error', 'Por favor completa todos los campos requeridos.');
            return;
        }
        
        $peso = floatval($this->peso);
        $volumen = floatval($this->volumen);
        $valorMercancia = floatval($this->valorMercancia);
        
        // Reiniciar estado de pregunta
        $this->mostrarPregunta = false;
        $this->respuestaUsuario = null;
        
        // Cálculo según tipo de carga
        switch ($this->tipoCarga) {
            case 'lcl':
                $resultado = $this->calcularLCL($peso, $volumen, $valorMercancia);
                break;
            case 'fcl':
                $resultado = $this->calcularFCL($peso, $volumen, $valorMercancia);
                break;
            case 'uld':
                $resultado = $this->calcularULD($peso, $volumen, $valorMercancia);
                break;
            default:
                $resultado = 0;
        }
        
        $this->resultado = number_format($resultado, 2, '.', ',');
        $this->mostrarPregunta = true;
        session()->flash('success', 'Cálculo completado exitosamente.');
    }
    
    /**
     * Cálculo para LCL (Less than Container Load)
     * Fórmula básica: tarifa base + (peso * tarifa por kg) + (volumen * tarifa por m3)
     */
    private function calcularLCL($peso, $volumen, $valorMercancia)
    {
        $costoPeso = $peso * $this->tarifaPorKg;
        $costoVolumen = $volumen * $this->tarifaPorM3;
        $costoSeguro = $valorMercancia > 0 ? ($valorMercancia * 0.02) : 0; // 2% del valor
        
        $total = $this->tarifaBase + $costoPeso + $costoVolumen + $costoSeguro;
        
        $this->desglose = [
            'Tarifa Base' => number_format($this->tarifaBase, 2, '.', ','),
            'Costo por Peso (' . $peso . ' kg)' => number_format($costoPeso, 2, '.', ','),
            'Costo por Volumen (' . $volumen . ' m³)' => number_format($costoVolumen, 2, '.', ','),
            'Seguro (2%)' => number_format($costoSeguro, 2, '.', ','),
        ];
        
        return $total;
    }
    
    /**
     * Cálculo para FCL (Full Container Load)
     * Tarifa fija por contenedor + adicionales
     */
    private function calcularFCL($peso, $volumen, $valorMercancia)
    {
        $tarifaContenedor = 3500; // Tarifa fija por contenedor completo
        $recargoPeso = $peso > 20000 ? ($peso - 20000) * 0.5 : 0; // Recargo si excede 20 toneladas
        $costoSeguro = $valorMercancia > 0 ? ($valorMercancia * 0.015) : 0; // 1.5% del valor
        
        $total = $tarifaContenedor + $recargoPeso + $costoSeguro;
        
        $this->desglose = [
            'Tarifa Contenedor Completo' => number_format($tarifaContenedor, 2, '.', ','),
            'Recargo por Peso Excedente' => number_format($recargoPeso, 2, '.', ','),
            'Seguro (1.5%)' => number_format($costoSeguro, 2, '.', ','),
        ];
        
        return $total;
    }
    
    /**
     * Cálculo para ULD (Unit Load Device)
     * Tarifa por unidad de carga
     */
    private function calcularULD($peso, $volumen, $valorMercancia)
    {
        $tarifaULD = 1800; // Tarifa por ULD
        $numULDs = ceil($volumen / 6); // Aproximadamente 6 m³ por ULD
        $costoPeso = $peso * 1.8;
        $costoSeguro = $valorMercancia > 0 ? ($valorMercancia * 0.018) : 0; // 1.8% del valor
        
        $total = ($tarifaULD * $numULDs) + $costoPeso + $costoSeguro;
        
        $this->desglose = [
            'ULDs Requeridos' => $numULDs,
            'Costo por ULD' => number_format($tarifaULD * $numULDs, 2, '.', ','),
            'Costo por Peso' => number_format($costoPeso, 2, '.', ','),
            'Seguro (1.8%)' => number_format($costoSeguro, 2, '.', ','),
        ];
        
        return $total;
    }
    
    /**
     * Calcular dimensiones volumétricas
     */
    public function calcularVolumen()
    {
        if (!empty($this->largo) && !empty($this->ancho) && !empty($this->alto)) {
            $this->volumen = number_format(
                ($this->largo * $this->ancho * $this->alto) / 1000000, 
                3, 
                '.', 
                ''
            );
        }
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
        $this->reset(['peso', 'volumen', 'largo', 'ancho', 'alto', 'origen', 'destino', 'valorMercancia', 'resultado', 'desglose', 'mostrarPregunta', 'respuestaUsuario']);
        session()->flash('info', 'Formulario limpiado.');
    }

    public function render()
    {
        return view('livewire.calculadora-maritima')
            ->layout('layouts.app', ['title' => 'Calculadora Marítima']);
    }
}
