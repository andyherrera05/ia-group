    public function buscarTarifasFCL()
    {

        $this->reset(['rates', 'loadingRates', 'statusMessage', 'fclRates', 'currentPage']);
        $this->currentRunId = null;


        if (empty($this->polCode) || empty($this->podCode)) {
            $this->statusMessage = 'Selecciona origen y destino';
            return;
        }

        $this->loadingRates = true;
        // $this->statusMessage = 'Conectando con el proveedor de tarifas'; // Old message
        $this->statusMessage = 'Cargando tarifas locales...';

        // $originCode = strtolower(substr($this->polCode, 0, 5));
        // $destCode   = strtolower(substr($this->podCode, 0, 5));
        // $url = "https://www.5688.com.cn/fcl/{$originCode}-{$destCode}";
        // Mock URL for internal tracking
        $url = "local://database/mockup/data.json";

        try {
            /*
             * REEMPLAZO PETICIÓN HTTP POR LECTURA DE ARCHIVO LOCAL
             */
            $jsonPath = base_path('database/mockup/data.json');

            if (!File::exists($jsonPath)) {
                throw new \Exception("El archivo de datos simulados no existe en: $jsonPath");
            }

            $jsonContent = File::get($jsonPath);
            $responseArray = json_decode($jsonContent, true);

            if (!$responseArray || !($responseArray['success'] ?? false)) {
                throw new \Exception('El archivo JSON local no tiene el formato esperado o success=false.');
            }

            // Extract rates from the mockup structure
            $data = $responseArray['data']['json']['rates'] ?? [];

            if (empty($data)) {
                $this->statusMessage = 'No se encontraron tarifas en el archivo local.';
                $this->fclRates = collect();
            } else {
                $this->fclRates = collect($data);

                // Guardamos en BD para mantener la lógica de caché/historial si se desea
                $this->guardarTarifasEnBaseDeDatos($url, $data);

                $this->statusMessage = '¡Tarifas actualizadas (Local)! Se encontraron ' . count($data) . ' opciones.';
            }
        } catch (\Exception $e) {
            Log::error('Error Cargando FCL Local: ' . $e->getMessage());
            $this->statusMessage = 'Error cargando datos locales: ' . $e->getMessage();

            // Fallback a BD antigua si falla lo local (opcional)
            $this->cargarTarifasDesdeBaseDeDatos();
        }
        $this->loadingRates = false;
    }
