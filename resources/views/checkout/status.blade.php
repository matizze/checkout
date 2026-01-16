<x-layout.checkout title="Status do Pagamento" :step="3">
    <div class="max-w-2xl mx-auto">
        {{-- Status Card --}}
        <x-card class="text-center bg-white">
            @if (in_array($payment->status, ['RECEIVED', 'CONFIRMED']))
                <div class="flex items-center justify-center size-20 rounded-full bg-feedback-done/20 mx-auto mb-6">
                    <x-lucide-check class="size-10 text-feedback-done" />
                </div>
                <h2 class="text-2xl font-bold text-feedback-done mb-2">Pagamento Confirmado!</h2>
                <p class="text-grayin-300">Seu pagamento foi recebido com sucesso.</p>
            @elseif ($payment->status === 'PENDING')
                <div class="flex items-center justify-center size-20 rounded-full bg-feedback-progress/20 mx-auto mb-6">
                    <x-lucide-clock class="size-10 text-feedback-progress" />
                </div>
                <h2 class="text-2xl font-bold text-feedback-progress mb-2">Aguardando Pagamento</h2>
                <p class="text-grayin-300">Estamos aguardando a confirmacao do seu pagamento PIX.</p>
            @elseif ($payment->status === 'OVERDUE')
                <div class="flex items-center justify-center size-20 rounded-full bg-feedback-danger/20 mx-auto mb-6">
                    <x-lucide-alert-circle class="size-10 text-feedback-danger" />
                </div>
                <h2 class="text-2xl font-bold text-feedback-danger mb-2">Pagamento Vencido</h2>
                <p class="text-grayin-300">O prazo para pagamento expirou.</p>
            @else
                <div class="flex items-center justify-center size-20 rounded-full bg-grayin-500 mx-auto mb-6">
                    <x-lucide-info class="size-10 text-grayin-300" />
                </div>
                <h2 class="text-2xl font-bold text-grayin-100 mb-2">{{ $payment->status }}</h2>
            @endif
        </x-card>

        {{-- Detalhes do Pedido --}}
        <x-card class="mt-6 bg-white">
            <h3 class="text-lg font-semibold text-grayin-100 mb-4">Detalhes do Pedido</h3>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-grayin-500">
                    <span class="text-grayin-300">Pedido</span>
                    <span class="text-grayin-100">#{{ $payment->order->id }}</span>
                </div>

                @foreach ($payment->order->items as $item)
                    <div class="flex justify-between items-center py-2 border-b border-grayin-500">
                        <span class="text-grayin-300">{{ $item->product->name }}</span>
                        <span class="text-grayin-100">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</span>
                    </div>
                @endforeach

                <div class="flex justify-between items-center py-2 border-b border-grayin-500">
                    <span class="text-grayin-300">Metodo de Pagamento</span>
                    <span class="text-grayin-100">PIX</span>
                </div>

                <div class="flex justify-between items-center py-2">
                    <span class="text-lg font-semibold text-grayin-100">Total</span>
                    <span class="text-xl font-bold text-blue-base">
                        R$ {{ number_format($payment->amount, 2, ',', '.') }}
                    </span>
                </div>
            </div>
        </x-card>

        {{-- Dados do Cliente --}}
        <x-card class="mt-6 bg-white">
            <h3 class="text-lg font-semibold text-grayin-100 mb-4">Dados do Cliente</h3>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-grayin-500">
                    <span class="text-grayin-300">Nome</span>
                    <span class="text-grayin-100">{{ $payment->order->customer->name }}</span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-grayin-500">
                    <span class="text-grayin-300">E-mail</span>
                    <span class="text-grayin-100">{{ $payment->order->customer->email }}</span>
                </div>

                <div class="flex justify-between items-center py-2">
                    <span class="text-grayin-300">CPF</span>
                    <span class="text-grayin-100">{{ $payment->order->customer->cpf_cnpj }}</span>
                </div>
            </div>
        </x-card>

        {{-- Acoes --}}
        <div class="mt-6 flex flex-col sm:flex-row gap-4">
            @if ($payment->status === 'PENDING')
                <a
                    href="{{ route('checkout.payment') }}"
                    class="flex-1 flex items-center justify-center px-6 py-3 bg-blue-base text-white font-semibold rounded-lg hover:bg-blue-dark transition-colors"
                >
                    <x-lucide-qr-code class="size-4 mr-2" />
                    Ver QR Code
                </a>
            @endif

            <a
                href="{{ route('products.index') }}"
                class="flex-1 flex items-center justify-center px-6 py-3 bg-grayin-500 text-grayin-100 font-semibold rounded-lg hover:bg-grayin-400 transition-colors"
            >
                <x-lucide-shopping-bag class="size-4 mr-2" />
                Continuar Comprando
            </a>
        </div>
    </div>
</x-layout.checkout>

@if ($payment->status === 'PENDING')
<script>
    // Polling para verificar status do pagamento
    function checkPaymentStatus() {
        fetch('{{ route('checkout.status.check', $payment) }}')
            .then(response => response.json())
            .then(data => {
                if (data.paid) {
                    window.location.reload();
                }
            });
    }

    // Verificar a cada 5 segundos
    setInterval(checkPaymentStatus, 5000);
</script>
@endif
