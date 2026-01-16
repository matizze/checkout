<x-layout.checkout title="Pagamento PIX" :step="3">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- QR Code PIX --}}
        <x-card class="text-center bg-white">
            <div class="flex items-center justify-center mb-4">
                <x-lucide-qr-code class="size-6 text-blue-base mr-2" />
                <h3 class="text-lg font-semibold text-grayin-100">Pague com PIX</h3>
            </div>

            <p class="text-sm text-grayin-300 mb-6">
                Escaneie o QR Code abaixo com o app do seu banco
            </p>

            @if ($payment->pix_qrcode_base64)
                <div class="bg-grayin-600 p-4 rounded-lg inline-block mb-6">
                    <img
                        src="data:image/png;base64,{{ $payment->pix_qrcode_base64 }}"
                        alt="QR Code PIX"
                        class="w-48 h-48"
                    >
                </div>
            @endif

            <div class="space-y-4">
                <p class="text-sm text-grayin-300">Ou copie o codigo PIX:</p>

                <div class="relative">
                    <input
                        type="text"
                        id="pix-code"
                        value="{{ $payment->pix_payload }}"
                        readonly
                        class="w-full rounded-md bg-grayin-600 border border-grayin-500 px-4 py-3 text-grayin-100 text-xs pr-24"
                    >
                    <button
                        type="button"
                        onclick="copyPixCode()"
                        class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-blue-base text-white text-sm font-medium rounded hover:bg-blue-dark transition-colors"
                    >
                        <span id="copy-text">Copiar</span>
                    </button>
                </div>
            </div>

            <div class="mt-6 p-4 bg-grayin-500 rounded-lg">
                <div class="flex items-center justify-center text-sm text-grayin-200">
                    <x-lucide-clock class="size-4 mr-2" />
                    <span>Vencimento: {{ $payment->due_date->format('d/m/Y') }}</span>
                </div>
            </div>
        </x-card>

        {{-- Resumo e Status --}}
        <x-card class="bg-white">
            <h3 class="text-lg font-semibold text-grayin-100 mb-4">Detalhes do Pagamento</h3>

            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-grayin-500">
                    <span class="text-grayin-300">Produto</span>
                    <span class="text-grayin-100">{{ $product->name }}</span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-grayin-500">
                    <span class="text-grayin-300">Metodo</span>
                    <span class="text-grayin-100 flex items-center">
                        <x-lucide-landmark class="size-4 mr-2 text-blue-base" />
                        PIX
                    </span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-grayin-500">
                    <span class="text-grayin-300">Status</span>
                    <span id="payment-status" @class([
                        'px-3 py-1 rounded-full text-sm font-medium',
                        'bg-feedback-progress/20 text-feedback-progress' => $payment->status === 'PENDING',
                        'bg-feedback-done/20 text-feedback-done' => in_array($payment->status, ['RECEIVED', 'CONFIRMED']),
                        'bg-feedback-danger/20 text-feedback-danger' => in_array($payment->status, ['OVERDUE', 'REFUNDED']),
                    ])>
                        @switch($payment->status)
                            @case('PENDING')
                                Aguardando pagamento
                                @break
                            @case('RECEIVED')
                            @case('CONFIRMED')
                                Pago
                                @break
                            @case('OVERDUE')
                                Vencido
                                @break
                            @default
                                {{ $payment->status }}
                        @endswitch
                    </span>
                </div>

                <div class="flex justify-between items-center py-2">
                    <span class="text-lg font-semibold text-grayin-100">Total</span>
                    <span class="text-2xl font-bold text-blue-base">
                        R$ {{ number_format($payment->amount, 2, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="mt-6 space-y-3">
                <a
                    href="{{ route('checkout.status', $payment) }}"
                    class="flex items-center justify-center w-full px-6 py-3 bg-blue-base text-white font-semibold rounded-lg hover:bg-blue-dark transition-colors"
                >
                    <x-lucide-refresh-cw class="size-4 mr-2" />
                    Verificar Status
                </a>

                <p class="text-xs text-grayin-400 text-center">
                    A pagina sera atualizada automaticamente quando o pagamento for confirmado
                </p>
            </div>
        </x-card>
    </div>

    {{-- Instrucoes --}}
    <x-card class="bg-white">
        <h3 class="text-lg font-semibold text-grayin-100 mb-4">Como pagar com PIX</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex items-start space-x-3">
                <div class="flex items-center justify-center size-8 rounded-full bg-blue-base text-white text-sm font-semibold shrink-0">
                    1
                </div>
                <div>
                    <p class="font-medium text-grayin-100">Abra o app do seu banco</p>
                    <p class="text-sm text-grayin-300">Acesse a area PIX do aplicativo</p>
                </div>
            </div>

            <div class="flex items-start space-x-3">
                <div class="flex items-center justify-center size-8 rounded-full bg-blue-base text-white text-sm font-semibold shrink-0">
                    2
                </div>
                <div>
                    <p class="font-medium text-grayin-100">Escaneie o QR Code</p>
                    <p class="text-sm text-grayin-300">Ou copie e cole o codigo PIX</p>
                </div>
            </div>

            <div class="flex items-start space-x-3">
                <div class="flex items-center justify-center size-8 rounded-full bg-blue-base text-white text-sm font-semibold shrink-0">
                    3
                </div>
                <div>
                    <p class="font-medium text-grayin-100">Confirme o pagamento</p>
                    <p class="text-sm text-grayin-300">O status sera atualizado em segundos</p>
                </div>
            </div>
        </div>
    </x-card>
</x-layout.checkout>

<script>
    function copyPixCode() {
        const input = document.getElementById('pix-code');
        const copyText = document.getElementById('copy-text');

        navigator.clipboard.writeText(input.value).then(() => {
            copyText.textContent = 'Copiado!';
            setTimeout(() => {
                copyText.textContent = 'Copiar';
            }, 2000);
        });
    }

    // Polling para verificar status do pagamento
    function checkPaymentStatus() {
        fetch('{{ route('checkout.status.check', $payment) }}')
            .then(response => response.json())
            .then(data => {
                if (data.paid) {
                    window.location.href = '{{ route('checkout.status', $payment) }}';
                }
            });
    }

    // Verificar a cada 5 segundos
    setInterval(checkPaymentStatus, 5000);
</script>
