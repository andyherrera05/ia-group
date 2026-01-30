<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización {{ strtoupper($tipoCarga) }} - IA GROUPS</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: #fff;
            color: #000;
            margin: 0;
            padding: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .header-logo {
            width: 80px;
            vertical-align: top;
        }

        .header-title {
            text-align: center;
            vertical-align: middle;
        }

        .header-title h1 {
            font-size: 18px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
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

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .items-table th {
            background: #fb9e00;
            border: 1px solid #000;
            padding: 8px 4px;
            font-size: 10px;
            text-align: center;
            text-transform: uppercase;
        }

        .items-table td {
            border: 1px solid #000;
            padding: 10px 5px;
            font-size: 11px;
            vertical-align: middle;
            text-align: center;
        }

        .col-ref {
            width: 8%;
            font-weight: bold;
            text-transform: uppercase;
        }

        .col-foto {
            width: 30%;
        }

        .col-desc {
            width: 20%;
            font-weight: bold;
        }

        .col-cant {
            width: 8%;
            font-weight: bold;
        }

        .col-unid {
            width: 8%;
            font-weight: bold;
        }

        .col-price {
            width: 12%;
            font-weight: bold;
        }

        .col-empty {
            width: 8%;
        }

        .col-total {
            width: 12%;
            font-weight: bold;
        }

        .product-img {
            max-width: 150px;
            max-height: 100px;
        }

        .price-val {
            display: inline-block;
            width: 100%;
            text-align: center;
        }

        .currency-prefix {
            float: left;
            margin-left: 5px;
        }

        .amount-val {
            float: right;
            margin-right: 5px;
        }

        .header-info-cell {
            padding: 10px;
            vertical-align: top;
            text-align: center;
        }

        .logo-cell img {
            width: 80px;
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

        .clear {
            clear: both;
        }

        .footer-note {
            margin-top: 20px;
            font-size: 10px;
            line-height: 1.4;
        }
    </style>
</head>

<body>
    <!-- Header Section -->
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


    <!-- Client & Order Info -->
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

    <table class="order-date-table">
        <tr>
            <td style="width: 50%;">ORDER NO.:</td>
            <td style="width: 25%;">FECHA:</td>
            <td style="width: 25%;">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</td>
        </tr>
    </table>

    <!-- Main Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th class="col-ref">REF.</th>
                <th class="col-foto">FOTO</th>
                <th class="col-desc">DESCRIPCION</th>
                <th class="col-cant">CANTIDAD</th>
                <th class="col-unid">UNIDAD</th>
                <th class="col-price">EXW PRICE(USD)</th>
                <th class="col-total">MONTO TOTAL COMPRA</th>
            </tr>
        </thead>
        <tbody>
            @php
            $item = $desglose_reporte;
            @endphp
            <tr>
                <td class="col-ref">{{ $item['ref'] ?? 'CONTAINER' }}</td>
                <td>
                    @if(!empty($item['imagen']))
                    <img src="{{ $item['imagen'] }}" alt="Producto" style="max-width: 100px; max-height: 100px; object-fit: contain;">
                    @elseif(!empty($containerBase64))
                    <img src="{{ $containerBase64 }}" alt="Container" style="max-width: 100px; max-height: 100px; object-fit: contain;">
                    @else
                    <div style="color: #9ca3af; font-size: 10px; font-style: italic;">Sin imagen</div>
                    @endif
                </td>
                <td class="col-desc">
                    <div style="font-weight: bold;">{{ $item['descripcion'] ?? 'Flete Marítimo' }}</div>
                </td>
                <td class="col-cant">{{ $item['cantidad'] ?? '1' }}</td>
                <td class="col-unid">{{ $item['unidad'] ?? 'PCS' }}</td>
                <td class="col-price">
                    <div class="price-val">
                        <span class="currency-prefix">$</span>
                        <span class="amount-val">${{ number_format($item['valorMercancia'], 2) }}</span>
                        <div class="clear"></div>
                    </div>
                </td>
                <td class="col-total">
                    <div class="price-val">
                        <span class="currency-prefix">$</span>
                        <span class="amount-val">${{ number_format($item['valorMercancia'], 2) }}</span>
                        <div class="clear"></div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Detailed Cost Summary Table -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr>
            <td style="width: 60%; vertical-align: top; padding-right: 10px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    @php
                    $item = $desglose_reporte;
                    $valorCarga = $item['valorMercancia'] ?? 0;
                    $fleteMaritimo = isset($valorFacturado) ? $valorFacturado : ($item['precio'] ?? 0);
                    $transporte_terrestre = $item['transporte_terrestre'] ?? 0;
                    $despachante = $item['despachante'] ?? 0;
                    $gravamenArancelario = $item['gravamen'] ?? 0;
                    $agencia_despachante = $item['agencia_despachante'] ?? 0;
                    $impuesto = $item['impuestos'] ?? 0;
                    $desconsolidacion = $item['desconsolidacion'] ?? 0;
                    $verificacion_producto = $item['verificacion_producto'] ?? 0;
                    $verificacion_calidad = $item['verificacion_calidad'] ?? 0;
                    $verificacion_empresa_digital = $item['verificacion_empresa_digital'] ?? 0;
                    $verificacion_empresa_presencial = $item['verificacion_empresa_presencial'] ?? 0;
                    $costoPeligrosa = $item['verificacion_sustancias_peligrosas'] ?? 0;
                    $costoSeguroCarga = $item['seguro_carga'] ?? 0;
                    $costoSwift = $item['comision_swift'] ?? 0;
                    $costoRepresentacion = $item['representacion'] ?? 0;
                    $gestionPortuaria = $item['gestionPortuaria'] ?? 0;
                    $booking = $item['booking'] ?? 0;
                    $totalLogisticaBolivia = $item['total_logistica_bolivia'] ?? 0;

                    @endphp

                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">VALOR DE CARGA</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($valorCarga, 2) }}</td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">SERVICIO DE ENVIO MARITIMO {{ $origen }} - {{ $destino }}</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">${{ number_format($fleteMaritimo, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">GESTION PORTUARIA</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($gestionPortuaria, 2) }}</td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">BOOKING</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($booking, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">GESTION LOGISTICA</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($totalLogisticaBolivia, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">DESCONSOLIDACION</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($desconsolidacion, 2) }}</td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">COSTO ADICIONAL DE ENVIO POR CARGA PELIGROSA</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($costoPeligrosa, 2) }}</td>
                    </tr>


                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">SEGURO</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($costoSeguroCarga, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">GIRO INTERNACIONAL</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($costoSwift, 2) }}</td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">VERIFICACION DE EMPRESA DIGITAL</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($verificacion_empresa_digital, 2) }}</td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">SERVICIO DE INSPECCION DE CALIDAD</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($verificacion_calidad, 2) }}</td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">VERIFICACION DEL PRODUCTO</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($verificacion_producto, 2) }}</td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">VERIFICACION PRESENCIAL DE EMPRESA</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($verificacion_empresa_presencial, 2) }}</td>
                    </tr>

                    @php
                    $totalGeneral = $valorCarga + $fleteMaritimo + $costoSeguroCarga + $costoPeligrosa + $costoSwift + $gestionPortuaria + $booking + $desconsolidacion + $verificacion_empresa_digital + $verificacion_calidad + $verificacion_producto + $verificacion_empresa_presencial + $totalLogisticaBolivia;

                    $exchangeRateP2P = (isset($p2pPrice) && is_numeric($p2pPrice)) ? (float)$p2pPrice :9.70;
                    @endphp
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">TOTAL</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">${{ number_format($totalGeneral, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">TOTAL T/C {{ number_format($exchangeRateP2P, 2) }}</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">Bs {{ number_format($totalGeneral * $exchangeRateP2P, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">Shipping Date: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 40%; vertical-align: top;">
                <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    @php
                    $totalImpuesto = $gravamenArancelario + $transporte_terrestre + $impuesto + $despachante + $agencia_despachante + $costoRepresentacion;
                    @endphp
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">TRANSPORTE TERRESTRE (Iquique - Oruro)</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($transporte_terrestre, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">REPRESENTACIÓN / USUARIO IMPORTACIÓN</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($costoRepresentacion, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">GRAVAMEN ARANCELARIO</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($gravamenArancelario, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">IMPUESTOS</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($impuesto, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">CARGOS DE IMPORTACION Y DESPACHO </td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($despachante, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">AGENCIA DESPACHANTE</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($agencia_despachante, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">TOTAL</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">${{ number_format($totalImpuesto, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">TOTAL T/C 6,96 Bs</td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">Bs {{ number_format($totalImpuesto * 6.96, 2) }}</td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
    <table style="width: 70%; border-collapse: collapse; margin-top: 20px; font-size: 12px;">
        <tr style="border: 1px solid #000;">
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">Total Estimado USD</td>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">${{ number_format($totalGeneral + $totalImpuesto, 2) }}</td>
        </tr>
        <tr style="border: 1px solid #000;">
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">Total Estimado Bs</td>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">Bs {{ number_format(($totalGeneral * $exchangeRateP2P) + ($totalImpuesto * 6.96) , 2) }}</td>
        </tr>
    </table>

    <div class="footer-note" style="margin-top: 20px;">
        <strong>NOTA:</strong><br>
        <ul class="footer-note-list">
            <li style="font-weight: bold; font-size: 12px;">La presente cotizacion tiene un plazo limite esteblecido.</li>
            <li style="font-weight: bold; font-size: 12px;">El pago expresado es dolares puede ser pagado en bolivianos al tipo de cambio del dia.</li>
            <li style="font-weight: bold; font-size: 12px;">Esta cotizacion podria sufrir alteraciones en caso de alguna revaloracion por parte de la aduana.</li>
            <li style="font-weight: bold; font-size: 12px;">Se aplica a carga regular, no peligrosos</li>
            <li style="font-weight: bold; font-size: 12px;">Asume que el consignatario que tiene cualquier permiso que sea requerido por autoridades en el país de destino</li>
            <li style="font-weight: bold; font-size: 12px;">Está sujeto a verificación de peso y medidas</li>
            <li style="font-weight: bold; font-size: 12px;">Requisitos de embarque: Factura comercial, Packing List.</li>
            <li style="font-weight: bold; font-size: 12px;">Contamos con nuestra Propia Agencia Despachante de manera opcional.</li>
            <li style="font-weight: bold; font-size: 12px;">El PAGO DE ADUANAS es una ves llegue la carga a Almacenes Aduaneros de Bolivia.</li>
            <li style="font-weight: bold; font-size: 12px;">Cotización en base a datos enviados por el cliente, al llegar a almacén, se verificarán peso y dimensiones.</li>
        </ul>
    </div>
</body>

</html>