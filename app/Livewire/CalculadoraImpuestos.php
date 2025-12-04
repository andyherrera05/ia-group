<?php

namespace App\Livewire;

use Livewire\Component;

class CalculadoraImpuestos extends Component
{
    // Datos de entrada
    public $valorMercancia = '';
    public $valorFlete = '';
    public $valorSeguro = '';
    public $paisOrigen = '';
    public $categoria = 'general'; // general, alimentos, tecnologia, textil
    
    // Tasas configurables (estas varían por país y categoría)
    public $tasaArancel = 10; // %
    public $tasaIVA = 16; // %
    public $tasaDUA = 1; // % Declaración Única Aduanera
    
    // Resultado
    public $resultado = null;
    public $desglose = [];
    
    // Estado de interacción
    public $mostrarPregunta = false;
    public $respuestaUsuario = null;
    
    /**
     * Método principal de cálculo
     */
    public function calcular()
    {
        if (empty($this->valorMercancia)) {
            session()->flash('error', 'Por favor ingresa el valor de la mercancía.');
            return;
        }
        
        // Reiniciar estado de pregunta
        $this->mostrarPregunta = false;
        $this->respuestaUsuario = null;
        
        $valorMercancia = floatval($this->valorMercancia);
        $valorFlete = floatval($this->valorFlete);
        $valorSeguro = floatval($this->valorSeguro);
        
        // Ajustar tasas según categoría
        $this->ajustarTasasPorCategoria();
        
        // Calcular Base Imponible (CIF: Cost, Insurance, Freight)
        $baseImponible = $valorMercancia + $valorFlete + $valorSeguro;
        
        // Calcular Arancel
        $arancel = $baseImponible * ($this->tasaArancel / 100);
        
        // Base para IVA (Base Imponible + Arancel)
        $baseIVA = $baseImponible + $arancel;
        
        // Calcular IVA
        $iva = $baseIVA * ($this->tasaIVA / 100);
        
        // Calcular DUA
        $dua = $baseImponible * ($this->tasaDUA / 100);
        
        // Total de impuestos
        $totalImpuestos = $arancel + $iva + $dua;
        
        // Valor total a pagar (mercancía + impuestos)
        $totalAPagar = $baseImponible + $totalImpuestos;
        
        $this->desglose = [
            'Valor Mercancía (FOB)' => number_format($valorMercancia, 2, '.', ','),
            'Valor Flete' => number_format($valorFlete, 2, '.', ','),
            'Valor Seguro' => number_format($valorSeguro, 2, '.', ','),
            'Base Imponible (CIF)' => number_format($baseImponible, 2, '.', ','),
            'Arancel (' . $this->tasaArancel . '%)' => number_format($arancel, 2, '.', ','),
            'IVA (' . $this->tasaIVA . '%)' => number_format($iva, 2, '.', ','),
            'DUA (' . $this->tasaDUA . '%)' => number_format($dua, 2, '.', ','),
            'Total Impuestos' => number_format($totalImpuestos, 2, '.', ','),
            'Total a Pagar' => number_format($totalAPagar, 2, '.', ','),
        ];
        
        $this->resultado = number_format($totalImpuestos, 2, '.', ',');
        $this->mostrarPregunta = true;
        session()->flash('success', 'Cálculo de impuestos completado.');
    }
    
    /**
     * Ajustar tasas según la categoría del producto
     */
    private function ajustarTasasPorCategoria()
    {
        switch ($this->categoria) {
            case 'alimentos':
                $this->tasaArancel = 5; // Menor arancel para alimentos
                $this->tasaIVA = 0; // Algunos alimentos están exentos
                break;
            case 'tecnologia':
                $this->tasaArancel = 15; // Mayor arancel para tecnología
                $this->tasaIVA = 16;
                break;
            case 'textil':
                $this->tasaArancel = 20; // Arancel alto para textiles
                $this->tasaIVA = 16;
                break;
            case 'general':
            default:
                $this->tasaArancel = 10;
                $this->tasaIVA = 16;
                break;
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
        $this->reset(['valorMercancia', 'valorFlete', 'valorSeguro', 'paisOrigen', 'resultado', 'desglose', 'mostrarPregunta', 'respuestaUsuario']);
        $this->categoria = 'general';
        session()->flash('info', 'Formulario limpiado.');
    }

    public function render()
    {
        return view('livewire.calculadora-impuestos')
            ->layout('layouts.app', ['title' => 'Calculadora de Impuestos']);
    }
}
