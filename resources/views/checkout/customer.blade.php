<x-layout.checkout title="Dados do Cliente" :step="2">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Formulario --}}
        <x-card class="md:col-span-2 bg-white">
            <h3 class="text-lg font-semibold text-grayin-100 mb-6">Seus Dados</h3>

            <form action="{{ route('checkout.customer.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="space-y-2">
                    <label for="name" class="text-sm font-medium text-grayin-200">
                        Nome completo
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $customerData['name'] ?? '') }}"
                        placeholder="Seu nome completo"
                        class="w-full rounded-md bg-grayin-600 border border-grayin-500 px-4 py-3 text-grayin-100 placeholder:text-grayin-400 focus:outline-none focus:ring-2 focus:ring-blue-base"
                        required
                    >
                    @error('name')
                        <p class="text-sm text-feedback-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium text-grayin-200">
                        E-mail
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $customerData['email'] ?? '') }}"
                        placeholder="seu@email.com"
                        class="w-full rounded-md bg-grayin-600 border border-grayin-500 px-4 py-3 text-grayin-100 placeholder:text-grayin-400 focus:outline-none focus:ring-2 focus:ring-blue-base"
                        required
                    >
                    @error('email')
                        <p class="text-sm text-feedback-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="cpf_cnpj" class="text-sm font-medium text-grayin-200">
                        CPF
                    </label>
                    <input
                        type="text"
                        id="cpf_cnpj"
                        name="cpf_cnpj"
                        value="{{ old('cpf_cnpj', $customerData['cpf_cnpj'] ?? '') }}"
                        placeholder="000.000.000-00"
                        maxlength="14"
                        class="w-full rounded-md bg-grayin-600 border border-grayin-500 px-4 py-3 text-grayin-100 placeholder:text-grayin-400 focus:outline-none focus:ring-2 focus:ring-blue-base"
                        required
                    >
                    @error('cpf_cnpj')
                        <p class="text-sm text-feedback-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="phone" class="text-sm font-medium text-grayin-200">
                        Telefone (opcional)
                    </label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', $customerData['phone'] ?? '') }}"
                        placeholder="(00) 00000-0000"
                        class="w-full rounded-md bg-grayin-600 border border-grayin-500 px-4 py-3 text-grayin-100 placeholder:text-grayin-400 focus:outline-none focus:ring-2 focus:ring-blue-base"
                    >
                    @error('phone')
                        <p class="text-sm text-feedback-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-4">
                    <a
                        href="{{ route('checkout.start', $product) }}"
                        class="flex items-center text-grayin-300 hover:text-blue-base transition-colors"
                    >
                        <x-lucide-arrow-left class="size-4 mr-2" />
                        Voltar
                    </a>

                    <button
                        type="submit"
                        class="flex items-center px-6 py-3 bg-blue-base text-white font-semibold rounded-lg hover:bg-blue-dark transition-colors"
                    >
                        Continuar para pagamento
                        <x-lucide-arrow-right class="size-4 ml-2" />
                    </button>
                </div>
            </form>
        </x-card>

        {{-- Resumo --}}
        <x-card class="bg-white">
            <h3 class="text-lg font-semibold text-grayin-100 mb-4">Seu Pedido</h3>

            @if ($product->image)
                <img
                    src="{{ Storage::url($product->image) }}"
                    alt="{{ $product->name }}"
                    class="w-full h-32 object-cover rounded-lg mb-4"
                >
            @endif

            <p class="text-grayin-100 font-medium">{{ $product->name }}</p>

            <div class="mt-4 pt-4 border-t border-grayin-500">
                <div class="flex justify-between items-center">
                    <span class="text-grayin-300">Total</span>
                    <span class="text-xl font-bold text-blue-base">
                        R$ {{ number_format($product->price, 2, ',', '.') }}
                    </span>
                </div>
            </div>
        </x-card>
    </div>
</x-layout.checkout>

@push('scripts')
<script>
    // Mascara CPF
    document.getElementById('cpf_cnpj').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        }
        e.target.value = value;
    });

    // Mascara Telefone
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
        }
        e.target.value = value;
    });
</script>
@endpush
