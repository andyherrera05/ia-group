<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CotizacionPDFController extends Controller
{
    public function generarPDF(Request $request)
    {
        $desglose_reporte = json_decode($request->desglose_reporte, true) ?? [];

        // 1. Cargar Logo robustamente (Base64)
        $logoBase64 = null;
        $logoPaths = [
            public_path('images/logo_amarillo.png'),
            public_path('images/logo.png'),
            public_path('images/logo-ia.png')
        ];

        foreach ($logoPaths as $path) {
            if (file_exists($path)) {
                try {
                    $logoData = file_get_contents($path);
                    $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
                    break;
                } catch (\Exception $e) {}
            }
        }

        // 2. Convertir imagen del producto a base64 (solo si no es local)
        if (!empty($desglose_reporte['imagen'])) {
            $url = $desglose_reporte['imagen'];
            $isLocal = str_contains($url, '127.0.0.1') || str_contains($url, 'localhost');

            if (!$isLocal) {
                try {
                    $imageData = @file_get_contents($url);
                    if ($imageData !== false && !empty($imageData)) {
                        $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
                        $mime = match($ext) {
                            'png' => 'image/png',
                            'gif' => 'image/gif',
                            'svg' => 'image/svg+xml',
                            'webp' => 'image/webp',
                            default => 'image/jpeg'
                        };
                        $desglose_reporte['imagen'] = 'data:' . $mime . ';base64,' . base64_encode($imageData);
                    }
                } catch (\Exception $e) {
                    // Si falla, mantenemos la URL original o limpiamos si causa error
                }
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
            'gastosAdicionales' => json_decode($request->gastosAdicionales, true) ?? [],
            'logoBase64' => $logoBase64,
        ];

        $view = (strtolower($data['tipoCarga']) === 'fcl') ? 'pdf.cotizacion-fcl' : 'pdf.cotizacion-lcl';
        $pdf = Pdf::loadView($view, $data)->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);

        return $pdf->download('cotizacion-' . strtolower($data['tipoCarga']) . '-' . date('Ymd-His') . '.pdf');
    }
}
