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
        $dimensiones_cm = null;
        $dimensiones_origen = 'ninguno';

        if (!empty($data['dimensions']) && is_array($data['dimensions'])) {
            foreach ($data['dimensions'] as $dim) {
                if (stripos($dim['attrName'] ?? '', 'dimension') !== false) {
                    $raw = $dim['attrValue'] ?? '';
                    $dimensiones_cm = $this->normalizarDimensionesCm($raw);
                    $dimensiones_origen = 'dimensions → convertido a cm';
                    break;
                }
            }
        }

        // Si no encontramos en dimensions, intentamos con unitSize (menos confiable)
        if (!$dimensiones_cm && !empty($data['unitSize'])) {
            $dimensiones_cm = $this->normalizarDimensionesCm($data['unitSize']);
            $dimensiones_origen = 'unitSize → convertido/normalizado';
        }

        // Prioridad para peso (lo que realmente cobra el flete)
        $peso_final_kg = null;
        $peso_origen = 'ninguno';

        if (!empty($data['weight'])) {
            $peso_final_kg = (float) preg_replace('/[^\d.]/', '', $data['weight']);
            $peso_origen = 'weight (bruto)';
        } elseif (!empty($data['unitWeight'])) {
            $peso_final_kg = (float) preg_replace('/[^\d.]/', '', $data['unitWeight']);
            $peso_origen = 'unitWeight (neto)';
        }

        return [
            'title' => $data['title'] ?? 'Sin título',
            'price' => $data['priceTiers'][0]['dollarPrice'] ?? 'No disponible',
            'moq' => $data['moq'] ?? 'N/A',
            'image' => $data['firstImageUrl'] ?? ($data['images'][0] ?? null),
            'dimensions_cm'       => $dimensiones_cm,              // "112x84x67"
            'dimensions_origen'   => $dimensiones_origen,
            'peso_kg_usar'          => $peso_final_kg ? round($peso_final_kg, 1) : null,
            'peso_origen'           => $peso_origen,
            'characteristics' => $data['characteristics'] ?? [],
            'micUrl' => $data['micUrl'] ?? null,
            'packageSize' => $data['packageSize'] ?? $data['unitSize'] ?? null,
            'packageWeight' => $data['packageWeight'] ?? $data['unitWeight'] ?? null,
            'source' => $data['source'] ?? 'Alibaba',
            'scrapeTimeSec' => $data['scrapeTimeSec'] ?? null,
            'scraped_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * Convierte dimensiones a formato cm corto: "112x84x67"
     */
    private function normalizarDimensionesCm(?string $dimensionsString): ?string
    {
        if (empty($dimensionsString)) {
            return null;
        }

        // 1. Limpiar el string: quitar mm, espacios, puntos innecesarios
        $clean = preg_replace('/\s*mm\s*/i', '', $dimensionsString);
        $clean = preg_replace('/[^0-9xX*\.]/', '', $clean); // solo números, x/X, *, .

        // 2. Posibles separadores: * o x o X
        $separador = preg_match('/[xX]/', $clean) ? '[xX]' : '[*]';
        $partes = preg_split("/{$separador}/", $clean);

        if (count($partes) !== 3) {
            return null; // formato inválido
        }

        $resultado = [];

        foreach ($partes as $valor) {
            $num = (float) trim($valor);

            // Si está en milímetros → convertir a cm
            if ($num > 500) { // heurística: difícil que una dimensión sea >500cm (5m)
                $num = $num / 10; // mm → cm (SIN REDONDEAR A ENTERO)
            }

            // Si tiene decimales, redondear a 2, si no, entero.
            // Para el string final, usamos lógica simple:
            if (floor($num) == $num) {
                $resultado[] = (int) $num;
            } else {
                $resultado[] = round($num, 2);
            }
        }

        // Formato final deseado
        return implode('x', $resultado);
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
                        Log::warning($bestItem);

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
