<x-layout.checkout title="Pagamento com Cartao" :step="3">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Formulario de Cartao (Penguin UI Card) --}}
        <div class="md:col-span-2 flex flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <x-lucide-credit-card class="size-6 text-primary mr-2 dark:text-primary-dark" />
                    <h3 class="text-lg font-semibold text-on-surface dark:text-on-surface-dark">Dados do Cartao</h3>
                </div>

                <form
                    action="{{ route('checkout.credit-card.process') }}"
                    method="POST"
                    x-data="{
                        cardNumber: '',
                        holderName: '',
                        isSubmitting: false,
                        get cardBrand() {
                            const number = this.cardNumber.replace(/\s/g, '');
                            if (/^4/.test(number)) return 'visa';
                            if (/^5[1-5]/.test(number) || /^2[2-7]/.test(number)) return 'mastercard';
                            if (/^3[47]/.test(number)) return 'amex';
                            if (/^(636368|438935|504175|451416|636297|5067|4576|4011|506699)/.test(number)) return 'elo';
                            return null;
                        }
                    }"
                    @submit="isSubmitting = true"
                >
                    @csrf

                    <div class="space-y-4">
                        {{-- Numero do Cartao --}}
                        <div class="flex w-full flex-col gap-1">
                            <label for="card_number" class="w-fit pl-0.5 text-sm text-on-surface dark:text-on-surface-dark">
                                Numero do Cartao
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="card_number"
                                    name="card_number"
                                    x-model="cardNumber"
                                    x-mask="9999 9999 9999 9999"
                                    placeholder="0000 0000 0000 0000"
                                    autocomplete="cc-number"
                                    class="w-full rounded-radius border border-outline bg-surface-alt px-4 py-3 text-sm text-on-surface placeholder:text-on-surface-muted focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark dark:placeholder:text-on-surface-dark-muted dark:focus-visible:outline-primary-dark pr-12"
                                    required
                                >
                                <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                    <template x-if="cardBrand === 'visa'">
                                        <span class="text-xs font-bold text-blue-600">VISA</span>
                                    </template>
                                    <template x-if="cardBrand === 'mastercard'">
                                        <span class="text-xs font-bold text-orange-600">MC</span>
                                    </template>
                                    <template x-if="cardBrand === 'amex'">
                                        <span class="text-xs font-bold text-blue-800">AMEX</span>
                                    </template>
                                    <template x-if="cardBrand === 'elo'">
                                        <span class="text-xs font-bold text-yellow-600">ELO</span>
                                    </template>
                                </div>
                            </div>
                            @error('card_number')
                                <small class="pl-0.5 text-sm text-danger dark:text-danger-dark">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Nome do Titular --}}
                        <div class="flex w-full flex-col gap-1">
                            <label for="card_holder_name" class="w-fit pl-0.5 text-sm text-on-surface dark:text-on-surface-dark">
                                Nome no Cartao
                            </label>
                            <input
                                type="text"
                                id="card_holder_name"
                                name="card_holder_name"
                                x-model="holderName"
                                @input="holderName = holderName.toUpperCase()"
                                placeholder="NOME COMO ESTA NO CARTAO"
                                autocomplete="cc-name"
                                class="w-full rounded-radius border border-outline bg-surface-alt px-4 py-3 text-sm text-on-surface placeholder:text-on-surface-muted focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark dark:placeholder:text-on-surface-dark-muted dark:focus-visible:outline-primary-dark uppercase"
                                required
                            >
                            @error('card_holder_name')
                                <small class="pl-0.5 text-sm text-danger dark:text-danger-dark">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Validade e CVV --}}
                        <div class="grid grid-cols-3 gap-4">
                            {{-- Mes --}}
                            <div class="flex w-full flex-col gap-1">
                                <label for="card_expiry_month" class="w-fit pl-0.5 text-sm text-on-surface dark:text-on-surface-dark">
                                    Mes
                                </label>
                                <select
                                    id="card_expiry_month"
                                    name="card_expiry_month"
                                    autocomplete="cc-exp-month"
                                    class="w-full rounded-radius border border-outline bg-surface-alt px-4 py-3 text-sm text-on-surface focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark dark:focus-visible:outline-primary-dark"
                                    required
                                >
                                    <option value="">Mes</option>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                                @error('card_expiry_month')
                                    <small class="pl-0.5 text-sm text-danger dark:text-danger-dark">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Ano --}}
                            <div class="flex w-full flex-col gap-1">
                                <label for="card_expiry_year" class="w-fit pl-0.5 text-sm text-on-surface dark:text-on-surface-dark">
                                    Ano
                                </label>
                                <select
                                    id="card_expiry_year"
                                    name="card_expiry_year"
                                    autocomplete="cc-exp-year"
                                    class="w-full rounded-radius border border-outline bg-surface-alt px-4 py-3 text-sm text-on-surface focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark dark:focus-visible:outline-primary-dark"
                                    required
                                >
                                    <option value="">Ano</option>
                                    @for ($y = date('Y'); $y <= date('Y') + 15; $y++)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                                @error('card_expiry_year')
                                    <small class="pl-0.5 text-sm text-danger dark:text-danger-dark">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- CVV --}}
                            <div class="flex w-full flex-col gap-1">
                                <label for="card_cvv" class="w-fit pl-0.5 text-sm text-on-surface dark:text-on-surface-dark">
                                    CVV
                                </label>
                                <input
                                    type="text"
                                    id="card_cvv"
                                    name="card_cvv"
                                    x-mask="9999"
                                    placeholder="123"
                                    maxlength="4"
                                    autocomplete="cc-csc"
                                    class="w-full rounded-radius border border-outline bg-surface-alt px-4 py-3 text-sm text-on-surface placeholder:text-on-surface-muted focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark dark:placeholder:text-on-surface-dark-muted dark:focus-visible:outline-primary-dark"
                                    required
                                >
                                @error('card_cvv')
                                    <small class="pl-0.5 text-sm text-danger dark:text-danger-dark">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @if (session('error'))
                        <div class="mb-4 p-4 rounded-radius bg-danger/20 border-2 border-danger text-danger dark:bg-danger-dark/20 dark:border-danger-dark dark:text-danger-dark">
                            <div class="flex items-start">
                                <x-lucide-alert-circle class="size-6 mr-3 shrink-0 mt-0.5" />
                                <div>
                                    <p class="font-semibold text-sm mb-1">Erro ao processar pagamento</p>
                                    <p class="text-sm">{{ session('error') }}</p>
                                    <p class="text-xs mt-2 opacity-75">Verifique os dados do cartão e tente novamente.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center justify-between pt-6 mt-6 border-t border-outline dark:border-outline-dark">
                        {{-- Botao Voltar --}}
                        <a
                            href="{{ route('checkout.payment-method') }}"
                            class="flex items-center whitespace-nowrap rounded-radius border border-outline bg-surface-alt px-4 py-2 text-sm font-medium tracking-wide text-on-surface transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark dark:focus-visible:outline-primary-dark"
                        >
                            <x-lucide-arrow-left class="size-4 mr-2" />
                            Voltar
                        </a>

                        {{-- Botao Pagar --}}
                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="flex items-center whitespace-nowrap rounded-radius bg-primary border border-primary px-4 py-3 text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark"
                        >
                            <span x-show="!isSubmitting" class="flex items-center">
                                <x-lucide-lock class="size-4 mr-2" />
                                Pagar {{ $product->price }}
                            </span>
                            <span x-show="isSubmitting" class="flex items-center">
                                <x-lucide-loader-2 class="size-4 mr-2 animate-spin" />
                                Processando...
                            </span>
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
                        <span class="text-on-surface-muted dark:text-on-surface-dark-muted">Subtotal</span>
                        <span class="text-on-surface dark:text-on-surface-dark">
                            {{ $product->price }}
                        </span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-outline dark:border-outline-dark">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-on-surface dark:text-on-surface-dark">Total</span>
                        <span class="text-xl font-bold text-primary dark:text-primary-dark">
                            {{ $product->price }}
                        </span>
                    </div>
                </div>

                {{-- Seguranca --}}
                <div class="mt-6 pt-4 border-t border-outline dark:border-outline-dark">
                    <div class="flex items-center text-sm text-on-surface-muted dark:text-on-surface-dark-muted">
                        <x-lucide-shield-check class="size-4 mr-2 text-success dark:text-success-dark" />
                        Pagamento 100% seguro
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.checkout>
