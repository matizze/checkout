<x-layout.checkout title="Status do Pagamento" :step="3">
    <div
        class="max-w-2xl mx-auto"
        @if($payment->status === 'PENDING')
        x-data="polling('{{ route('checkout.status.check', $payment) }}', 5000, {
            stopWhen: (data) => data.paid,
            onComplete: () => window.location.reload()
        })"
        @endif
    >
        {{-- Status Card (Penguin UI Card) --}}
        <div class="flex flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
            <div class="p-6 text-center">
                @if (in_array($payment->status, ['RECEIVED', 'CONFIRMED']))
                    <div class="flex items-center justify-center size-20 rounded-full bg-success/20 mx-auto mb-6 dark:bg-success-dark/20">
                        <x-lucide-check class="size-10 text-success dark:text-success-dark" />
                    </div>
                    <h2 class="text-2xl font-bold text-success mb-2 dark:text-success-dark">Pagamento Confirmado!</h2>
                    <p class="text-on-surface-muted dark:text-on-surface-dark-muted">Seu pagamento foi recebido com sucesso.</p>
                @elseif ($payment->status === 'PENDING')
                    <div class="flex items-center justify-center size-20 rounded-full bg-warning/20 mx-auto mb-6 dark:bg-warning-dark/20">
                        <x-lucide-clock class="size-10 text-warning dark:text-warning-dark" />
                    </div>
                    <h2 class="text-2xl font-bold text-warning mb-2 dark:text-warning-dark">Aguardando Pagamento</h2>
                    @if ($payment->billing_type === 'CREDIT_CARD')
                        <p class="text-on-surface-muted dark:text-on-surface-dark-muted">Seu pagamento com cartao esta sendo processado.</p>
                    @else
                        <p class="text-on-surface-muted dark:text-on-surface-dark-muted">Estamos aguardando a confirmacao do seu pagamento PIX.</p>
                    @endif
                @elseif ($payment->status === 'OVERDUE')
                    <div class="flex items-center justify-center size-20 rounded-full bg-danger/20 mx-auto mb-6 dark:bg-danger-dark/20">
                        <x-lucide-alert-circle class="size-10 text-danger dark:text-danger-dark" />
                    </div>
                    <h2 class="text-2xl font-bold text-danger mb-2 dark:text-danger-dark">Pagamento Vencido</h2>
                    <p class="text-on-surface-muted dark:text-on-surface-dark-muted">O prazo para pagamento expirou.</p>
                @else
                    <div class="flex items-center justify-center size-20 rounded-full bg-surface mx-auto mb-6 dark:bg-surface-dark">
                        <x-lucide-info class="size-10 text-on-surface-muted dark:text-on-surface-dark-muted" />
                    </div>
                    <h2 class="text-2xl font-bold text-on-surface mb-2 dark:text-on-surface-dark">{{ $payment->status }}</h2>
                @endif
            </div>
        </div>

        {{-- Detalhes do Pedido (Penguin UI Card) --}}
        <div class="mt-6 flex flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-on-surface mb-4 dark:text-on-surface-dark">Detalhes do Pedido</h3>

                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-outline dark:border-outline-dark">
                        <span class="text-on-surface-muted dark:text-on-surface-dark-muted">Pedido</span>
                        <span class="text-on-surface dark:text-on-surface-dark">#{{ $payment->order->id }}</span>
                    </div>

                    @foreach ($payment->order->items as $item)
                        <div class="flex justify-between items-center py-2 border-b border-outline dark:border-outline-dark">
                            <span class="text-on-surface-muted dark:text-on-surface-dark-muted">{{ $item->product->name }}</span>
                            <span class="text-on-surface dark:text-on-surface-dark">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</span>
                        </div>
                    @endforeach

                    <div class="flex justify-between items-center py-2 border-b border-outline dark:border-outline-dark">
                        <span class="text-on-surface-muted dark:text-on-surface-dark-muted">Metodo de Pagamento</span>
                        <span class="text-on-surface flex items-center dark:text-on-surface-dark">
                            @if ($payment->billing_type === 'CREDIT_CARD')
                                <x-lucide-credit-card class="size-4 mr-2 text-primary dark:text-primary-dark" />
                                Cartao de Credito
                            @else
                                <x-lucide-landmark class="size-4 mr-2 text-success dark:text-success-dark" />
                                PIX
                            @endif
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2">
                        <span class="text-lg font-semibold text-on-surface dark:text-on-surface-dark">Total</span>
                        <span class="text-xl font-bold text-primary dark:text-primary-dark">
                            R$ {{ number_format($payment->amount, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dados do Cliente (Penguin UI Card) --}}
        <div class="mt-6 flex flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-on-surface mb-4 dark:text-on-surface-dark">Dados do Cliente</h3>

                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-outline dark:border-outline-dark">
                        <span class="text-on-surface-muted dark:text-on-surface-dark-muted">Nome</span>
                        <span class="text-on-surface dark:text-on-surface-dark">{{ $payment->order->customer->name }}</span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-outline dark:border-outline-dark">
                        <span class="text-on-surface-muted dark:text-on-surface-dark-muted">E-mail</span>
                        <span class="text-on-surface dark:text-on-surface-dark">{{ $payment->order->customer->email }}</span>
                    </div>

                    <div class="flex justify-between items-center py-2">
                        <span class="text-on-surface-muted dark:text-on-surface-dark-muted">CPF</span>
                        <span class="text-on-surface dark:text-on-surface-dark">{{ $payment->order->customer->cpf_cnpj }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Acoes --}}
        <div class="mt-6 flex flex-col sm:flex-row gap-4">
            @if ($payment->status === 'PENDING' && $payment->billing_type === 'PIX')
                <a
                    href="{{ route('checkout.payment') }}"
                    class="flex-1 flex items-center justify-center whitespace-nowrap rounded-radius bg-primary border border-primary px-4 py-3 text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark"
                >
                    <x-lucide-qr-code class="size-4 mr-2" />
                    Ver QR Code
                </a>
            @endif

            <a
                href="{{ route('products.index') }}"
                class="flex-1 flex items-center justify-center whitespace-nowrap rounded-radius border border-outline bg-surface-alt px-4 py-3 text-sm font-medium tracking-wide text-on-surface transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark dark:focus-visible:outline-primary-dark"
            >
                <x-lucide-shopping-bag class="size-4 mr-2" />
                Continuar Comprando
            </a>
        </div>
    </div>
</x-layout.checkout>
