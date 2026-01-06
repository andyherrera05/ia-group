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
            font-size: 14px;
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
                    <div class="agent-line">AGENTE DE CARGA: {{ $agente['nombre'] ?? 'IA GROUPS' }}</div>
                    <div class="contact-line">pagina: https://ia-groups.com &nbsp;&nbsp; correo: {{ $agente['email'] ?? 'info@iagroups.com' }} &nbsp;&nbsp; contacto: {{ $agente['telefono'] ?? '+591 702693251' }}</div>
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
                    <td>{{ $prod['cantidad'] }}</td>
                    <td>c/u</td>
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
        <h4 style="margin-top: 25px; margin-bottom: 10px; font-size: 14px; text-transform: uppercase;">Detalle de Gastos Adicionales / Logística</h4>
        <table class="product-table" style="margin-bottom: 20px;">
            <thead>
                <tr>
                    <th style="width: 70%; text-align: left; padding-left: 15px;">CONCEPTO / SERVICIO</th>
                    <th style="width: 30%; text-align: right; padding-right: 15px;">MONTO (USD)</th>
                </tr>
            </thead>
            <tbody>
                @php
                $subtotalGastos = 0;
                // El flete principal está en $desglose['Costo de Envío de Paquete']
                $fleteInternacional = (float)($desglose['Costo de Envío de Paquete'] ?? 0);
                $valorMercancia = (float)($desglose['Valor de Mercancía'] ?? 0);
                @endphp

                {{-- Mostramos el Flete Marítimo primero --}}
                <tr style="height: auto;">
                    <td style="text-align: left; padding: 8px 15px; height: auto;">Costo de Envío de Paquete</td>
                    <td style="text-align: right; padding: 8px 15px; height: auto; font-weight: bold;">$ {{ number_format($fleteInternacional, 2) }}</td>
                </tr>

                {{-- Mostramos los gastos adicionales (servicios, entrega, verificaciones) --}}
                @foreach($gastosAdicionales as $concepto => $monto)
                @php
                $montoNum = is_numeric($monto) ? (float)$monto : 0;
                $subtotalGastos += $montoNum;
                @endphp
                <tr style="height: auto;">
                    <td style="text-align: left; padding: 8px 15px; height: auto;">{{ $concepto }}</td>
                    <td style="text-align: right; padding: 8px 15px; height: auto; font-weight: bold;">$ {{ number_format($montoNum, 2) }}</td>
                </tr>
                @endforeach

                @php
                // Usamos el resultado pre-calculado si está disponible, de lo contrario recalculamos
                $granTotal = isset($resultado) && is_numeric($resultado) ? (float)$resultado : ($valorMercancia + $fleteInternacional + $subtotalGastos);
                @endphp
                <tr style="background-color: #fb9e00; font-weight: bold;">
                    <td style="text-align: right;">TOTAL GENERAL ESTIMADO (USD)</td>
                    <td style="text-align: right; font-size: 16px;">$ {{ number_format($granTotal, 2) }}</td>
                </tr>
                <tr style="background-color: #fb9e00; font-weight: bold;">
                    <td style="text-align: right;">TOTAL GENERAL ESTIMADO (BS)</td>
                    <td style="text-align: right; font-size: 16px;">Bs {{ number_format($granTotal * 9.61, 2) }}</td>
                </tr>
            </tbody>
        </table>


        <div class="footer-note" style="margin-top: 40px;">
            <strong>NOTAS:</strong><br>
            <ul class="footer-note-list">
                <li>ESTA COTIZACIÓN TIENE UNA VALIDEZ DE 7 DÍAS</li>
                <li>LOS PRECIOS NO INCLUYEN IMPUESTOS DE ADUANA EN DESTINO</li>
                <li>Esta cotizacion podria sufrir alteraciones en caso de alguna revaloracion por parte de la aduana.</li>
                <li>Asume que el consignatario que tiene cualquier permiso que sea requerido por autoridades en el país de destino</li>
                <li>Requisitos de embarque: Factura comercial, Packing List.</li>
                <li>Contamos con nuestra Propia Agencia Despachante de manera opcional.</li>
                <li>El PAGO DE ADUANAS es una ves llegue la carga a Almacenes Aduaneros de Bolivia.</li>
                <li>Cotización en base a datos enviados por el cliente, al llegar a almacén, se verificarán peso y dimensiones.</li>
            </ul>
        </div>

    </div>
</body>

</html>