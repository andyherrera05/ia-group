<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización {{ strtoupper($tipoCarga) }} - IA GROUPS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: #fff;
            color: #374151;
            padding: 30px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .header {
            background: #f59e0b;
            padding: 25px;
            color: white;
            text-align: center;
        }
        
        .header-content {
            margin-bottom: 10px;
        }
        
        .header-logo {
            height: 45px;
            vertical-align: middle;
            margin-right: 12px;
            display: inline-block;
        }
        
        .logo {
            display: inline-block;
            vertical-align: middle;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        
        .subtitle {
            font-size: 12px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        
        .title {
            font-size: 20px;
            font-weight: bold;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .date {
            text-align: right;
            padding: 10px 30px;
            font-size: 11px;
            color: #6b7280;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .content {
            padding: 30px;
        }
        
        .info-section {
            margin-bottom: 25px;
        }
        
        .info-title {
            font-size: 13px;
            font-weight: bold;
            color: #b45309;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 2px solid #fef3c7;
        }
        
        .info-grid {
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        
        .info-cell {
            display: table-cell;
            width: 50%;
            padding: 0 10px;
        }
        
        .info-item {
            background: #f9fafb;
            padding: 12px;
            border-radius: 6px;
            border-left: 3px solid #f59e0b;
        }
        
        .info-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        
        .info-value {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
        }
        
        .calc-summary {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 25px;
        }
        
        .calc-title {
            font-size: 11px;
            font-weight: bold;
            color: #92400e;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        
        .calc-detail {
            font-size: 13px;
            color: #4b5563;
        }

        .result-box {
            background: #f59e0b;
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            margin: 25px 0;
            color: white;
        }
        
        .result-label {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        
        .result-value {
            font-size: 40px;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .result-currency {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .desglose-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .desglose-table th {
            background: #f3f4f6;
            color: #374151;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .desglose-table td {
            padding: 10px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
        }
        
        .row-section {
            background: #f9fafb;
            font-weight: bold;
            color: #1f2937;
            font-size: 12px !important;
            padding-top: 15px !important;
            padding-bottom: 5px !important;
            text-transform: uppercase;
        }
        
        .row-subtotal {
            font-weight: bold;
            color: #b45309;
            background: #fffbeb;
            border-top: 1px solid #fde68a;
        }
        
        .indent-1 { padding-left: 20px !important; font-size: 12px; color: #4b5563; }
        .indent-2 { padding-left: 40px !important; font-size: 11px; color: #6b7280; }
        
        .footer {
            background: #111827;
            color: white;
            padding: 25px 30px;
            text-align: center;
        }
        
        .footer-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #f59e0b;
        }
        
        .footer-text {
            font-size: 11px;
            line-height: 1.5;
            color: #9ca3af;
            margin-bottom: 15px;
        }
        
        .contact-grid {
            display: table;
            width: 100%;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 15px;
        }
        
        .contact-cell {
            display: table-cell;
            width: 50%;
            font-size: 10px;
            padding: 5px;
            color: #9ca3af;
        }
        
        .contact-value {
            color: #fca311;
            font-weight: bold;
        }
        
        .disclaimer {
            margin-top: 20px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            font-size: 9px;
            color: #6b7280;
            line-height: 1.4;
            text-align: justify;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <img src="{{ public_path('images/bg_pdf.png') }}" class="header-logo">
                <div class="logo">IA GROUPS</div>
            </div>
            <div class="subtitle">Logística Internacional</div>
            <div class="title">COTIZACIÓN DE ENVÍO {{ strtoupper($tipoCarga) }}</div>
        </div>
        
        <!-- Fecha -->
        <div class="date">
            Generado: {{ $fecha }}
        </div>
        
        <!-- Contenido -->
        <div class="content">
            <!-- Información del Envío -->
            <div class="info-section">
                <div class="info-title">Detalles del Envío</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-cell">
                            <div class="info-item">
                                <div class="info-label">Peso Total</div>
                                <div class="info-value">{{ number_format($peso, 2) }} KG</div>
                            </div>
                        </div>
                        <div class="info-cell">
                            <div class="info-item">
                                <div class="info-label">Volumen Total</div>
                                <div class="info-value">{{ number_format($volumen, 3) }} M³</div>
                            </div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-cell">
                            <div class="info-item">
                                <div class="info-label">Puerto Origen</div>
                                <div class="info-value">{{ $origen ?: 'No especificado' }}</div>
                            </div>
                        </div>
                        <div class="info-cell">
                            <div class="info-item">
                                <div class="info-label">Puerto Destino</div>
                                <div class="info-value">{{ $destino ?: 'No especificado' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-cell">
                            <div class="info-item">
                                <div class="info-label">Dimensiones</div>
                                <div class="info-value">{{ $largo ?? '0' }}x{{ $ancho ?? '0' }}x{{ $alto ?? '0' }} cm</div>
                            </div>
                        </div>
                        <div class="info-cell">
                            <div class="info-item">
                                <div class="info-label">Cantidad</div>
                                <div class="info-value">{{ $cantidad ?? '1' }} unid.</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($valorMercancia > 0)
                <div class="info-item" style="width: 100%;">
                    <div class="info-label">Valor de Mercancía Declarado</div>
                    <div class="info-value">${{ number_format($valorMercancia, 2) }} USD</div>
                </div>
                @endif
            </div>

            <!-- Resumen de Cálculo -->
            <div class="calc-summary">
                <div class="calc-title">Base de Cotización</div>
                <div class="calc-detail">
                    Carga cobrada por <strong>{{ $tipoCobro }}</strong>. 
                    Concepto: {{ $tipoCobro == 'CBM' ? 'Volumen facturado' : 'Peso facturado' }}: 
                    <strong>{{ is_numeric($cbmFacturado) ? number_format($cbmFacturado, 2) : number_format($peso, 2) }} {{ $unidad ?: 'kg' }}</strong>.
                </div>
            </div>
            
            <!-- Resultado Total -->
            <div class="result-box">
                <div class="result-label">Total Estimado Puerta a Puerta</div>
                <div class="result-value">${{ $resultado }}</div>
                <div class="result-currency">USD - Dólares Americanos</div>
            </div>
            
            <!-- Desglose de Costos -->
            @if(count($desglose) > 0)
                @php
                    $principales = [];
                    $detalles = [];
                    
                    // Definición de conceptos que siempre van al resumen principal
                    $conceptosPrincipales = [
                        'Valor de Mercancía', 
                        'Costo de Envío de Paquete', 
                        'Agencia Despachante', 
                        'Recojo desde Almacén'
                    ];

                    foreach($desglose as $concepto => $valor) {
                        $isMain = false;
                        foreach($conceptosPrincipales as $main) {
                            if (str_contains($concepto, $main)) { $isMain = true; break; }
                        }
                        if (str_contains($concepto, 'Entrega a')) { $isMain = true; }

                        if ($isMain && !is_null($valor)) {
                            $principales[$concepto] = $valor;
                        } else {
                            $detalles[$concepto] = $valor;
                        }
                    }
                @endphp

                <!-- Tabla 1: Resumen de Inversión -->
                <div class="info-section">
                    <div class="info-title">Resumen de Inversión</div>
                    <table class="desglose-table">
                        <thead>
                            <tr>
                                <th>Concepto</th>
                                <th style="text-align: right;">Monto (USD)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($principales as $concepto => $valor)
                                <tr>
                                    <td style="font-weight: bold; color: #111827;">{{ $concepto }}</td>
                                    <td style="text-align: right; font-weight: bold; color: #b45309;">
                                        ${{ is_numeric($valor) ? number_format($valor, 2) : $valor }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Tabla 2: Detalle Operativo (Opcional) -->
                @if(count($detalles) > 0)
                <div class="info-section" style="margin-top: 20px;">
                    <div class="info-title" style="font-size: 11px; color: #6b7280; border-bottom: 1px solid #e5e7eb;">Detalle Operativo del Flete (Referencial)</div>
                    <table class="desglose-table" style="background: #fdfdfd;">
                        <thead>
                            <tr>
                                <th style="font-size: 10px; color: #6b7280;">Componente logístico</th>
                                <th style="text-align: right; font-size: 10px; color: #6b7280;">Desglose Unitario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detalles as $concepto => $valor)
                                @php
                                    $isSection = is_null($valor);
                                    $isSubtotal = str_contains(strtolower($concepto), 'subtotal');
                                    $isSubItem = str_contains($concepto, '├─') || str_contains($concepto, '└─');
                                    // Limpieza agresiva para el PDF
                                    $cleanConcepto = preg_replace('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\(\)\$.,\-:%]/u', '', $concepto);
                                    $cleanConcepto = trim($cleanConcepto);
                                @endphp
                                <tr class="{{ $isSection ? 'row-section' : ($isSubtotal ? 'row-subtotal' : '') }}">
                                    <td class="{{ $isSubItem ? 'indent-1' : '' }}" style="font-size: 11px;">
                                        {{ $cleanConcepto }}
                                    </td>
                                    <td style="text-align: right; font-size: 11px;">
                                        @if(!$isSection)
                                            ${{ is_numeric($valor) ? number_format($valor, 2) : $valor }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            @endif
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-title">¡Gracias por confiar en IA GROUPS!</div>
            <div class="footer-text">
                IA GROUPS es su aliado estratégico en logística internacional, conectando mercados con eficiencia y seguridad.
                Esta propuesta de servicios es válida por 7 días calendario.
            </div>
            
            <div class="contact-grid">
                <div class="contact-cell">
                    Email: <span class="contact-value">info@iagroups.com</span>
                </div>
                <div class="contact-cell">
                    WhatsApp: <span class="contact-value">+591 72976032</span>
                </div>
                <div class="contact-cell">
                    Web: <span class="contact-value">www.iagroups.com</span>
                </div>
                <div class="contact-cell">
                    Oficina: <span class="contact-value">Tarija, Bolivia</span>
                </div>
            </div>
            
            <div class="disclaimer">
                <strong>TÉRMINOS Y CONDICIONES:</strong> Esta cotización es referencial y está sujeta a revisión al momento del embarque. 
                El flete marítimo no incluye impuestos de importación, aranceles aduaneros en destino, demoras por inspecciones 
                gubernamentales o servicios de almacenaje extraordinarios. Los tiempos de tránsito son estimados y dependen 
                exclusivamente de las navieras. IA GROUPS no se hace responsable por retrasos derivados de causas de fuerza mayor.
            </div>
        </div>
    </div>
</body>
</html>
