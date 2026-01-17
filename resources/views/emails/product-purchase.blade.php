<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Confirmada</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #f9fafb;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            padding: 32px;
            text-align: center;
        }
        
        .header h1 {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
        }
        
        .content {
            padding: 32px;
        }
        
        .section {
            margin-bottom: 24px;
        }
        
        .section h2 {
            color: #1f2937;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .product-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            background: #f9fafb;
        }
        
        .product-name {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .product-price {
            font-size: 24px;
            font-weight: 700;
            color: #3b82f6;
        }
        
        .order-details {
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #e5e7eb;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .detail-row:last-child {
            border-bottom: none;
            padding-top: 16px;
            margin-top: 8px;
            border-top: 2px solid #3b82f6;
        }
        
        .detail-label {
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
        }
        
        .detail-value {
            color: #1f2937;
            font-weight: 600;
        }
        
        .detail-value.total {
            color: #3b82f6;
            font-size: 20px;
        }
        
        .message {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 24px;
        }
        
        .message-text {
            color: #92400e;
            white-space: pre-line;
        }
        
        .footer {
            text-align: center;
            padding: 24px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }
        
        .footer-text {
            color: #6b7280;
            font-size: 14px;
        }
        
        .icon {
            width: 20px;
            height: 20px;
            display: inline-block;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 12px;
            background: #dcfce7;
            color: #166534;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        @media (max-width: 640px) {
            .container {
                padding: 10px;
            }
            
            .header {
                padding: 24px;
            }
            
            .content {
                padding: 24px;
            }
            
            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <!-- Header -->
            <div class="header">
                <h1>🎉 Compra Confirmada!</h1>
                <p>Obrigado por comprar conosco</p>
            </div>
            
            <!-- Content -->
            <div class="content">
                <!-- Custom Message -->
                @if($message = $mail->getProcessedMessage())
                    <div class="section">
                        <div class="message">
                            <div class="message-text">{{ $message }}</div>
                        </div>
                    </div>
                @endif
                
                <!-- Product Info -->
                <div class="section">
                    <h2>
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Produto Comprado
                    </h2>
                    <div class="product-card">
                        <div class="product-name">{{ $mail->product->name }}</div>
                        @if($mail->product->description)
                            <p style="color: #6b7280; font-size: 14px; margin-bottom: 12px;">{{ $mail->product->description }}</p>
                        @endif
                        <div class="product-price">R$ {{ number_format($mail->product->price / 100, 2, ',', '.') }}</div>
                    </div>
                </div>
                
                <!-- Order Details -->
                <div class="section">
                    <h2>
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Detalhes do Pedido
                    </h2>
                    <div class="order-details">
                        <div class="detail-row">
                            <span class="detail-label">Número do Pedido</span>
                            <span class="detail-value">#{{ $mail->order->id }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Cliente</span>
                            <span class="detail-value">{{ $mail->customer->name }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Status</span>
                            <span class="badge">Confirmado</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Total</span>
                            <span class="detail-value total">R$ {{ number_format($mail->order->total_amount / 100, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Attachment Notice -->
                @if($mail->product->attachment)
                    <div class="section">
                        <h2>
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            Material de Download
                        </h2>
                        <div style="background: #eff6ff; border: 1px solid #3b82f6; border-radius: 8px; padding: 16px; color: #1e40af;">
                            <p style="font-weight: 600; margin-bottom: 8px;">📎 Arquivo anexado neste e-mail</p>
                            <p style="font-size: 14px;">O material complementar do seu produto está disponível para download no anexo deste e-mail.</p>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <p class="footer-text">
                    © {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.<br>
                    Este e-mail foi gerado automaticamente.
                </p>
            </div>
        </div>
    </div>
</body>
</html>