<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TransportController extends Controller
{
    protected $guzzle;
    protected $token;
    protected $actorId;

    public function __construct()
    {
        $this->guzzle = new Guzzle();
        $this->token = config('services.apify.transport_token');
        $this->actorId = config('services.apify.transport_actor_id');

        if (!$this->token || !$this->actorId) {
            throw new \Exception('APIFY_TOKEN o TRANSPORT_ACTOR_ID no están configurados.');
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
        $request->validate([
            'podCode' => 'required|string',
            'polCode' => 'required|string',
            'transportType' => 'required|string',
            'maxRetries' => 'nullable|integer',
        ]);

        // Construyo el input que se enviará al actor
        $input = [
            'podCode' => $request->input('podCode'),
            'polCode' => $request->input('polCode'),
            'transportType' => $request->input('transportType'),
            'maxRetries' => (int) $request->input('maxRetries', 3),
        ];


        try {
            $response = $this->guzzle->post("https://api.apify.com/v2/acts/{$this->actorId()}/runs", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token(),
                    'Content-Type' => 'application/json',
                ],
                // Enviamos el input bajo la clave 'input' para la ejecución del actor
                'json' => $input,
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



            // Guardamos metadata del run para seguimiento
            Cache::put('apify_run_' . $run['id'], [
                'input' => $input,
                'datasetId' => $datasetId,
                'status' => 'running'
            ], now()->addHours(3));
            Log::info('Cache guardada para run:', ['runId' => $run['id'], 'datasetId' => $datasetId]);

            $result = [
                'status' => 'processing',
                'message' => 'Buscando datos reales... (60–90 segundos)',
                'runId' => $run['id'],
                'datasetId' => $datasetId,
            ];

            // También guardamos el resultado en cache para respuestas rápidas si se solicita de nuevo


            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Apify error: ' . $e->getMessage());
            return response()->json(['error' => 'No se pudo iniciar'], 500);
        }
    }

    private function procesarDatos(array $item): array
    {
        // Normaliza precio a float|null
        $normalizePrice = function ($value) {
            if ($value === null || $value === '') {
                return null;
            }
            $num = preg_replace('/[^\d.]/', '', (string) $value);
            if ($num === '') {
                return null;
            }
            return (float) $num;
        };

        $processedRates = [];
        $mins = [
            'gp20' => null,
            'gp40' => null,
            'hq40' => null,
        ];

        if (!empty($item['rates']) && is_array($item['rates'])) {
            foreach ($item['rates'] as $r) {
                $gp20 = $normalizePrice($r['price']['gp20'] ?? $r['price_gp20'] ?? null);
                $gp40 = $normalizePrice($r['price']['gp40'] ?? $r['price_gp40'] ?? null);
                $hq40 = $normalizePrice($r['price']['hq40'] ?? $r['price_hq40'] ?? null);

                $rate = [
                    'shippingLine' => $r['shippingLine'] ?? $r['carrier'] ?? null,
                    'validUntil' => $r['validUntil'] ?? null,
                    'price' => [
                        'gp20' => $gp20,
                        'gp40' => $gp40,
                        'hq40' => $hq40,
                    ],
                    'closing' => isset($r['closing']) ? (string)$r['closing'] : ($r['closingDate'] ?? null),
                    'transitTime' => $r['transitTime'] ?? $r['transit_time'] ?? null,
                    'detailsUrl' => $r['detailsUrl'] ?? $r['details_url'] ?? null,
                ];

                $processedRates[] = $rate;

                // Actualizar mínimos para cada tipo
                foreach (['gp20', 'gp40', 'hq40'] as $k) {
                    $priceVal = $rate['price'][$k];
                    if ($priceVal !== null) {
                        if ($mins[$k] === null || $priceVal < $mins[$k]['price']) {
                            $mins[$k] = [
                                'price' => $priceVal,
                                'shippingLine' => $rate['shippingLine'],
                                'validUntil' => $rate['validUntil'],
                                'detailsUrl' => $rate['detailsUrl'],
                                'closing' => $rate['closing'],
                                'transitTime' => $rate['transitTime'],
                            ];
                        }
                    }
                }
            }
        }

        // Ordenar rates por gp20 asc (nulls al final)
        usort($processedRates, function ($a, $b) {
            $av = $a['price']['gp20'];
            $bv = $b['price']['gp20'];
            if ($av === $bv) return 0;
            if ($av === null) return 1;
            if ($bv === null) return -1;
            return $av < $bv ? -1 : 1;
        });

        // Preparar resumen
        $summary = [
            'totalRatesFound' => $item['totalRatesFound'] ?? count($processedRates),
            'ratesReturned' => count($processedRates),
            'topRates' => array_slice($processedRates, 0, 5),
        ];

        return [
            'searchedAt' => $item['searchedAt'] ?? null,
            'transportType' => $item['transportType'] ?? null,
            'polCode' => $item['polCode'] ?? null,
            'podCode' => $item['podCode'] ?? null,
            'resultPageUrl' => $item['resultPageUrl'] ?? null,
            'totalRatesFound' => $summary['totalRatesFound'],
            'rates' => $processedRates,
            'best' => $mins,
            'summary' => $summary,
            'success' => $item['success'] ?? true,
            'scraped_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * Stream SSE que espera a que el dataset esté listo y procesa la salida adaptada al formato dado.
     *
     * Busca el mejor item (más rates o totalRatesFound) y lo normaliza con procesarDatos.
     */
    // public function stream($runId)
    // {
    //     // LIMPIAR TODO BUFFER
    //     while (ob_get_level()) ob_end_clean();

    //     // HEADERS SSE OBLIGATORIOS
    //     header('Content-Type: text/event-stream');
    //     header('Cache-Control: no-cache');
    //     header('Connection: keep-alive');
    //     header('X-Accel-Buffering: no');

    //     // Mantener viva la conexión
    //     echo ": conexión SSE iniciada\n\n";
    //     flush();

    //     Log::info('STREAM INICIADO - runId: ' . $runId);

    //     // $runInfo = Cache::get('apify_run_' . $runId);

    //     // if (!$runInfo || empty($runInfo['datasetId'])) {
    //     //     echo "event: error\ndata: " . json_encode(['message' => 'Run no encontrado en cache']) . "\n\n";
    //     //     flush();
    //     //     return;
    //     // }

    //     // $datasetId = $runInfo['datasetId'];
    //     $maxWait = 120;
    //     $waited = 0;

    //     while ($waited < $maxWait) {
    //         // FORZAR QUE PHP NO SE MUERA
    //         set_time_limit(30);

    //         try {
    //             // ESTA LÍNEA ES LA QUE FALLA SILENCIOSAMENTE EN WINDOWS
    //             // $response = Http::timeout(20)->get("https://api.apify.com/v2/datasets/{$datasetId}/items", [
    //             //     'token' => $this->token(),
    //             //     'limit' => 100,
    //             //     'clean' => 'true'
    //             // ]);
    //             $response = Http::timeout(20)->get("https://api.apify.com/v2/datasets/poZXdODUW5rcbB7S9/items", [
    //                 'format' => 'json',
    //                 'limit' => 1000,
    //                 'clean' => 'true'
    //             ]);

    //             Log::info('Apify respondió', ['status' => $response->status()]);

    //             if ($response->successful()) {
    //                 $items = $response->json();

    //                 if (!empty($items)) {
    //                     $preferred = collect($items)
    //                         ->filter(fn($i) => ($i['success'] ?? false) === true)
    //                         ->sortByDesc(fn($i) => $i['totalRatesFound'] ?? 0)
    //                         ->first() ?? $items[0];

    //                     $finalResult = $this->procesarDatos($preferred);

    //                     echo "event: ready\ndata: " . json_encode($finalResult) . "\n\n";
    //                     Log::info('TARIFAS ENVIADAS AL NAVEGADOR');
    //                     flush();
    //                     return;
    //                 }
    //             }
    //         } catch (\Exception $e) {
    //             Log::warning('Error Apify: ' . $e->getMessage());
    //         }

    //         $waited += 5;
    //         echo "event: heartbeat\ndata: " . json_encode(['waiting' => $waited]) . "\n\n";
    //         flush();
    //         sleep(5);
    //     }

    //     echo "event: timeout\ndata: Tiempo agotado\n\n";
    //     flush();
    // }
    public function stream($runId = null)
{
    // Limpiar buffer
    while (ob_get_level()) ob_end_clean();

    // Headers SSE
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    header('Connection: keep-alive');

    // Mostrar que estamos vivos
    echo "event: heartbeat\ndata: Conectado... cargando tarifas reales\n\n";
    flush();

    // TU DATASET ESTÁTICO (el que ya tiene datos)
    $datasetId = 'poZXdODUW5rcbB7S9'; // ← tu dataset con datos reales

    try {
        $response = Http::timeout(30)->get("https://api.apify.com/v2/datasets/{$datasetId}/items", [
            'token' => $this->token(),
            'format' => 'json',
            'limit' => 1000,
            'clean' => true
        ]);

        if ($response->successful()) {
            $items = $response->json();

            if (!empty($items)) {
                $preferred = collect($items)
                    ->filter(fn($i) => !empty($i['rates']))
                    ->sortByDesc(fn($i) => $i['totalRatesFound'] ?? 0)
                    ->first() ?? $items[0];

                $finalResult = $this->procesarDatos($preferred);

                echo "event: ready\ndata: " . json_encode($finalResult) . "\n\n";
                Log::info("TARIFAS ESTÁTICAS ENVIADAS → " . count($finalResult['rates'] ?? []));
            } else {
                echo "event: error\ndata: No hay datos en el dataset\n\n";
            }
        } else {
            echo "event: error\ndata: Error al cargar tarifas\n\n";
        }
    } catch (\Exception $e) {
        echo "event: error\ndata: Error de conexión\n\n";
        Log::error("Error dataset estático: " . $e->getMessage());
    }

    flush();
    exit; // MUY IMPORTANTE: terminar el script aquí
}
}
