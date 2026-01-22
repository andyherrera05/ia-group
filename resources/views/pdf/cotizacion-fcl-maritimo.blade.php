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

    <!-- Main Items Table (Container Only) -->
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
                <td>@if(!empty($containerBase64))
                    <img src="{{ $containerBase64 }}" alt="Container" style="max-width: 100px; max-height: 100px; object-fit: contain;">
                    @else
                    <div style="color: #9ca3af; font-size: 10px; font-style: italic;">Sin imagen</div>
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