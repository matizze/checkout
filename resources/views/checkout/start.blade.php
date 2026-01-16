<x-layout.checkout title="Checkout" :step="1">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Produto --}}
        <x-card class="bg-white">
            @if ($product->image)
                <img
                    src="{{ Storage::url($product->image) }}"
                    alt="{{ $product->name }}"
                    class="w-full h-48 object-cover rounded-lg mb-4"
                >
            @else
                <div class="w-full h-48 bg-grayin-500 rounded-lg flex items-center justify-center mb-4">
                    <x-lucide-image class="size-16 text-grayin-300" />
                </div>
            @endif

            <h2 class="text-xl font-bold text-grayin-100">{{ $product->name }}</h2>

            @if ($product->description)
                <p class="text-sm text-grayin-300 mt-2">{{ $product->description }}</p>
            @endif
        </x-card>

        {{-- Resumo do Pedido --}}
        <x-card class="bg-white">
            <h3 class="text-lg font-semibold text-grayin-100 mb-4">Resumo do Pedido</h3>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-grayin-500">
                    <span class="text-grayin-300">Produto</span>
                    <span class="text-grayin-100">{{ $product->name }}</span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-grayin-500">
                    <span class="text-grayin-300">Quantidade</span>
                    <span class="text-grayin-100">1</span>
                </div>

                <div class="flex justify-between items-center py-2">
                    <span class="text-lg font-semibold text-grayin-100">Total</span>
                    <span class="text-2xl font-bold text-blue-base">
                        R$ {{ number_format($product->price, 2, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="mt-6">
                <a
                    href="{{ route('checkout.customer') }}"
                    class="flex items-center justify-center w-full px-6 py-3 bg-blue-base text-white font-semibold rounded-lg hover:bg-blue-dark transition-colors"
                >
                    Continuar para dados
                    <x-lucide-arrow-right class="size-4 ml-2" />
                </a>
            </div>
        </x-card>
    </div>
</x-layout.checkout>
