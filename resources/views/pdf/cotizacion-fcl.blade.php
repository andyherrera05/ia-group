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
            background: #e5eef7;
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
        .col-ref { width: 8%; font-weight: bold; text-transform: uppercase; }
        .col-foto { width: 30%; }
        .col-desc { width: 20%; font-weight: bold; }
        .col-cant { width: 8%; font-weight: bold; }
        .col-unid { width: 8%; font-weight: bold; }
        .col-price { width: 12%; font-weight: bold; }
        .col-empty { width: 8%; }
        .col-total { width: 12%; font-weight: bold; }

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
            font-size: 16px;
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
        
        .clear { clear: both; }

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
                    <div class="address-line">Direccion Internacional: Wrigley Building, Magnificent Mile, Chicago, IL 60611, USA, Phone: +1 312 665 6656</div>
                    <div class="address-line">Direccion Bolivia: Tarija colon y bolivar 820</div>
                    <div class="agent-line">AGENTE DE CARGA: {{ $agente['nombre'] ?? 'IA GROUPS' }}</div>
                    <div class="contact-line">pagina: https://ia-groups.com &nbsp;&nbsp; correo: {{ $agente['email'] ?? 'info@iagroups.com' }} &nbsp;&nbsp; telefono: {{ $agente['telefono'] ?? '+591 702693251' }}</div>
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
                <td class="col-ref">{{ $item['ref'] ?? 'JULIO' }}</td>
                <td class="col-foto">
                    @if(!empty($item['imagen']))
                        <img src="{{ $item['imagen'] }}" class="product-img">
                    @endif
                </td>
                <td class="col-desc">
                    {{ $item['descripcion'] ?? 'Flete Marítimo (COSCO - 40\' Standard)' }}
                </td>
                <td class="col-cant">{{ $item['cantidad'] ?? '1' }}</td>
                <td class="col-unid">{{ $item['unidad'] ?? 'PCS' }}</td>
                <td class="col-price">
                    <div class="price-val">
                        <span class="currency-prefix">$</span>
                        <span class="amount-val">{{ number_format($item['precio'] ?? 0, 2) }}</span>
                        <div class="clear"></div>
                    </div>
                </td>
                <td class="col-total">
                    <div class="price-val">
                        <span class="currency-prefix">$</span>
                        <span class="amount-val">{{ number_format($item['total'] ?? 0, 2) }}</span>
                        <div class="clear"></div>
                    </div>
                </td>
            </tr>           
        </tbody>
    </table>

    <!-- Detailed Cost Summary Table -->
    <table style="width: 70%; border-collapse: collapse; margin-top: 20px; font-size: 11px;">
        @php
            $item = $desglose_reporte;
            $valorCarga = $item['valorMercancia'] ?? 0;
            $fleteMaritimo = $item['precio'] ?? 0;
            // Costos fijos/hardcoded
            $seguroComisiones = ($fleteMaritimo + $valorCarga) * 0.07; // Aproximación basada en Booking 50%
            $costoAdicionalCargaPeligrosa = 250;
            $totalGeneral = $valorCarga + $fleteMaritimo + $seguroComisiones + $costoAdicionalCargaPeligrosa;
        @endphp
        
        <tr>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold; width: 80%;">VALOR DE CARGA</td>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; width: 20%;">${{ number_format($valorCarga, 2) }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">COSTO DE RECOGIDA DESDE LA FABRICA A NUESTRO ALMACEN</td>
            <td style="border: 1px solid #000; padding: 4px; text-align: right;">$0.00</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">SERVICIO DE BUSQUEDA DE PRODUCTOS</td>
            <td style="border: 1px solid #000; padding: 4px; text-align: right;">$0.00</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">SERVICIO DE INSPECCION DE CALIDAD</td>
            <td style="border: 1px solid #000; padding: 4px; text-align: right;">$0.00</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">SERVICIO DE ENVIO MARITIMO {{ $origen }} - {{ $destino }}</td>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">${{ number_format($fleteMaritimo, 2) }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">COSTO ADICIONAL DE ENVIO POR CARGA PELIGROSA CON CERTIFICACION</td>
            <td style="border: 1px solid #000; padding: 4px; text-align: right;">$0.00</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">COSTO ADICIONAL DE ENVIO POR CARGA PELIGROSA SIN CERTIFICACION</td>
            <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($costoAdicionalCargaPeligrosa, 2) }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">DESCONSOLIDACION</td>
            <td style="border: 1px solid #000; padding: 4px; text-align: right;">$0.00</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">GESTION PORTUARIA</td>
            <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($item['gestionPortuaria'] ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">BOOKING Y CUPO DE CARGA</td>
            <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($item['booking'] ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">SEGURO, GIRO INTERNACIONAL Y COMISIONES</td>
            <td style="border: 1px solid #000; padding: 4px; text-align: right;">${{ number_format($seguroComisiones, 2) }}</td>
        </tr>
        @php
            // Update total to include port fees and booking
            $totalGeneral = $valorCarga + $fleteMaritimo + $seguroComisiones + $costoAdicionalCargaPeligrosa + ($item['gestionPortuaria'] ?? 0) + ($item['booking'] ?? 0);
        @endphp
        <tr>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">TOTAL</td>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">${{ number_format($totalGeneral, 2) }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">TOTAL T/C 9,62 Bs</td>
            <td style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">Bs {{ number_format($totalGeneral * 9.62, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid #000; padding: 4px; text-align: right; font-weight: bold;">Shipping Date: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</td>
        </tr>
    </table>

    <div class="footer-note" style="margin-top: 40px;">
        <strong>NOTAS:</strong><br>
        <ul class="footer-note-list">
            <li>La presente cotizacion tiene un plazo limite esteblecido.</li>
            <li>El pago expresado es dolares puede ser pagado en bolivianos al tipo de cambio del dia.</li>
            <li>Esta cotizacion podria sufrir alteraciones en caso de alguna revaloracion por parte de la aduana.</li>
            <li>Se aplica a carga regular, no peligrosos</li>
            <li>Asume que el consignatario  que tiene cualquier permiso que sea requerido por autoridades en el país de destino</li>
            <li>Está sujeto a verificación de peso y medidas</li>
            <li>Requisitos de embarque: Factura comercial, Packing List.</li>
            <li>Contamos con nuestra Propia Agencia Despachante de manera opcional.</li>
            <li>El PAGO DE ADUANAS es una ves llegue la carga a Almacenes Aduaneros de Bolivia.</li>
            <li>Cotización en base a datos enviados por el cliente, al llegar a almacén, se verificarán peso y dimensiones.</li>
        </ul>
    </div>
</body>
</html>
