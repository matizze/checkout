<x-layout.dashboard class="space-y-6" title="Produtos">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-bold text-blue-dark">
            Produtos
        </h1>

        <x-button tag="a" href="{{ route('products.create') }}" class="px-4">
            <x-lucide-plus class="size-4" />
            Novo
        </x-button>
    </div>

    @if (session('status'))
        <x-card class="border border-feedback-done/30 bg-feedback-done/10">
            <p class="text-sm text-grayin-300">{{ session('status') }}</p>
        </x-card>
    @endif

    <section class="border-dotted border border-grayin-500 rounded-lg overflow-x-auto">
        <table class="w-full min-w-180">
            <thead>
                <tr>
                    <th class="px-4 py-4 text-start text-sm text-grayin-400">Imagem</th>
                    <th class="px-4 py-4 text-start text-sm text-grayin-400">Nome</th>
                    <th class="px-4 py-4 text-start text-sm text-grayin-400">Descricao</th>
                    <th class="px-4 py-4 text-start text-sm text-grayin-400">Preço</th>
                    <th class="px-4 py-4 w-10"></th>
                </tr>
            </thead>

            <tbody>
                @forelse ($products as $product)
                    <tr class="border-t border-dotted border-grayin-500">
                        <td class="px-4 py-4">
                            @if ($product->image)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="size-12 object-cover rounded">
                            @else
                                <div class="size-12 bg-grayin-500 rounded flex items-center justify-center">
                                    <x-lucide-image class="size-5 text-grayin-400" />
                                </div>
                            @endif
                        </td>

                        <td class="px-4 py-4">
                            <span class="text-sm text-grayin-200">
                                {{ $product->name }}
                            </span>
                        </td>

                        <td class="px-4 py-4">
                            @if (!empty($product->description))
                                <span class="text-sm text-grayin-200">
                                    {{ Str::limit($product->description, 50) }}
                                </span>
                            @else
                                <span class="text-sm text-grayin-400">-</span>
                            @endif
                        </td>

                        <td class="px-4 py-4">
                            <span class="text-sm text-grayin-200">
                                R$ {{ number_format($product->price, 2, ',', '.') }}
                            </span>
                        </td>

                        <td class="flex gap-2 px-4 py-4">
                            <button
                                x-data="{
                                    checkoutUrl: '{{ route('checkout.start', $product) }}',
                                    copied: false
                                }"
                                @click="$clipboard(checkoutUrl).then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                class="flex items-center justify-center cursor-pointer size-7 bg-grayin-500 rounded"
                                :class="{ 'bg-green-500/10': copied }"
                                :title="copied ? 'Copiado!' : 'Copiar link checkout'"
                            >
                                <x-lucide-check class="size-3 text-green-500" x-show="copied" />
                                <x-lucide-link class="size-3" x-show="!copied" />
                            </button>

                            <a
                                href="{{ route('products.show', $product) }}"
                                class="flex items-center justify-center cursor-pointer size-7 bg-grayin-500 rounded"
                                title="Ver"
                            >
                                <x-lucide-eye class="size-3" />
                            </a>

                            <a
                                href="{{ route('products.edit', $product) }}"
                                class="flex items-center justify-center cursor-pointer size-7 bg-grayin-500 rounded"
                                title="Editar"
                            >
                                <x-lucide-pen-line class="size-3" />
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
                                        class="flex items-center justify-center cursor-pointer size-7 bg-red-100 text-red-600 rounded"
                                        title="Deletar"
                                    >
                                        <x-lucide-trash class="size-3" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-grayin-400">
                            Nenhum produto encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-layout.dashboard>
