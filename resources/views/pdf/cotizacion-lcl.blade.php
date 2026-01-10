<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización LCL - IA GROUPS</title>
    <style>
        @page {
            margin: 50px;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #000;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #000;
        }

        .logo-cell {
            padding: 10px;
            width: 15%;
            text-align: center;
        }

        .logo-cell img {
            width: 80px;
        }

        .header-info-cell {
            padding: 10px;
            vertical-align: top;
            text-align: center;
        }

        .header-title {
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }

        .address-line {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .contact-line {
            font-size: 12px;
            color: #000;
            margin-bottom: 3px;
        }

        .agent-line {
            font-size: 12px;
            color: #000;
            font-weight: bold;
        }

        .client-info-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 1px solid #000;
        }

        .client-info-table td {
            border: 1px solid #000;
            padding: 5px 10px;
            font-size: 13px;
        }

        .label-cell {
            width: 15%;
            font-weight: bold;
        }

        .value-cell {
            width: 85%;
        }

        .order-date-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f0f0f0;
        }

        .order-date-table td {
            border: 1px solid #000;
            padding: 5px 10px;
            font-size: 13px;
            font-weight: bold;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-table th {
            border: 1px solid #000;
            background-color: #fb9e00;
            padding: 10px 5px;
            font-size: 12px;
            text-align: center;
        }

        .product-table td {
            border: 1px solid #000;
            padding: 10px 5px;
            font-size: 12px;
            text-align: center;
            vertical-align: middle;
        }

        .ref-cell {
            width: 10%;
        }

        .foto-cell {
            width: 30%;
        }

        .desc-cell {
            width: 15%;
        }

        .qty-cell {
            width: 10%;
        }

        .unit-cell {
            width: 10%;
        }

        .price-cell {
            width: 10%;
        }

        .empty-cell {
            width: 5%;
        }

        .total-cell {
            width: 10%;
            font-weight: bold;
        }

        .footer-note {
            margin-top: 20px;
            padding: 20px;
            font-weight: bold;
            color: #000;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="Logo">
                    @else
                    <div style="color: #f59e0b; font-weight: bold; font-size: 24px;">IA GROUPS</div>
                    @endif
                </td>
                <td class="header-info-cell">
                    <div class="header-title">COTIZACION IA GROUPS</div>
                    <div class="address-line">Direccion Internacional: 1209 Mountain Road PL NE STE N, Albuquerque, NM 87110, USA</div>
                    <div class="address-line">Direccion Bolivia: Calle Colón esq Bolivar 820. Edificio AUAD 2do piso. Tarija</div>
                    <div class="agent-line">Agentes Internacionales de carga: {{ $agente['nombre'] ?? 'IA GROUPS' }}</div>
                    <div class="contact-line">https://ia-groups.com &nbsp;&nbsp; {{ $agente['email'] ?? 'info@iagroups.com' }} &nbsp;&nbsp; {{ $agente['telefono'] ?? '+591 702693251' }}</div>
                </td>
            </tr>
        </table>

        <table class="client-info-table">
            <tr>
                <td class="label-cell">Cliente</td>
                <td class="value-cell">{{ $clienteNombre ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Ciudad</td>
                <td class="value-cell">{{ $clienteCiudad ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Contacto</td>
                <td class="value-cell">{{ $clienteTelefono ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Email:</td>
                <td class="value-cell">{{ $clienteEmail ?: 'N/A' }}</td>
            </tr>
        </table>
        @php
        $subtotalGastos = 0;
        $fleteInternacional = (float)($desglose['Costo de Envío de Paquete'] ?? 0);
        $valorMercancia = (float)($desglose['Valor de Mercancía'] ?? 0);

        // Extraer valores de aduana para usarlos en la derecha y no duplicarlos aquí
        $gravamenArancelario = (float)($valorMercancia * 0.10 ?? 0);
        $baseImponible = (float)($gravamenArancelario + $valorMercancia ?? 0);
        $impuestoIVA = (float)($baseImponible * 0.1494 ?? 0);
        $despacho = (float)($gastosAdicionales['Despacho'] ?? 0);
        $agencia = (float)($gastosAdicionales['Agencia despachante'] ?? 0);

        // Lista de keys a EXCLUIR de la izquierda porque van a la derecha o no se muestran
        $excludedKeys = [
        'Gravamen Arancelario',
        'Impuesto IVA',
        'Base Imponible',
        'Despacho',
        'Agencia despachante',
        'Impuesto' // Key vieja agregada
        ];
        $granTotalFinal = $gravamenArancelario + $impuestoIVA + $despacho + $agencia;
        $granTotalFinalEnvio = $fleteInternacional + $subtotalGastos;
        @endphp
        <table class="order-date-table">
            <tr>
                <td style="width: 50%;">ORDER NO.:</td>
                <td style="width: 25%;">FECHA:</td>
                <td style="width: 25%;">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</td>
            </tr>
        </table>

        <table class="product-table">
            <thead>
                <tr>
                    <th class="ref-cell">REF.</th>
                    <th class="foto-cell">FOTO</th>
                    <th class="desc-cell">DESCRIPCION</th>
                    <th class="qty-cell">CANTIDAD</th>
                    <th class="unit-cell">UNIDAD</th>
                    <th class="price-cell">EXW PRICE(USD)</th>
                    <th class="total-cell">MONTO TOTAL COMPRA</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($productos) && count($productos) > 0)
                @foreach($productos as $prod)
                <tr>
                    <td>{{ $prod['id'] ?? '-' }}</td>
                    <td>
                        @if(!empty($prod['imagen']) && (str_starts_with($prod['imagen'], 'data:image') || str_starts_with($prod['imagen'], 'http')))
                        <img src="{{ $prod['imagen'] }}" style="max-width: 150px; max-height: 150px; object-fit: contain;">
                        @else
                        <div style="color: #9ca3af; font-size: 10px; font-style: italic;">Sin imagen</div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $prod['producto'] }}</strong><br>
                    </td>
                    <td>{{ $prod['cantidad_total'] ?? $prod['cantidad'] }}</td>
                    <td>unids</td>
                    <td>$ {{ number_format($prod['valor_unitario'], 2) }}</td>
                    <td>${{ number_format($prod['total_valor'], 2) }}</td>
                </tr>
                @endforeach
                @elseif(!empty($desglose_reporte))
                <tr>
                    <td>{{ $desglose_reporte['ref'] }}</td>
                    <td>
                        @if(!empty($desglose_reporte['imagen']) && (str_starts_with($desglose_reporte['imagen'], 'data:image') || str_starts_with($desglose_reporte['imagen'], 'http')))
                        <img src="{{ $desglose_reporte['imagen'] }}" style="max-width: 150px; max-height: 150px; object-fit: contain;">
                        @else
                        <div style="color: #9ca3af; font-size: 10px; font-style: italic;">Sin imagen</div>
                        @endif
                    </td>
                    <td>{{ $desglose_reporte['descripcion'] }}</td>
                    <td>{{ $desglose_reporte['cantidad'] }}</td>
                    <td>{{ $desglose_reporte['unidad'] }}</td>
                    <td>$ {{ number_format($desglose_reporte['precio'], 2) }}</td>
                    <td>${{ number_format($desglose_reporte['total'], 2) }}</td>
                </tr>
                @else
                <tr>
                    <td colspan="7" style="height: 50px;">No hay información disponible</td>
                </tr>
                @endif
            </tbody>
        </table>


        <!-- Tabla de Gastos Adicionales -->
        <!-- Tabla de Gastos Adicionales -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 25px; margin-bottom: 20px;">
            <tr>
                <!-- Columna Izquierda: Gastos de Logística y Envío -->
                <td style="width: 58%; vertical-align: top; padding-right: 2%;">
                    <h4 style="margin-bottom: 10px; font-size: 10px; text-transform: uppercase;">Detalle de Gastos de Logística y Envío</h4>
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th style="width: 70%; text-align: left; padding-left: 15px;">CONCEPTO / SERVICIO</th>
                                <th style="width: 30%; text-align: right; padding-right: 15px;">MONTO (USD)</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Flete Marítimo --}}
                            <tr style="height: auto;">
                                <td style="text-align: left; padding: 8px 15px;">Costo de Envío de Paquete</td>
                                <td style="text-align: right; padding: 8px 15px; font-weight: bold;">$ {{ number_format($fleteInternacional, 2) }}</td>
                            </tr>

                            {{-- Gastos Generales (Filtrando los de aduana) --}}
                            @php $subtotalGastosAccum = 0; @endphp
                            @foreach($gastosAdicionales as $concepto => $monto)
                            @if(!in_array($concepto, $excludedKeys))
                            @php
                            $montoNum = is_numeric($monto) ? (float)$monto : 0;
                            $subtotalGastosAccum += $montoNum;
                            @endphp
                            <tr style="height: auto;">
                                <td style="text-align: left; padding: 8px 15px;">{{ $concepto }}</td>
                                <td style="text-align: right; padding: 8px 15px; font-weight: bold;">$ {{ number_format($montoNum, 2) }}</td>
                            </tr>
                            @endif
                            @endforeach

                            @php
                            $totalEnvioUSD = $fleteInternacional + $subtotalGastosAccum;
                            $exchangeRateP2P = (isset($p2pPrice) && is_numeric($p2pPrice)) ? (float)$p2pPrice : 9.70;
                            @endphp

                            <tr style="background-color: #fb9e00; font-weight: bold;">
                                <td style="text-align: right; font-size: 10px; border: 1px solid #000;">TOTAL GESTION DE ENVIO (USD)</td>
                                <td style="text-align: right; font-size: 10px; border: 1px solid #000;">$ {{ number_format($totalEnvioUSD, 2) }}</td>
                            </tr>
                            <tr style="background-color: #fb9e00; font-weight: bold;">
                                <td style="text-align: right; font-size: 10px; border: 1px solid #000;">TOTAL GESTION DE ENVIO (BS) - T.C. {{ number_format($exchangeRateP2P, 2) }}</td>
                                <td style="text-align: right; font-size: 10px; border: 1px solid #000;">
                                    Bs {{ number_format($totalEnvioUSD * $exchangeRateP2P, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>

                <!-- Columna Derecha: Impuesto, Aduana y Despacho -->
                <td style="width: 40%; vertical-align: top;">
                    <h4 style="margin-bottom: 10px; font-size: 10px; text-transform: uppercase;">Detalle de Impuesto, Aduana y Despacho</h4>
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th style="width: 70%; text-align: left; padding-left: 15px;">CONCEPTO / SERVICIO</th>
                                <th style="width: 30%; text-align: right; padding-right: 15px;">MONTO (USD)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Gravamen Arancelario -->
                            <tr>
                                <td style="text-align: left; padding: 8px 15px; background-color: #ffe6cc; font-weight: bold; border: 1px solid #000;">GRAVAMEN ARANCELARIO</td>
                                <td style="text-align: right; padding: 8px 15px; font-weight: bold; border: 1px solid #000;">$ {{ number_format($gravamenArancelario, 2) }}</td>
                            </tr>
                            <!-- Base Imponible -->
                            <tr>
                                <td style="text-align: left; padding: 8px 15px; background-color: #ffe6cc; font-weight: bold; border: 1px solid #000;">BASE</td>
                                <td style="text-align: right; padding: 8px 15px; font-weight: bold; border: 1px solid #000;">$ {{ number_format($baseImponible, 2) }}</td>
                            </tr>
                            <!-- IVA -->
                            <tr>
                                <td style="text-align: left; padding: 8px 15px; border: 1px solid #000;">IMPUESTO AL VALOR AGREGADO</td>
                                <td style="text-align: right; padding: 8px 15px; font-weight: bold; border: 1px solid #000;">$ {{ number_format($impuestoIVA, 2) }}</td>
                            </tr>
                            <!-- Despacho -->
                            <tr>
                                <td style="text-align: left; padding: 8px 15px; border: 1px solid #000;">DESPACHO</td>
                                <td style="text-align: right; padding: 8px 15px; font-weight: bold; border: 1px solid #000;">$ {{ number_format($despacho, 2) }}</td>
                            </tr>
                            <!-- Agencia -->
                            <tr>
                                <td style="text-align: left; padding: 8px 15px; border: 1px solid #000;">AGENCIA DESPACHANTE</td>
                                <td style="text-align: right; padding: 8px 15px; font-weight: bold; border: 1px solid #000;">$ {{ number_format($agencia, 2) }}</td>
                            </tr>
                            <tr style="background-color: #fb9e00; font-weight: bold;">
                                <td style="text-align: right; font-size: 10px; border: 1px solid #000;">TOTAL GESTION ADUANERA (USD)</td>
                                <td style="text-align: right; font-size: 10px; border: 1px solid #000;">$ {{ number_format($granTotalFinal, 2) }}</td>
                            </tr>
                            <tr style="background-color: #fb9e00; font-weight: bold;">
                                <td style="text-align: right; font-size: 10px; border: 1px solid #000;">TOTAL GESTION ADUANERA (BS) - T.C. 6.96</td>
                                <td style="text-align: right; font-size: 10px; border: 1px solid #000;">Bs {{ number_format($granTotalFinal * 6.96, 2) }} </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

        <table style="width: 70%; border-collapse: collapse;">
            @php
            $totalGralUSD = $granTotalFinal + $totalEnvioUSD;
            $totalGralBS = ($granTotalFinal * 6.96) + ($totalEnvioUSD * $exchangeRateP2P);
            @endphp
            <tr style="background-color: #fb9e00; font-weight: bold;">
                <td style="text-align: right; font-size: 12px; border: 1px solid #000;">TOTAL ESTIMADO DETALLE DE GASTOS (USD)</td>
                <td style="text-align: right; font-size: 12px; border: 1px solid #000;">$ {{ number_format($totalGralUSD, 2) }}</td>
            </tr>
            <tr style="background-color: #fb9e00; font-weight: bold;">
                <td style="text-align: right; font-size: 12px; border: 1px solid #000;">TOTAL ESTIMADO DETALLE DE GASTOS (BS)</td>
                <td style="text-align: right; font-size: 12px; border: 1px solid #000;">Bs {{ number_format($totalGralBS, 2) }}</td>
            </tr>
        </table>

        <div class="footer-note" style="margin-top: 20px;">
            <strong>NOTA:</strong><br>
            <ul class="footer-note-list">
                <li style="font-weight: bold; font-size: 12px;">Esta cotizacion tiene una validez de 7 dias.</li>
                <li style="font-weight: bold; font-size: 12px;">Esta cotizacion podria sufrir alteraciones en caso de alguna revaloracion por parte de la aduana.</li>
                <li style="font-weight: bold; font-size: 12px;">Asume que el consignatario que tiene cualquier permiso que sea requerido por autoridades en el país de destino</li>
                <li style="font-weight: bold; font-size: 12px;">Contamos con nuestra Propia Agencia Despachante de manera opcional.</li>
                <li style="font-weight: bold; font-size: 12px;">El PAGO DE ADUANAS es una ves llegue la carga a Almacenes Aduaneros de Bolivia.</li>
                <li style="font-weight: bold; font-size: 12px;">Cotización en base a datos enviados por el cliente, al llegar a almacén, se verificarán peso y dimensiones.</li>
            </ul>
        </div>

    </div>
</body>

</html>