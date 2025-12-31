<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }
        .container {
            padding: 20px 30px;
        }
        /* Header */
        .header {
            border-bottom: 3px solid #f59e0b;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-content {
            display: table;
            width: 100%;
        }
        .logo-section {
            display: table-cell;
            width: 60%;
            vertical-align: middle;
        }
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
        }
        .logo-subtitle {
            font-size: 10px;
            color: #6b7280;
            margin-top: 3px;
        }
        .doc-info {
            display: table-cell;
            width: 40%;
            text-align: right;
            vertical-align: middle;
        }
        .doc-number {
            font-size: 14px;
            font-weight: bold;
            color: #f59e0b;
        }
        .doc-date {
            font-size: 10px;
            color: #6b7280;
            margin-top: 3px;
        }
        /* Title */
        .title-bar {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 12px 20px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        /* Sections */
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background: #1f2937;
            color: white;
            padding: 8px 15px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            padding: 8px 12px;
            text-align: left;
            border: 1px solid #e5e7eb;
        }
        th {
            background: #f3f4f6;
            font-weight: bold;
            color: #374151;
            font-size: 10px;
            text-transform: uppercase;
        }
        td {
            font-size: 11px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        /* Info Grid */
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-col {
            display: table-cell;
            width: 50%;
            padding-right: 15px;
            vertical-align: top;
        }
        .info-col:last-child {
            padding-right: 0;
            padding-left: 15px;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .info-label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .info-value {
            font-size: 12px;
            font-weight: bold;
            color: #1f2937;
        }
        /* Highlight Box */
        .highlight-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 15px 20px;
            text-align: center;
            margin: 20px 0;
        }
        .highlight-label {
            font-size: 10px;
            color: #92400e;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .highlight-value {
            font-size: 28px;
            font-weight: bold;
            color: #92400e;
        }
        .highlight-currency {
            font-size: 12px;
            color: #b45309;
        }
        /* Product Info */
        .product-box {
            background: #eff6ff;
            border: 1px solid #3b82f6;
            border-radius: 5px;
            padding: 12px 15px;
            margin-bottom: 15px;
        }
        .product-code {
            font-family: monospace;
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
        }
        .product-desc {
            font-size: 11px;
            color: #374151;
            margin-top: 5px;
        }
        .product-arancel {
            font-size: 12px;
            font-weight: bold;
            color: #059669;
            margin-top: 5px;
        }
        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            font-size: 9px;
            color: #6b7280;
            text-align: center;
        }
        .footer-note {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 9px;
            color: #92400e;
        }
        /* Row styles */
        .row-total {
            background: #fef3c7 !important;
            font-weight: bold;
        }
        .row-total td {
            border-color: #f59e0b;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <div class="logo-section">
                    <div class="logo-text">🚢 IA GROUPS</div>
                    <div class="logo-subtitle">Comercio Internacional & Logística</div>
                </div>
                <div class="doc-info">
                    <div class="doc-number">{{ $numero }}</div>
                    <div class="doc-date">{{ $fecha }}</div>
                </div>
            </div>
        </div>

        <!-- Title -->
        <div class="title-bar">
            📋 {{ $titulo }}
        </div>

        <!-- Product Info -->
        @if($producto['codigo_hs'])
        <div class="section">
            <div class="section-title">📦 INFORMACIÓN DEL PRODUCTO</div>
            <div class="product-box">
                <div class="product-code">{{ $producto['codigo_hs'] }}</div>
                <div class="product-desc">{{ $producto['descripcion'] }}</div>
                <div class="product-arancel">Gravamen Arancelario: {{ $producto['arancel'] }}%</div>
            </div>
        </div>
        @endif

        <!-- Cargo & Values Info -->
        <div class="info-grid">
            <div class="info-col">
                <div class="section-title">📦 DATOS DE CARGA</div>
                <table>
                    <tr>
                        <td class="info-label">Cantidad de Bultos</td>
                        <td class="text-right info-value">{{ $carga['bultos'] ?? 1 }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Peso Bruto (KG)</td>
                        <td class="text-right info-value">{{ $carga['peso'] ?: 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Volumen (M³)</td>
                        <td class="text-right info-value">{{ $carga['volumen'] ?: 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Dimensiones</td>
                        <td class="text-right info-value">{{ $carga['dimensiones'] }}</td>
                    </tr>
                </table>
            </div>
            <div class="info-col">
                <div class="section-title">💵 VALORES CIF</div>
                <table>
                    <tr>
                        <td class="info-label">Valor FOB</td>
                        <td class="text-right info-value">$ {{ number_format($valores['fob'], 2) }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Valor Flete</td>
                        <td class="text-right info-value">$ {{ number_format($valores['flete'], 2) }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Valor Seguro</td>
                        <td class="text-right info-value">$ {{ number_format($valores['seguro'], 2) }}</td>
                    </tr>
                    <tr class="row-total">
                        <td class="info-label">VALOR CIF</td>
                        <td class="text-right info-value">$ {{ number_format($valores['cif'], 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Desglose de Impuestos -->
        <div class="section">
            <div class="section-title">📊 DESGLOSE DE IMPUESTOS</div>
            <table>
                <thead>
                    <tr>
                        <th>CONCEPTO</th>
                        <th class="text-right">VALOR (USD)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($desglose as $concepto => $valor)
                    <tr class="{{ str_contains($concepto, 'Total') ? 'row-total' : '' }}">
                        <td>{{ $concepto }}</td>
                        <td class="text-right">
                            @if(str_contains($concepto, 'Código') || str_contains($concepto, 'Producto'))
                                {{ $valor }}
                            @else
                                $ {{ $valor }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Highlight Box -->
        <div class="highlight-box">
            <div class="highlight-label">💰 TOTAL ESTIMADO DE IMPUESTOS</div>
            <div class="highlight-value">$ {{ $resultado }}</div>
            <div class="highlight-currency">Dólares Americanos (USD)</div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-note">
                ⚠️ <strong>IMPORTANTE:</strong> Esta proforma es un estimado basado en la información proporcionada. 
                Los valores finales pueden variar según la clasificación arancelaria definitiva y las regulaciones vigentes al momento de la importación.
                Consulte con un agente de aduanas autorizado para obtener valores exactos.
            </div>
            <p>Documento generado el {{ $fecha }} | IA GROUPS - Comercio Internacional</p>
            <p>www.iagroups.com | contacto@iagroups.com | +591 123 456 789</p>
        </div>
    </div>
</body>
</html>