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
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: #333;
            padding: 40px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            padding: 40px;
            color: white;
            text-align: center;
        }
        
        .logo {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 4px;
            margin-bottom: 10px;
        }
        
        .subtitle {
            font-size: 14px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .date {
            text-align: right;
            padding: 20px 40px;
            font-size: 12px;
            color: #666;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .content {
            padding: 40px;
        }
        
        .info-section {
            margin-bottom: 30px;
        }
        
        .info-title {
            font-size: 14px;
            font-weight: bold;
            color: #d97706;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f59e0b;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .info-item {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid #f59e0b;
        }
        
        .info-label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
        }
        
        .result-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 3px solid #f59e0b;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        
        .result-label {
            font-size: 14px;
            color: #92400e;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        
        .result-value {
            font-size: 48px;
            font-weight: bold;
            color: #b45309;
            margin: 10px 0;
        }
        
        .result-currency {
            font-size: 14px;
            color: #92400e;
        }
        
        .desglose-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .desglose-table th {
            background: #f59e0b;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .desglose-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        
        .desglose-table tr:last-child td {
            border-bottom: none;
        }
        
        .desglose-table tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .desglose-concepto {
            color: #4b5563;
        }
        
        .desglose-valor {
            text-align: right;
            font-weight: bold;
            color: #1f2937;
        }
        
        .footer {
            background: #1f2937;
            color: white;
            padding: 30px 40px;
            text-align: center;
        }
        
        .footer-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #f59e0b;
        }
        
        .footer-text {
            font-size: 12px;
            line-height: 1.6;
            color: #d1d5db;
            margin-bottom: 20px;
        }
        
        .contact-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .contact-item {
            font-size: 11px;
            color: #9ca3af;
        }
        
        .contact-value {
            color: #f59e0b;
            font-weight: bold;
        }
        
        .disclaimer {
            margin-top: 30px;
            padding: 20px;
            background: rgba(249, 250, 251, 0.1);
            border-radius: 8px;
            font-size: 10px;
            color: #9ca3af;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">IA GROUPS</div>
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
                <div class="info-title">📦 Detalles del Envío</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Peso Total</div>
                        <div class="info-value">{{ number_format($peso, 2) }} KG</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Volumen Total</div>
                        <div class="info-value">{{ number_format($volumen, 3) }} M³</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Puerto Origen</div>
                        <div class="info-value">{{ $origen }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Puerto Destino</div>
                        <div class="info-value">{{ $destino }}</div>
                    </div>
                </div>
                
                @if($valorMercancia > 0)
                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="info-label">Valor de Mercancía Declarado</div>
                    <div class="info-value">${{ number_format($valorMercancia, 2) }} USD</div>
                </div>
                @endif
            </div>
            
            <!-- Resultado Total -->
            <div class="result-box">
                <div class="result-label">Total Estimado</div>
                <div class="result-value">${{ $resultado }}</div>
                <div class="result-currency">USD - Dólares Americanos</div>
            </div>
            
            <!-- Desglose de Costos -->
            @if(count($desglose) > 0)
            <div class="info-section">
                <div class="info-title">💰 Desglose de Costos</div>
                <table class="desglose-table">
                    <thead>
                        <tr>
                            <th>Concepto</th>
                            <th style="text-align: right;">Monto (USD)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($desglose as $concepto => $valor)
                        <tr>
                            <td class="desglose-concepto">{{ $concepto }}</td>
                            <td class="desglose-valor">
                                @if(is_numeric($valor))
                                    ${{ number_format($valor, 2) }}
                                @else
                                    {{ $valor }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-title">¡Gracias por confiar en IA GROUPS!</div>
            <div class="footer-text">
                Esta cotización es válida por 7 días a partir de la fecha de emisión.
                Los precios pueden variar según disponibilidad y condiciones del mercado.
            </div>
            
            <div class="contact-info">
                <div class="contact-item">
                    📧 Email: <span class="contact-value">info@iagroups.com</span>
                </div>
                <div class="contact-item">
                    📱 WhatsApp: <span class="contact-value">+591 64700457</span>
                </div>
                <div class="contact-item">
                    🌐 Web: <span class="contact-value">www.iagroups.com</span>
                </div>
                <div class="contact-item">
                    📍 Oficina: <span class="contact-value">Santa Cruz, Bolivia</span>
                </div>
            </div>
            
            <div class="disclaimer">
                <strong>NOTA IMPORTANTE:</strong> Este documento es una cotización estimada y no constituye un contrato vinculante.
                Los precios finales pueden variar según el tipo de mercancía, embalaje, documentación adicional requerida,
                recargos por combustible, condiciones del puerto y regulaciones aduaneras vigentes. Para una cotización oficial
                y definitiva, por favor contacte directamente con nuestro equipo comercial.
            </div>
        </div>
    </div>
</body>
</html>
