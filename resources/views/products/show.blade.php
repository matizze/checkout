<x-layout.dashboard class="space-y-6" title="Produto: {{ $product->name }}">
    <header class="flex flex-wrap items-start justify-between gap-4">
        <div class="space-y-3">
            <h1 class="text-2xl font-bold text-blue-dark">
                {{ $product->name }}
            </h1>

            <div class="flex flex-wrap items-center gap-3 text-sm text-grayin-300">
                <a href="{{ route('products.index') }}" class="font-semibold hover:text-blue-base">
                    ← Voltar
                </a>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
             <button
                 x-data="{
                     checkoutUrl: '{{ route('checkout.start', $product) }}',
                     copied: false
                 }"
                 @click="$clipboard(checkoutUrl).then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                 class="flex items-center justify-center cursor-pointer size-9 bg-grayin-500 rounded"
                 :class="{ 'bg-green-500/10': copied }"
                 :title="copied ? 'Copiado!' : 'Copiar link checkout'"
             >
                <x-lucide-check class="size-4 text-green-500" x-show="copied" />
                <x-lucide-link class="size-4" x-show="!copied" />
            </button>

            <a
                href="{{ route('products.edit', $product) }}"
                class="flex items-center justify-center cursor-pointer size-9 bg-grayin-500 rounded"
                title="Editar produto"
            >
                <x-lucide-pen-line class="size-4" />
            </a>

            <div x-data="{
                confirmDelete() {
                    return confirm('Tem certeza que deseja excluir este produto? Essa acao nao pode ser desfeita.');
                }
            }">
                <form
                    method="POST"
                    action="{{ route('products.destroy', $product) }}"
                    @submit="if (!confirmDelete()) $event.preventDefault()"
                >
                    @method('DELETE')
                    @csrf
                    <button
                        type="submit"
                        class="flex items-center justify-center cursor-pointer size-9 bg-red-100 text-red-600 rounded"
                        title="Excluir produto"
                    >
                        <x-lucide-trash class="size-4" />
                    </button>
                </form>
            </div>
        </div>
    </header>

    @if (session('status'))
        <x-card class="border border-feedback-done/30 bg-feedback-done/10">
            <p class="text-sm text-grayin-300">{{ session('status') }}</p>
        </x-card>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-card class="md:col-span-1">
            @if ($product->image)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded-lg">
            @else
                <div class="w-full h-64 bg-grayin-500 rounded-lg flex items-center justify-center">
                    <x-lucide-image class="size-16 text-grayin-400" />
                </div>
            @endif
        </x-card>

        <x-card class="md:col-span-2">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-grayin-400">Nome</p>
                    <p class="text-lg font-semibold text-grayin-200">{{ $product->name }}</p>
                </div>

                <div class="space-y-1">
                    <p class="text-sm font-medium text-grayin-400">Preço</p>
                     <p class="text-2xl font-bold text-blue-base">{{ $product->formatted_price }}</p>
                </div>

                <div class="space-y-1 sm:col-span-2">
                    <p class="text-sm font-medium text-grayin-400">Descrição</p>
                    @if ($product->description)
                        <p class="text-base text-grayin-300">{{ $product->description }}</p>
                    @else
                        <p class="text-base text-grayin-400 italic">Sem descrição</p>
                    @endif
                </div>

                <div class="space-y-1">
                    <p class="text-sm font-medium text-grayin-400">Criado em</p>
                    <p class="text-base text-grayin-300">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div class="space-y-1">
                    <p class="text-sm font-medium text-grayin-400">Link checkout</p>
                    <div class="flex items-center gap-2">
                        <code class="text-xs bg-grayin-600 px-2 py-1 rounded text-grayin-300 truncate">{{ route('checkout.start', $product) }}</code>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</x-layout.dashboard>
