<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ScraperController extends Controller
{
    protected $guzzle;
    protected $token;
    protected $actorId;

    public function __construct()
    {
        $this->guzzle = new Guzzle();
        $this->token = config('services.apify.token');
        $this->actorId = config('services.apify.product_actor_id');

        if (!$this->token || !$this->actorId) {
            throw new \Exception('APIFY_TOKEN o PRODUCT_ACTOR_ID no están configurados.');
        }
    }

    private function token()
    {
        return $this->token;
    }

    private function actorId()
    {
        return $this->actorId;
    }

    public function scrape(Request $request)
    {
        $request->validate(['url' => 'required|url']);

        $url = preg_replace('/\?spm=.*/', '', $request->input('url'));
        $url = rtrim($url, '?');

        if (!str_contains($url, 'alibaba.com/product-detail')) {
            return response()->json(['error' => 'Solo URLs de detalle de Alibaba'], 400);
        }

        $cacheKey = 'mic_result_' . md5($url);
        if ($cached = Cache::get($cacheKey)) {
            return response()->json($cached);
        }

        try {
            $response = $this->guzzle->post("https://api.apify.com/v2/acts/{$this->actorId()}/runs", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token(),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'startUrls' => [['url' => $url]],
                ],
                'timeout' => 20,
            ]);
            $run = json_decode($response->getBody(), true)['data'];

            $datasetId = $run['defaultDatasetId']
                ?? ($run['data']['defaultDatasetId'] ?? null)
                ?? null;

            if (!$datasetId) {
                Log::error('No datasetId', $run);
                return response()->json(['error' => 'Error interno'], 500);
            }

            Cache::put('apify_run_' . $run['id'], [
                'url' => $url,
                'datasetId' => $datasetId,
                'status' => 'running'
            ], now()->addHours(3));

            return response()->json([
                'status' => 'processing',
                'message' => 'Buscando datos... (60–90 segundos)',
                'runId' => $run['id'],
                'datasetId' => $datasetId,
            ]);
        } catch (\Exception $e) {
            Log::error('Apify error: ' . $e->getMessage());
            return response()->json(['error' => 'No se pudo iniciar'], 500);
        }
    }

    private function procesarDatos($data)
    {
        $candidates = [];

        if (!empty($data['dimensions']) && is_array($data['dimensions'])) {
            foreach ($data['dimensions'] as $dim) {
                if (stripos($dim['attrName'] ?? '', 'dimension') !== false) {
                    $raw = $dim['attrValue'] ?? '';
                    $parsed = $this->parsearDimensiones($raw, $dim['attrName'] ?? '');
                    if ($parsed) {
                        $parsed['origen'] = 'dimensions (' . ($dim['attrName'] ?? '?') . ')';
                        $candidates[] = $parsed;
                    }
                }
            }
        }

        if (!empty($data['unitSize'])) {
            $parsed = $this->parsearDimensiones($data['unitSize'], 'unitSize');
            if ($parsed) {
                $parsed['origen'] = 'unitSize';
                $candidates[] = $parsed;
            }
        }

        if (!empty($data['dimensionsDetail'])) {
            $parsed = $this->parsearDimensiones($data['dimensionsDetail'], 'dimensionsDetail');
            if ($parsed) {
                $parsed['origen'] = 'dimensionsDetail';
                $candidates[] = $parsed;
            }
        }

        $bestDim = null;
        if (count($candidates) > 0) {
            usort($candidates, fn($a, $b) => $a['vol'] <=> $b['vol']);
            $bestDim = $candidates[0];
        }

        $dimensiones_cm = $bestDim ? $bestDim['formatted'] : null;
        $dimensiones_origen = $bestDim ? $bestDim['origen'] : 'ninguno';
        $weightCandidates = [];

        if (!empty($data['weight'])) {
            $parsed = $this->parsearPeso($data['weight']);
            if ($parsed) {
                $parsed['origen'] = 'weight';
                $weightCandidates[] = $parsed;
            }
        }

        if (!empty($data['unitWeight'])) {
            $parsed = $this->parsearPeso($data['unitWeight']);
            if ($parsed) {
                $parsed['origen'] = 'unitWeight';
                $weightCandidates[] = $parsed;
            }
        }

        if (!empty($data['weightDetail'])) {
            $parsed = $this->parsearPeso($data['weightDetail']);
            if ($parsed) {
                $parsed['origen'] = 'weightDetail';
                $weightCandidates[] = $parsed;
            }
        }

        if (!empty($data['packageWeight'])) {
            $parsed = $this->parsearPeso($data['packageWeight']);
            if ($parsed) {
                $parsed['origen'] = 'packageWeight';
                $weightCandidates[] = $parsed;
            }
        }

        $bestWeight = null;
        if (count($weightCandidates) > 0) {
            usort($weightCandidates, fn($a, $b) => $a['kg'] <=> $b['kg']);
            $bestWeight = $weightCandidates[0];
        }

        $peso_final_kg = $bestWeight ? $bestWeight['kg'] : null;
        $peso_origen = $bestWeight ? $bestWeight['origen'] : 'ninguno';

        return [
            'title' => $data['title'] ?? 'Sin título',
            'price' => $data['priceTiers'][0]['dollarPrice'] ?? $data['priceRange'] ?? 'No disponible',
            'moq' => $data['moq'] ?? 'N/A',
            'image' => $data['firstImageUrl'] ?? ($data['images'][0] ?? null),
            'dimensions_cm'       => $dimensiones_cm,
            'dimensions_origen'   => $dimensiones_origen,
            'peso_kg_usar'          => $peso_final_kg ? round($peso_final_kg, 2) : null,
            'peso_origen'           => $peso_origen,
            'characteristics' => $data['characteristics'] ?? [],
            'micUrl' => $data['micUrl'] ?? null,
            'packageSize' => $data['packageSize'] ?? $data['unitSize'] ?? null,
            'packageWeight' => $data['packageWeight'] ?? $data['unitWeight'] ?? null,
        ];
    }

    /**
     * Parsea un string de peso a kg. "400g" -> 0.4, "15" -> 15.0
     * Retorna array ['kg' => float, 'original' => string] o null.
     */
    private function parsearPeso($raw): ?array
    {
        if (empty($raw)) return null;
        // Convertir a string por si viene número
        $str = strtolower((string)$raw);
        $clean = preg_replace('/[^0-9\.]/', '', $str);

        if (!is_numeric($clean)) return null;

        $val = (float) $clean;
        $factor = 1.0; // por defecto kg

        if (str_contains($str, 'kg') || str_contains($str, 'kilogram')) {
            $factor = 1.0;
        } elseif (str_contains($str, 'mg')) {
            $factor = 0.000001;
        } elseif (str_contains($str, 'g') || str_contains($str, 'gram')) { // Cuidado: 'kg' contiene 'g', el orden importa o regex
            // Si ya detectamos kg antes, no entra aquí, pero mejor ser específico
            if (!str_contains($str, 'kg')) {
                $factor = 0.001;
            }
        } elseif (str_contains($str, 'lb') || str_contains($str, 'pound')) {
            $factor = 0.453592;
        } elseif (str_contains($str, 'oz')) {
            $factor = 0.0283495;
        } else {
            // Sin unidad. Heurística simple:
            // Si es muy grande (ej > 1000), probablemente sean gramos? 
            // Alibaba suele usar kg por defecto. Si dice "15", es 15kg. Si dice "1500", podría ser 1.5kg o 1500kg.
            // Asumiremos KG por defecto excepto si es absurdo? 
            // Mejor dejar default kg.
        }

        return [
            'kg' => $val * $factor,
            'original' => $raw
        ];
    }

    /**
     * Parsea un string de dimensiones a cm.
     * Retorna array ['vals' => [l,w,h], 'vol' => float, 'formatted' => string] o null.
     */
    private function parsearDimensiones(?string $raw, string $contextHint = ''): ?array
    {
        if (empty($raw)) return null;

        $lowerRaw = strtolower($raw);
        $lowerHint = strtolower($contextHint);
        $factor = 1.0; // por defecto cm
        $detectedUnit = false;

        // 1. Detectar unidad explícita en el VALOR (Prioridad Alta)
        if (str_contains($lowerRaw, 'mm')) {
            $factor = 0.1;
            $detectedUnit = true;
        } elseif (preg_match('/(inch|in|"\s*$)/i', $lowerRaw)) {
            $factor = 2.54;
            $detectedUnit = true;
        } elseif (str_contains($lowerRaw, 'cm')) {
            $factor = 1.0;
            $detectedUnit = true;
        }

        // 2. Si no hay unidad en valor, mirar el HINT (Key)
        if (!$detectedUnit) {
            if (str_contains($lowerHint, 'mm')) {
                $factor = 0.1;
                $detectedUnit = true;
            } elseif (preg_match('/(inch|in\b)/i', $lowerHint)) {
                $factor = 2.54;
                $detectedUnit = true;
            }
        }

        // Limpiar para dejar solo números y separadores
        $clean = preg_replace('/[^0-9xX*\.]/', '', $raw);
        $separador = preg_match('/[xX]/', $clean) ? '[xX]' : '[*]';
        $partes = preg_split("/{$separador}/", $clean);
        $partes = array_filter($partes, fn($p) => trim($p) !== '');

        if (count($partes) !== 3) {
            return null;
        }

        $dims = [];
        foreach ($partes as $p) {
            $dims[] = (float) $p;
        }

        // 3. Heurística de seguridad si NO se detectó unidad
        if (!$detectedUnit && $factor === 1.0) {
            // Si alguna dimensión es muy grande (>200), asumimos mm
            if (max($dims) > 200) {
                $factor = 0.1;
            }
        }

        // Calcular finales
        $finalDims = array_map(fn($d) => $d * $factor, $dims);
        $vol = $finalDims[0] * $finalDims[1] * $finalDims[2];

        // Formatear LxAxH
        $formattedParts = array_map(function ($d) {
            return (floor($d) == $d) ? (int)$d : round($d, 2);
        }, $finalDims);

        return [
            'vals' => $finalDims,
            'vol' => $vol,
            'formatted' => implode('x', $formattedParts)
        ];
    }
    public function stream($runId)
    {
        header('X-Accel-Buffering: no');

        if (function_exists('apache_setenv')) {
            @apache_setenv('no-gzip', '1');
        }
        ini_set('zlib.output_compression', '0');
        ini_set('implicit_flush', 1);

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        return response()->stream(function () use ($runId) {
            $runInfo = Cache::get('apify_run_' . $runId);

            if (!$runInfo || !$runInfo['datasetId']) {
                echo "event: error\ndata: " . json_encode(['message' => 'Run no encontrado']) . "\n\n";
                @ob_flush();
                flush();
                return;
            }

            $datasetId = $runInfo['datasetId'];
            $maxWait = 90;
            $waited = 0;

            while ($waited < $maxWait) {
                try {
                    $response = $this->guzzle->get("https://api.apify.com/v2/datasets/{$datasetId}/items", [
                        'query' => ['token' => $this->token()],
                        'timeout' => 12
                    ]);

                    $items = json_decode($response->getBody(), true);

                    if (!empty($items)) {
                        $bestItem = collect($items)
                            ->sortByDesc(fn($i) => isset($i['packageSize']) ? 1 : 0)
                            ->first() ?? $items[0];
                        $finalResult = $this->procesarDatos($bestItem);

                        $cacheKey = 'apify_product_' . md5(strtolower($runInfo['url']));
                        Cache::put($cacheKey, $finalResult, now()->addDays(7));
                        Cache::forget('apify_run_' . $runId);

                        echo "event: ready\ndata: " . json_encode($finalResult) . "\n\n";
                        @ob_flush();
                        flush();
                        break;
                    }
                } catch (\Exception $e) {
                    Log::error('Apify SSE error: ' . $e->getMessage());
                }

                $waited += 5;

                echo "event: heartbeat\ndata: " . json_encode(['waiting' => $waited]) . "\n\n";
                echo "data: .\n\n";

                @ob_flush();
                flush();

                sleep(4);
            }

            if ($waited >= $maxWait) {
                echo "event: timeout\ndata: " . json_encode(['message' => 'Tiempo agotado']) . "\n\n";
                @ob_flush();
                flush();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
