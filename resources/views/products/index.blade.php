<x-layout.dashboard class="space-y-6" title="Listas de Leads">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-bold text-blue-dark">
            Listas de Leads
        </h1>

        <x-button tag="a" href="{{ route('products.create') }}" class="px-4">
            <x-lucide-plus class="size-4" />
            Novo
        </x-button>
    </div>

    <section class="border-dotted border border-gray-500 rounded-lg overflow-x-auto">
        <table class="w-full min-w-180">
            <thead>
                <tr>
                    <th class="px-4 py-4 text-start text-sm text-gray-400">Nome</th>
                    <th class="px-4 py-4 text-start text-sm text-gray-400">Descrição</th>
                    <th class="px-4 py-4 text-start text-sm text-gray-400">Leads ativos</th>
                    <th class="px-4 py-4 w-10"></th>
                </tr>
            </thead>

            <tbody>
                @forelse ($products as $collection)
                    @php
                        $contacts = $collection->contacts();
                    @endphp

                    <tr class="border-t border-dotted border-gray-500">
                        <td class="px-4 py-4">
                            <span class="text-sm text-gray-200">
                                {{ $collection->name }}
                            </span>
                        </td>

                        <td class="px-4 py-4">
                            @if (!empty($collection->description))
                                <span class="text-sm text-gray-200">
                                    {{ $collection->description }}
                                </span>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="px-4 py-4">
                            <span class="text-sm text-gray-200">
                                {{ $contacts->active()->count() }}
                            </span>
                        </td>

                        <td class="flex gap-2 px-4 py-4">
                            <a
                                href="{{ route('products.show', $collection) }}"
                                class="flex items-center justify-center cursor-pointer size-7 bg-gray-500 rounded"
                                title="Ver"
                            >
                                <x-lucide-eye class="size-3" />
                            </a>

                            <a
                                href="{{ route('products.edit', $collection) }}"
                                class="flex items-center justify-center cursor-pointer size-7 bg-gray-500 rounded"
                                title="Editar"
                            >
                                <x-lucide-pen-line class="size-3" />
                            </a>

                            <div x-data="{
                                confirmDelete() {
                                    return confirm('Tem certeza que deseja excluir esta lista? Essa ação não pode ser desfeita.');
                                }
                            }">
                                <form
                                    method="POST"
                                    :action="`{{ route('products.destroy', $collection) }}`"
                                    @submit="if (!confirmDelete()) $event.preventDefault()"
                                >
                                    @method('DELETE')
                                    @csrf
                                    <button
                                        type="submit"
                                        class="flex items-center justify-center cursor-pointer size-7 bg-red-100 text-red-600 rounded"
                                        title="Deletar/Destruir"
                                    >
                                        <x-lucide-trash class="size-3" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-400">
                            Nenhuma lista encontrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-layout.dashboard>
