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
            'largo' => $request->largo,
            'ancho' => $request->ancho,
            'alto' => $request->alto,
            'cantidad' => $request->cantidad,
            'origen' => $request->origen ?? 'Puerto de Ningbo-Zhoushan',
            'destino' => $request->destino ?? 'Puerto de Iquique',
            'valorMercancia' => $request->valorMercancia ?? 0,
            'resultado' => $request->resultado,
            'desglose' => json_decode($request->desglose, true) ?? [],
            'tipoCobro' => $request->tipoCobro,
            'unidad' => $request->unidad,
            'valorFacturado' => $request->valorFacturado,
            'cbmFacturado' => $request->cbmFacturado,
        ];

        $view = (strtolower($data['tipoCarga']) === 'fcl') ? 'pdf.cotizacion-fcl' : 'pdf.cotizacion-lcl';
        $pdf = Pdf::loadView($view, $data);

        return $pdf->download('cotizacion-' . strtolower($data['tipoCarga']) . '-' . date('Ymd-His') . '.pdf');
    }
}
