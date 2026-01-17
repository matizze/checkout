<x-layout.checkout title="Dados do Cliente" :step="2">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Formulario (Penguin UI Card) --}}
        <div class="md:col-span-2 flex flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-on-surface mb-6 dark:text-on-surface-dark">Seus Dados</h3>

                <form action="{{ route('checkout.customer.store') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Nome --}}
                    <div class="flex w-full flex-col gap-1">
                        <label for="name" class="w-fit pl-0.5 text-sm text-on-surface dark:text-on-surface-dark">
                            Nome completo
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $customerData['name'] ?? '') }}"
                            placeholder="Seu nome completo"
                            class="w-full rounded-radius border border-outline bg-surface-alt px-4 py-3 text-sm text-on-surface placeholder:text-on-surface-muted focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark dark:placeholder:text-on-surface-dark-muted dark:focus-visible:outline-primary-dark"
                            required
                        >
                        @error('name')
                            <small class="pl-0.5 text-sm text-danger dark:text-danger-dark">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="flex w-full flex-col gap-1">
                        <label for="email" class="w-fit pl-0.5 text-sm text-on-surface dark:text-on-surface-dark">
                            E-mail
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $customerData['email'] ?? '') }}"
                            placeholder="seu@email.com"
                            class="w-full rounded-radius border border-outline bg-surface-alt px-4 py-3 text-sm text-on-surface placeholder:text-on-surface-muted focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark dark:placeholder:text-on-surface-dark-muted dark:focus-visible:outline-primary-dark"
                            required
                        >
                        @error('email')
                            <small class="pl-0.5 text-sm text-danger dark:text-danger-dark">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- CPF --}}
                    <div class="flex w-full flex-col gap-1">
                        <label for="cpf_cnpj" class="w-fit pl-0.5 text-sm text-on-surface dark:text-on-surface-dark">
                            CPF
                        </label>
                        <input
                            type="text"
                            id="cpf_cnpj"
                            name="cpf_cnpj"
                            value="{{ old('cpf_cnpj', $customerData['cpf_cnpj'] ?? '') }}"
                            placeholder="000.000.000-00"
                            maxlength="14"
                            x-data
                            x-mask="999.999.999-99"
                            class="w-full rounded-radius border border-outline bg-surface-alt px-4 py-3 text-sm text-on-surface placeholder:text-on-surface-muted focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark dark:placeholder:text-on-surface-dark-muted dark:focus-visible:outline-primary-dark"
                            required
                        >
                        @error('cpf_cnpj')
                            <small class="pl-0.5 text-sm text-danger dark:text-danger-dark">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Telefone --}}
                    <div class="flex w-full flex-col gap-1">
                        <label for="phone" class="w-fit pl-0.5 text-sm text-on-surface dark:text-on-surface-dark">
                            Telefone (opcional)
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $customerData['phone'] ?? '') }}"
                            placeholder="(00) 00000-0000"
                            x-data
                            x-mask="(99) 99999-9999"
                            class="w-full rounded-radius border border-outline bg-surface-alt px-4 py-3 text-sm text-on-surface placeholder:text-on-surface-muted focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark dark:placeholder:text-on-surface-dark-muted dark:focus-visible:outline-primary-dark"
                        >
                        @error('phone')
                            <small class="pl-0.5 text-sm text-danger dark:text-danger-dark">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        {{-- Botao Voltar (Penguin UI Secondary) --}}
                        <a
                            href="{{ route('checkout.start', $product) }}"
                            class="flex items-center whitespace-nowrap rounded-radius border border-outline bg-surface-alt px-4 py-2 text-sm font-medium tracking-wide text-on-surface transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark dark:focus-visible:outline-primary-dark"
                        >
                            <x-lucide-arrow-left class="size-4 mr-2" />
                            Voltar
                        </a>

                        {{-- Botao Continuar (Penguin UI Primary) --}}
                        <button
                            type="submit"
                            class="flex items-center whitespace-nowrap rounded-radius bg-primary border border-primary px-4 py-3 text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark"
                        >
                            Continuar para pagamento
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
                            {{ $product->price }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.checkout>
