<x-layout.checkout title="Checkout" :step="1">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Produto (Penguin UI Card) --}}
        <div class="flex flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
            @if ($product->image)
                <img
                    src="{{ $product->image_url }}"
                    alt="{{ $product->name }}"
                    class="w-full h-48 object-cover"
                >
            @else
                <div class="w-full h-48 bg-surface flex items-center justify-center dark:bg-surface-dark">
                    <x-lucide-image class="size-16 text-on-surface-muted dark:text-on-surface-dark-muted" />
                </div>
            @endif

            <div class="p-6">
                <h2 class="text-xl font-bold text-on-surface dark:text-on-surface-dark">{{ $product->name }}</h2>

                @if ($product->description)
                    <p class="text-sm text-on-surface-muted mt-2 dark:text-on-surface-dark-muted">{{ $product->description }}</p>
                @endif
            </div>
        </div>

        {{-- Resumo do Pedido (Penguin UI Card) --}}
        <div class="flex flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-on-surface mb-4 dark:text-on-surface-dark">Resumo do Pedido</h3>

                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-outline dark:border-outline-dark">
                        <span class="text-on-surface-muted dark:text-on-surface-dark-muted">Produto</span>
                        <span class="text-on-surface dark:text-on-surface-dark">{{ $product->name }}</span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-outline dark:border-outline-dark">
                        <span class="text-on-surface-muted dark:text-on-surface-dark-muted">Quantidade</span>
                        <span class="text-on-surface dark:text-on-surface-dark">1</span>
                    </div>

                    <div class="flex justify-between items-center py-2">
                        <span class="text-lg font-semibold text-on-surface dark:text-on-surface-dark">Total</span>
                        <span class="text-2xl font-bold text-primary dark:text-primary-dark">
                            {{ $product->price }}
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <a
                        href="{{ route('checkout.customer') }}"
                        class="flex items-center justify-center w-full whitespace-nowrap rounded-radius bg-primary border border-primary px-4 py-3 text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark"
                    >
                        Continuar para dados
                        <x-lucide-arrow-right class="size-4 ml-2" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout.checkout>
