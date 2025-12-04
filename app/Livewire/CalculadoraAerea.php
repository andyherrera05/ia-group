<?php

namespace App\Livewire;

use Livewire\Component;

class CalculadoraAerea extends Component
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
    public $urgente = false;
    
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
        if (empty($this->peso) || empty($this->volumen)) {
            session()->flash('error', 'Por favor completa todos los campos requeridos.');
            return;
        }
        
        // Reiniciar estado de pregunta
        $this->mostrarPregunta = false;
        $this->respuestaUsuario = null;
        
        $peso = floatval($this->peso);
        $volumen = floatval($this->volumen);
        $valorMercancia = floatval($this->valorMercancia);
        
        // Peso volumétrico para carga aérea (importante en aéreo)
        $pesoVolumetrico = $volumen * 167; // Factor estándar IATA
        $pesoFacturable = max($peso, $pesoVolumetrico);
        
        switch ($this->tipoCarga) {
            case 'lcl':
                $resultado = $this->calcularLCL($pesoFacturable, $volumen, $valorMercancia);
                break;
            case 'fcl':
                $resultado = $this->calcularFCL($pesoFacturable, $volumen, $valorMercancia);
                break;
            case 'uld':
                $resultado = $this->calcularULD($pesoFacturable, $volumen, $valorMercancia);
                break;
            default:
                $resultado = 0;
        }
        
        // Recargo por servicio urgente
        if ($this->urgente) {
            $recargo = $resultado * 0.30; // 30% de recargo
            $this->desglose['Recargo Urgente (30%)'] = number_format($recargo, 2, '.', ',');
            $resultado += $recargo;
        }
        
        $this->resultado = number_format($resultado, 2, '.', ',');
        $this->mostrarPregunta = true;
        session()->flash('success', 'Cálculo completado exitosamente.');
    }
    
    /**
     * Cálculo para LCL Aéreo
     */
    private function calcularLCL($pesoFacturable, $volumen, $valorMercancia)
    {
        $costoPeso = $pesoFacturable * $this->tarifaPorKg;
        $costoSeguro = $valorMercancia > 0 ? ($valorMercancia * 0.025) : 0; // 2.5% del valor
        $recargoCombustible = $costoPeso * 0.15; // 15% recargo por combustible
        
        $total = $this->tarifaBase + $costoPeso + $costoSeguro + $recargoCombustible;
        
        $this->desglose = [
            'Tarifa Base' => number_format($this->tarifaBase, 2, '.', ','),
            'Costo por Peso Facturable (' . number_format($pesoFacturable, 2) . ' kg)' => number_format($costoPeso, 2, '.', ','),
            'Recargo Combustible (15%)' => number_format($recargoCombustible, 2, '.', ','),
            'Seguro (2.5%)' => number_format($costoSeguro, 2, '.', ','),
        ];
        
        return $total;
    }
    
    /**
     * Cálculo para FCL Aéreo
     */
    private function calcularFCL($pesoFacturable, $volumen, $valorMercancia)
    {
        $tarifaPallet = 2800; // Tarifa por pallet completo
        $numPallets = ceil($volumen / 4); // Aprox 4 m³ por pallet
        $costoSeguro = $valorMercancia > 0 ? ($valorMercancia * 0.02) : 0;
        $recargoCombustible = ($tarifaPallet * $numPallets) * 0.15;
        
        $total = ($tarifaPallet * $numPallets) + $costoSeguro + $recargoCombustible;
        
        $this->desglose = [
            'Pallets Requeridos' => $numPallets,
            'Costo por Pallet' => number_format($tarifaPallet * $numPallets, 2, '.', ','),
            'Recargo Combustible (15%)' => number_format($recargoCombustible, 2, '.', ','),
            'Seguro (2%)' => number_format($costoSeguro, 2, '.', ','),
        ];
        
        return $total;
    }
    
    /**
     * Cálculo para ULD Aéreo
     */
    private function calcularULD($pesoFacturable, $volumen, $valorMercancia)
    {
        $tarifaULD = 4500; // Tarifa por ULD aéreo
        $numULDs = ceil($volumen / 10); // Aprox 10 m³ por ULD aéreo
        $costoSeguro = $valorMercancia > 0 ? ($valorMercancia * 0.022) : 0;
        $recargoCombustible = ($tarifaULD * $numULDs) * 0.15;
        
        $total = ($tarifaULD * $numULDs) + $costoSeguro + $recargoCombustible;
        
        $this->desglose = [
            'ULDs Requeridos' => $numULDs,
            'Costo por ULD' => number_format($tarifaULD * $numULDs, 2, '.', ','),
            'Recargo Combustible (15%)' => number_format($recargoCombustible, 2, '.', ','),
            'Seguro (2.2%)' => number_format($costoSeguro, 2, '.', ','),
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
        $this->reset(['peso', 'volumen', 'largo', 'ancho', 'alto', 'origen', 'destino', 'valorMercancia', 'urgente', 'resultado', 'desglose', 'mostrarPregunta', 'respuestaUsuario']);
        session()->flash('info', 'Formulario limpiado.');
    }

    public function render()
    {
        return view('livewire.calculadora-aerea')
            ->layout('layouts.app', ['title' => 'Calculadora Aérea']);
    }
}
