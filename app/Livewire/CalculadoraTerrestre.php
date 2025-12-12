<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CalculadoraTerrestre extends Component
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
    public $distancia = ''; // km
    public $valorMercancia = '';
    
    // Tarifas configurables
    public $tarifaBase = 300;
    public $tarifaPorKg = 1.2;
    public $tarifaPorKm = 0.5;
    
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
        if (empty($this->peso) || empty($this->volumen) || empty($this->distancia)) {
            session()->flash('error', 'Por favor completa todos los campos requeridos.');
            return;
        }
        
        // Reiniciar estado de pregunta
        $this->mostrarPregunta = false;
        $this->respuestaUsuario = null;
        
        $peso = floatval($this->peso);
        $volumen = floatval($this->volumen);
        $distancia = floatval($this->distancia);
        $valorMercancia = floatval($this->valorMercancia);
        
        switch ($this->tipoCarga) {
            case 'lcl':
                $resultado = $this->calcularLCL($peso, $volumen, $distancia, $valorMercancia);
                break;
            case 'fcl':
                $resultado = $this->calcularFCL($peso, $volumen, $distancia, $valorMercancia);
                break;
            case 'uld':
                $resultado = $this->calcularULD($peso, $volumen, $distancia, $valorMercancia);
                break;
            default:
                $resultado = 0;
        }
        
        $this->resultado = number_format($resultado, 2, '.', ',');
        $this->mostrarPregunta = true;
        session()->flash('success', 'Cálculo completado exitosamente.');
    }
    
    /**
     * Cálculo para LCL Terrestre
     */
    private function calcularLCL($peso, $volumen, $distancia, $valorMercancia)
    {
        $costoPeso = $peso * $this->tarifaPorKg;
        $costoDistancia = $distancia * $this->tarifaPorKm;
        $costoSeguro = $valorMercancia > 0 ? ($valorMercancia * 0.015) : 0; // 1.5% del valor
        $peajes = $distancia > 500 ? 50 : 25; // Estimación de peajes
        
        $total = $this->tarifaBase + $costoPeso + $costoDistancia + $costoSeguro + $peajes;
        
        $this->desglose = [
            'Tarifa Base' => number_format($this->tarifaBase, 2, '.', ','),
            'Costo por Peso (' . $peso . ' kg)' => number_format($costoPeso, 2, '.', ','),
            'Costo por Distancia (' . $distancia . ' km)' => number_format($costoDistancia, 2, '.', ','),
            'Peajes Estimados' => number_format($peajes, 2, '.', ','),
            'Seguro (1.5%)' => number_format($costoSeguro, 2, '.', ','),
        ];
        
        return $total;
    }
    
    /**
     * Cálculo para FCL Terrestre (camión completo)
     */
    private function calcularFCL($peso, $volumen, $distancia, $valorMercancia)
    {
        $tarifaCamion = 2000; // Tarifa base por camión completo
        $costoDistancia = $distancia * 1.2; // Tarifa premium por km para camión completo
        $costoSeguro = $valorMercancia > 0 ? ($valorMercancia * 0.012) : 0;
        $peajes = $distancia > 500 ? 80 : 40;
        $recargoPeso = $peso > 15000 ? ($peso - 15000) * 0.3 : 0;
        
        $total = $tarifaCamion + $costoDistancia + $costoSeguro + $peajes + $recargoPeso;
        
        $this->desglose = [
            'Tarifa Camión Completo' => number_format($tarifaCamion, 2, '.', ','),
            'Costo por Distancia (' . $distancia . ' km)' => number_format($costoDistancia, 2, '.', ','),
            'Recargo Peso Excedente' => number_format($recargoPeso, 2, '.', ','),
            'Peajes Estimados' => number_format($peajes, 2, '.', ','),
            'Seguro (1.2%)' => number_format($costoSeguro, 2, '.', ','),
        ];
        
        return $total;
    }
    
    /**
     * Cálculo para ULD Terrestre
     */
    private function calcularULD($peso, $volumen, $distancia, $valorMercancia)
    {
        $tarifaPallet = 1200; // Tarifa por pallet
        $numPallets = ceil($volumen / 5); // Aprox 5 m³ por pallet terrestre
        $costoDistancia = ($distancia * 0.8) * $numPallets;
        $costoSeguro = $valorMercancia > 0 ? ($valorMercancia * 0.014) : 0;
        $peajes = $distancia > 500 ? 60 : 30;
        
        $total = ($tarifaPallet * $numPallets) + $costoDistancia + $costoSeguro + $peajes;
        
        $this->desglose = [
            'Pallets Requeridos' => $numPallets,
            'Costo por Pallet' => number_format($tarifaPallet * $numPallets, 2, '.', ','),
            'Costo por Distancia' => number_format($costoDistancia, 2, '.', ','),
            'Peajes Estimados' => number_format($peajes, 2, '.', ','),
            'Seguro (1.4%)' => number_format($costoSeguro, 2, '.', ','),
        ];
        
        return $total;
    }
    
    /**
     * Calcular volumen
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
        $this->reset(['peso', 'volumen', 'largo', 'ancho', 'alto', 'origen', 'destino', 'distancia', 'valorMercancia', 'resultado', 'desglose', 'mostrarPregunta', 'respuestaUsuario']);
        session()->flash('info', 'Formulario limpiado.');
    }
    
    public function render()
    {
        return view('livewire.calculadora-terrestre');
    }
      
}
