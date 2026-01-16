<x-layout.dashboard class="space-y-6" title="Editar Pedido: {{ $collection->name }}">
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-bold text-blue-dark">
            Editar Pedido
        </h1>

        <div class="flex flex-wrap items-center gap-3">
            <x-button tag="a" variant="ghost" href="{{ route('products.show', $collection) }}" class="px-4">
                Cancelar
            </x-button>

            <div x-data="{
                confirmDelete() {
                    return confirm('Tem certeza que deseja excluir esta lista? Essa ação não pode ser desfeita.');
                }
            }">
                <form
                    method="POST"
                    :action="`{{ route('products.destroy', $collection) }}`"
                    @submit="if (!confirmDelete()) $event.preventDefault()"
                    class="inline"
                >
                    @method('DELETE')
                    @csrf

                    <x-button type="submit" variant="destructive" class="px-4">
                        <x-lucide-trash-2 class="size-4.5" />
                        Excluir
                    </x-button>
                </form>
            </div>
        </div>
    </div>

    @if (session('status'))
        <x-card class="border border-feedback-done/30 bg-feedback-done/10">
            <p class="text-sm text-gray-300">{{ session('status') }}</p>
        </x-card>
    @endif

    <x-card class="w-full">
        <form action="{{ route('products.update', $collection) }}" method="POST" class="space-y-6 w-full">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-300">
                    Cliente
                </label>

                <div class="w-full rounded-md bg-gray-500 border border-gray-500 px-4 py-3 text-gray-200">
                    {{ $collection->client?->name ?? '—' }}
                </div>

                <p class="text-xs text-gray-400">
                    O cliente não pode ser alterado após a criação da lista.
                </p>
            </div>

            <div class="space-y-2">
                <label for="name" class="text-sm font-semibold text-gray-300">
                    Nome da lista
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $collection->name) }}"
                    placeholder="Ex.: Leads Novembro"
                    class="w-full rounded-md bg-white border border-gray-500 px-4 py-3 text-gray-100 placeholder:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-base"
                    aria-invalid="@error('name') true @else false @enderror"
                    aria-describedby="@error('name') name_error @enderror"
                    required
                >

                @error('name')
                    <p id="name_error" class="text-sm font-semibold text-feedback-danger">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="description" class="text-sm font-semibold text-gray-300">
                    Descrição (opcional)
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    placeholder="Ex.: Leads captados via campanha X"
                    class="w-full rounded-md bg-white border border-gray-500 px-4 py-3 text-gray-100 placeholder:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-base"
                    aria-invalid="@error('description') true @else false @enderror"
                    aria-describedby="@error('description') description_error @enderror"
                >{{ old('description', $collection->description) }}</textarea>

                @error('description')
                    <p id="description_error" class="text-sm font-semibold text-feedback-danger">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3 pt-2">
                <x-button tag="a" variant="ghost" href="{{ route('products.show', $collection) }}" class="px-4">
                    Voltar
                </x-button>

                <x-button type="submit" class="px-4">
                    Salvar
                </x-button>
            </div>
        </form>
    </x-card>
</x-layout.dashboard>
