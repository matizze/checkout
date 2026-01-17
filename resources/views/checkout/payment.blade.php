<x-layout.checkout title="Pagamento PIX" :step="3">
    <div
        class="grid grid-cols-1 md:grid-cols-2 gap-6"
        x-data="polling('{{ route('checkout.status.check', $payment) }}', 5000, {
            stopWhen: (data) => data.paid,
            onComplete: () => window.location.href = '{{ route('checkout.status', $payment) }}'
        })"
    >
        {{-- QR Code PIX (Penguin UI Card) --}}
        <div class="flex flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
            <div class="p-6 text-center">
                <div class="flex items-center justify-center mb-4">
                    <x-lucide-qr-code class="size-6 text-primary mr-2 dark:text-primary-dark" />
                    <h3 class="text-lg font-semibold text-on-surface dark:text-on-surface-dark">Pague com PIX</h3>
                </div>

                <p class="text-sm text-on-surface-muted mb-6 dark:text-on-surface-dark-muted">
                    Escaneie o QR Code abaixo com o app do seu banco
                </p>

                @if ($payment->pix_qrcode_base64)
                    <div class="bg-surface p-4 rounded-radius inline-block mb-6 dark:bg-surface-dark">
                        <img
                            src="data:image/png;base64,{{ $payment->pix_qrcode_base64 }}"
                            alt="QR Code PIX"
                            class="w-48 h-48"
                        >
                    </div>
                @endif

                 <div class="space-y-4" x-data="{ copied: false }">
                    <p class="text-sm text-on-surface-muted dark:text-on-surface-dark-muted">Ou copie o codigo PIX:</p>

                    <div class="relative">
                        <input
                            type="text"
                            x-ref="pixCode"
                            value="{{ $payment->pix_payload }}"
                            readonly
                            class="w-full rounded-radius border border-outline bg-surface-alt px-4 py-3 text-sm text-on-surface pr-24 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark"
                        >
                          <button
                              type="button"
                              @click="$clipboard($refs.pixCode.value).then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                              class="absolute right-2 top-1/2 -translate-y-1/2 whitespace-nowrap rounded-radius bg-primary border border-primary px-3 py-1.5 text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark"
                              :class="{ 'bg-green-500/10 border-green-500/10': copied }"
                          >
                             <span x-text="copied ? 'Copiado!' : 'Copiar'" :class="{ 'text-green-500': copied }"></span>
                         </button>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-surface rounded-radius dark:bg-surface-dark">
                    <div class="flex items-center justify-center text-sm text-on-surface-muted dark:text-on-surface-dark-muted">
                        <x-lucide-clock class="size-4 mr-2" />
                        <span>Vencimento: {{ $payment->due_date->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Resumo e Status (Penguin UI Card) --}}
        <div class="flex flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-on-surface mb-4 dark:text-on-surface-dark">Detalhes do Pagamento</h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-outline dark:border-outline-dark">
                        <span class="text-on-surface-muted dark:text-on-surface-dark-muted">Produto</span>
                        <span class="text-on-surface dark:text-on-surface-dark">{{ $product->name }}</span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-outline dark:border-outline-dark">
                        <span class="text-on-surface-muted dark:text-on-surface-dark-muted">Metodo</span>
                        <span class="text-on-surface flex items-center dark:text-on-surface-dark">
                            <x-lucide-landmark class="size-4 mr-2 text-primary dark:text-primary-dark" />
                            PIX
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-outline dark:border-outline-dark">
                        <span class="text-on-surface-muted dark:text-on-surface-dark-muted">Status</span>
                        <span id="payment-status" @class([
                            'px-3 py-1 rounded-radius text-sm font-medium',
                            'bg-warning/20 text-warning dark:text-warning-dark' => $payment->status === 'PENDING',
                            'bg-success/20 text-success dark:text-success-dark' => in_array($payment->status, ['RECEIVED', 'CONFIRMED']),
                            'bg-danger/20 text-danger dark:text-danger-dark' => in_array($payment->status, ['OVERDUE', 'REFUNDED']),
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
                        <span class="text-lg font-semibold text-on-surface dark:text-on-surface-dark">Total</span>
                        <span class="text-2xl font-bold text-primary dark:text-primary-dark">
                            {{ $payment->amount }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <a
                        href="{{ route('checkout.status', $payment) }}"
                        class="flex items-center justify-center w-full whitespace-nowrap rounded-radius bg-primary border border-primary px-4 py-3 text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark"
                    >
                        <x-lucide-refresh-cw class="size-4 mr-2" />
                        Verificar Status
                    </a>

                    <p class="text-xs text-on-surface-muted text-center dark:text-on-surface-dark-muted">
                        A pagina sera atualizada automaticamente quando o pagamento for confirmado
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Instrucoes (Penguin UI Card) --}}
    <div class="flex flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-on-surface mb-4 dark:text-on-surface-dark">Como pagar com PIX</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-start space-x-3">
                    <div class="flex items-center justify-center size-8 rounded-full bg-primary text-on-primary text-sm font-semibold shrink-0 dark:bg-primary-dark dark:text-on-primary-dark">
                        1
                    </div>
                    <div>
                        <p class="font-medium text-on-surface dark:text-on-surface-dark">Abra o app do seu banco</p>
                        <p class="text-sm text-on-surface-muted dark:text-on-surface-dark-muted">Acesse a area PIX do aplicativo</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="flex items-center justify-center size-8 rounded-full bg-primary text-on-primary text-sm font-semibold shrink-0 dark:bg-primary-dark dark:text-on-primary-dark">
                        2
                    </div>
                    <div>
                        <p class="font-medium text-on-surface dark:text-on-surface-dark">Escaneie o QR Code</p>
                        <p class="text-sm text-on-surface-muted dark:text-on-surface-dark-muted">Ou copie e cole o codigo PIX</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="flex items-center justify-center size-8 rounded-full bg-primary text-on-primary text-sm font-semibold shrink-0 dark:bg-primary-dark dark:text-on-primary-dark">
                        3
                    </div>
                    <div>
                        <p class="font-medium text-on-surface dark:text-on-surface-dark">Confirme o pagamento</p>
                        <p class="text-sm text-on-surface-muted dark:text-on-surface-dark-muted">O status sera atualizado em segundos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.checkout>
