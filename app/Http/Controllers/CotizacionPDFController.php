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
                } catch (\Exception $e) {
                }
            }
        }

        $containerBase64 = null;
        $containerPaths = [
            public_path('images/container.png'),
        ];

        foreach ($containerPaths as $containerPath) {
            if (file_exists($containerPath)) {
                try {
                    $containerData = file_get_contents($containerPath);
                    $containerBase64 = 'data:image/png;base64,' . base64_encode($containerData);
                    break;
                } catch (\Exception $e) {
                }
            }
        }

        // Contexto para timeout de 3 segundos
        $ctx = stream_context_create(['http' => ['timeout' => 3]]);

        // 2. Convertir imagen del producto a base64 (solo si no es local)
        if (!empty($desglose_reporte['imagen'])) {
            $url = $desglose_reporte['imagen'];
            $isLocal = str_contains($url, '127.0.0.1') || str_contains($url, 'localhost');

            if (!$isLocal) {
                try {
                    $imageData = @file_get_contents($url, false, $ctx);
                    if ($imageData !== false && !empty($imageData)) {
                        $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
                        // limpieza básica de query params en extensión
                        $ext = explode('?', $ext)[0];

                        $mime = match ($ext) {
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
            'fecha' => now()->toDateTimeString(),
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
            'p2pPrice' => $request->p2pPrice,
            'agente' => json_decode($request->agente, true) ?? [],
            'gastosAdicionales' => json_decode($request->gastosAdicionales, true) ?? [],
            'logoBase64' => $logoBase64,
            'containerBase64' => $containerBase64,
            'productos' => [], // Default empty
        ];

        // Procesar lista multiproducto
        $productos = json_decode($request->productos, true);
        if (is_array($productos)) {
            // Reutilizamos contexto
            $ctx = stream_context_create(['http' => ['timeout' => 3]]);

            foreach ($productos as &$prod) {
                if (!empty($prod['imagen'])) {
                    $url = $prod['imagen'];
                    $isLocal = str_contains($url, '127.0.0.1') || str_contains($url, 'localhost');
                    if (!$isLocal) {
                        try {
                            $imageData = @file_get_contents($url, false, $ctx);
                            if ($imageData !== false && !empty($imageData)) {
                                $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
                                $ext = explode('?', $ext)[0]; // Limpieza
                                $mime = match ($ext) {
                                    'png' => 'image/png',
                                    'gif' => 'image/gif',
                                    'svg' => 'image/svg+xml',
                                    'webp' => 'image/webp',
                                    default => 'image/jpeg'
                                };
                                $prod['imagen'] = 'data:' . $mime . ';base64,' . base64_encode($imageData);
                            }
                        } catch (\Exception $e) {
                        }
                    }
                }
            }
            $data['productos'] = $productos;
        }

        // Procesar vehiculo (RoRo)
        $vehiculo = json_decode($request->vehiculo, true);
        if (is_array($vehiculo)) {
            // Reutilizamos contexto
            $ctx = stream_context_create(['http' => ['timeout' => 3]]);

            if (!empty($vehiculo['imagen'])) {
                $url = $vehiculo['imagen'];
                $isLocal = str_contains($url, '127.0.0.1') || str_contains($url, 'localhost');
                if (!$isLocal) {
                    try {
                        $imageData = @file_get_contents($url, false, $ctx);
                        if ($imageData !== false && !empty($imageData)) {
                            $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
                            $ext = explode('?', $ext)[0]; // Limpieza
                            $mime = match ($ext) {
                                'png' => 'image/png',
                                'gif' => 'image/gif',
                                'svg' => 'image/svg+xml',
                                'webp' => 'image/webp',
                                default => 'image/jpeg'
                            };
                            $vehiculo['imagen'] = 'data:' . $mime . ';base64,' . base64_encode($imageData);
                        }
                    } catch (\Exception $e) {
                    }
                }
            }
            $data['vehiculo'] = $vehiculo;
        }

        $view = match (strtolower($data['tipoCarga'])) {
            'fcl' => 'pdf.cotizacion-fcl',
            'fcl_maritimo' => 'pdf.cotizacion-fcl-maritimo',
            'uld' => 'pdf.cotizacion-roro',
            default => 'pdf.cotizacion-lcl',
        };
        $pdf = Pdf::loadView($view, $data)->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);

        return $pdf->download('cotizacion-' . strtolower($data['tipoCarga']) . '-' . date('Ymd-His') . '.pdf');
    }
}
