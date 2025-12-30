<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CotizacionPDFController extends Controller
{
    public function generarPDF(Request $request)
    {
        $desglose_reporte = json_decode($request->desglose_reporte, true) ?? [];

        // Convertir imagen remota a base64 para mayor fiabilidad en el PDF
        if (!empty($desglose_reporte['imagen'])) {
            try {
                $imageData = file_get_contents($desglose_reporte['imagen']);
                if ($imageData !== false) {
                    $type = pathinfo($desglose_reporte['imagen'], PATHINFO_EXTENSION) ?: 'jpg';
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imageData);
                    $desglose_reporte['imagen'] = $base64;
                }
            } catch (\Exception $e) {
                // Si falla, mantenemos la URL original
            }
        }

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
            'desglose_reporte' => $desglose_reporte,

            // Nuevos campos de personalización
            'clienteNombre' => $request->clienteNombre,
            'clienteEmail' => $request->clienteEmail,
            'clienteTelefono' => $request->clienteTelefono,
            'clienteDireccion' => $request->clienteDireccion,
            'clienteCiudad' => $request->clienteCiudad,
            'agente' => json_decode($request->agente, true) ?? [],
        ];

        $view = (strtolower($data['tipoCarga']) === 'fcl') ? 'pdf.cotizacion-fcl' : 'pdf.cotizacion-lcl';
        $pdf = Pdf::loadView($view, $data)->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);

        return $pdf->download('cotizacion-' . strtolower($data['tipoCarga']) . '-' . date('Ymd-His') . '.pdf');
    }
}
