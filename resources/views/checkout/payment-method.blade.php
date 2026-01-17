<x-layout.checkout title="Metodo de Pagamento" :step="3">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Selecao de Metodo de Pagamento (Penguin UI Card) --}}
        <div class="md:col-span-2 flex flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-on-surface mb-6 dark:text-on-surface-dark">Escolha como deseja pagar</h3>

                <form action="{{ route('checkout.payment-method.store') }}" method="POST" x-data="{ selected: null }">
                    @csrf

                    <input type="hidden" name="payment_method" x-model="selected">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Opcao PIX --}}
                        <button
                            type="button"
                            @click="selected = 'pix'"
                            :class="{
                                'ring-2 ring-primary dark:ring-primary-dark border-primary dark:border-primary-dark': selected === 'pix',
                                'border-outline dark:border-outline-dark': selected !== 'pix'
                            }"
                            class="relative flex flex-col items-center p-6 rounded-radius border bg-surface transition hover:border-primary dark:bg-surface-dark dark:hover:border-primary-dark cursor-pointer"
                        >
                            <div class="flex items-center justify-center size-16 rounded-full bg-success/10 mb-4 dark:bg-success-dark/10">
                                <x-lucide-qr-code class="size-8 text-success dark:text-success-dark" />
                            </div>
                            <span class="text-lg font-semibold text-on-surface dark:text-on-surface-dark">PIX</span>
                            <span class="text-sm text-on-surface-muted mt-1 dark:text-on-surface-dark-muted">Pagamento instantaneo</span>

                            {{-- Indicador de selecao --}}
                            <div
                                x-show="selected === 'pix'"
                                class="absolute top-3 right-3"
                            >
                                <div class="flex items-center justify-center size-6 rounded-full bg-primary dark:bg-primary-dark">
                                    <x-lucide-check class="size-4 text-on-primary dark:text-on-primary-dark" />
                                </div>
                            </div>
                        </button>

                        {{-- Opcao Cartao de Credito --}}
                        <button
                            type="button"
                            @click="selected = 'credit_card'"
                            :class="{
                                'ring-2 ring-primary dark:ring-primary-dark border-primary dark:border-primary-dark': selected === 'credit_card',
                                'border-outline dark:border-outline-dark': selected !== 'credit_card'
                            }"
                            class="relative flex flex-col items-center p-6 rounded-radius border bg-surface transition hover:border-primary dark:bg-surface-dark dark:hover:border-primary-dark cursor-pointer"
                        >
                            <div class="flex items-center justify-center size-16 rounded-full bg-primary/10 mb-4 dark:bg-primary-dark/10">
                                <x-lucide-credit-card class="size-8 text-primary dark:text-primary-dark" />
                            </div>
                            <span class="text-lg font-semibold text-on-surface dark:text-on-surface-dark">Cartao de Credito</span>
                            <span class="text-sm text-on-surface-muted mt-1 dark:text-on-surface-dark-muted">Pague com seu cartao</span>

                            {{-- Indicador de selecao --}}
                            <div
                                x-show="selected === 'credit_card'"
                                class="absolute top-3 right-3"
                            >
                                <div class="flex items-center justify-center size-6 rounded-full bg-primary dark:bg-primary-dark">
                                    <x-lucide-check class="size-4 text-on-primary dark:text-on-primary-dark" />
                                </div>
                            </div>
                        </button>
                    </div>

                    @error('payment_method')
                        <p class="mt-4 text-sm text-danger dark:text-danger-dark">{{ $message }}</p>
                    @enderror

                    <div class="flex items-center justify-between pt-6 mt-6 border-t border-outline dark:border-outline-dark">
                        {{-- Botao Voltar --}}
                        <a
                            href="{{ route('checkout.customer') }}"
                            class="flex items-center whitespace-nowrap rounded-radius border border-outline bg-surface-alt px-4 py-2 text-sm font-medium tracking-wide text-on-surface transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark dark:focus-visible:outline-primary-dark"
                        >
                            <x-lucide-arrow-left class="size-4 mr-2" />
                            Voltar
                        </a>

                        {{-- Botao Continuar --}}
                        <button
                            type="submit"
                            :disabled="!selected"
                            :class="{ 'opacity-50 cursor-not-allowed': !selected }"
                            class="flex items-center whitespace-nowrap rounded-radius bg-primary border border-primary px-4 py-3 text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark"
                        >
                            Continuar
                            <x-lucide-arrow-right class="size-4 ml-2" />
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Resumo (Penguin UI Card) --}}
        <div class="flex flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
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
