<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CotizacionPDFController extends Controller
{
        public function generarPDF(Request $request)
    {
        $data = [
            'tipoCarga' => $request->tipoCarga ?? 'LCL',
            'fecha' => now()->format('d/m/Y H:i'),
            'peso' => $request->peso,
            'volumen' => $request->volumen,
            'origen' => $request->origen ?? 'No especificado',
            'destino' => $request->destino ?? 'No especificado',
            'valorMercancia' => $request->valorMercancia ?? 0,
            'resultado' => $request->resultado,
            'desglose' => json_decode($request->desglose, true) ?? [],
        ];

        $pdf = Pdf::loadView('pdf.cotizacion-lcl', $data);
        
        return $pdf->download('cotizacion-' . strtolower($data['tipoCarga']) . '-' . date('Ymd-His') . '.pdf');
    }
}
