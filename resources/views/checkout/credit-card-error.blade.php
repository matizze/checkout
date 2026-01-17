<x-layout.checkout title="Erro no Pagamento" :step="3">
    <div class="max-w-2xl mx-auto">
        {{-- Erro Card --}}
        <div class="flex flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
            <div class="p-8 text-center">
                {{-- Icone de Erro --}}
                <div class="flex items-center justify-center size-24 rounded-full bg-danger/20 mx-auto mb-6 dark:bg-danger-dark/20">
                    <x-lucide-alert-triangle class="size-12 text-danger dark:text-danger-dark" />
                </div>

                {{-- Titulo --}}
                <h2 class="text-2xl font-bold text-danger mb-2 dark:text-danger-dark">Falha na Autorização</h2>
                <p class="text-on-surface-muted dark:text-on-surface-dark-muted mb-6">O cartão de crédito foi recusado</p>

                {{-- Mensagem de Erro --}}
                <div class="bg-danger/10 dark:bg-danger-dark/10 border border-danger/30 dark:border-danger-dark/30 rounded-radius p-4 mb-6">
                    <p class="text-sm text-danger dark:text-danger-dark">
                        <span class="font-semibold">Motivo:</span> {{ $error }}
                    </p>
                </div>

                {{-- Dicas --}}
                <div class="mb-8 space-y-3 text-left">
                    <p class="font-semibold text-on-surface dark:text-on-surface-dark mb-3">Verifique:</p>
                    <div class="flex items-start space-x-3">
                        <x-lucide-check class="size-5 text-primary shrink-0 mt-0.5 dark:text-primary-dark" />
                        <p class="text-sm text-on-surface-muted dark:text-on-surface-dark-muted">Número do cartão está correto</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <x-lucide-check class="size-5 text-primary shrink-0 mt-0.5 dark:text-primary-dark" />
                        <p class="text-sm text-on-surface-muted dark:text-on-surface-dark-muted">Data de validade não está expirada</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <x-lucide-check class="size-5 text-primary shrink-0 mt-0.5 dark:text-primary-dark" />
                        <p class="text-sm text-on-surface-muted dark:text-on-surface-dark-muted">Código CVV está correto</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <x-lucide-check class="size-5 text-primary shrink-0 mt-0.5 dark:text-primary-dark" />
                        <p class="text-sm text-on-surface-muted dark:text-on-surface-dark-muted">Seu banco não está bloqueando a transação</p>
                    </div>
                </div>

                {{-- Acoes --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    {{-- Tentar com outro cartao --}}
                    <a
                        href="{{ route('checkout.credit-card') }}"
                        class="flex items-center justify-center whitespace-nowrap rounded-radius bg-primary border border-primary px-6 py-3 text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark"
                    >
                        <x-lucide-credit-card class="size-4 mr-2" />
                        Tentar Novamente
                    </a>

                    {{-- Voltar ao metodo de pagamento --}}
                    <a
                        href="{{ route('checkout.payment-method') }}"
                        class="flex items-center justify-center whitespace-nowrap rounded-radius border border-outline bg-surface-alt px-6 py-3 text-sm font-medium tracking-wide text-on-surface transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark dark:focus-visible:outline-primary-dark"
                    >
                        <x-lucide-arrow-left class="size-4 mr-2" />
                        Escolher outro metodo
                    </a>
                </div>
            </div>
        </div>

        {{-- Resumo do Pedido --}}
        <div class="mt-6 flex flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-on-surface mb-4 dark:text-on-surface-dark">Seu Pedido</h3>

                @if ($product->image)
                    <img
                        src="{{ $product->image_url }}"
                        alt="{{ $product->name }}"
                        class="w-full h-32 object-cover rounded-radius mb-4"
                    >
                @endif

                <p class="text-on-surface font-medium dark:text-on-surface-dark">{{ $product->name }}</p>

                <div class="mt-4 pt-4 border-t border-outline dark:border-outline-dark">
                    <div class="flex justify-between items-center">
                        <span class="text-on-surface-muted dark:text-on-surface-dark-muted">Total</span>
                        <span class="text-xl font-bold text-primary dark:text-primary-dark">
                            R$ {{ number_format($product->price, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.checkout>
