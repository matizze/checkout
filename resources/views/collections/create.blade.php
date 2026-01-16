<x-layout.dashboard class="space-y-6 px-40!" title="Nova Lista de Leads">
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-bold text-blue-dark">
            Nova Lista de Leads
        </h1>

        <x-button tag="a" variant="ghost" href="{{ route('collections.index') }}" class="px-4">
            Voltar
        </x-button>
    </div>

    @if (session('status'))
        <x-card class="border border-feedback-done/30 bg-feedback-done/10">
            <p class="text-sm text-gray-300">{{ session('status') }}</p>
        </x-card>
    @endif

    <x-card class="w-full">
        <form action="{{ route('collections.store') }}" method="POST" class="space-y-6 w-full">
            @csrf
            <div class="space-y-2">
                <label for="name" class="text-sm font-semibold text-gray-300">
                    Nome da lista
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
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
                    class="w-full rounded-md bg-white border border-gray-500 px-4 py-3 text-gray-100 placeholder:text-gray-300 focus:outline-none focus:ring-2 focus:ring-none"
                    aria-invalid="@error('description') true @else false @enderror"
                    aria-describedby="@error('description') desc_error @enderror"
                >{{ old('description') }}</textarea>

                @error('description')
                    <p id="desc_error" class="text-sm font-semibold text-feedback-danger">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3 pt-2">
                <x-button tag="a" variant="ghost" href="{{ route('collections.index') }}" class="px-4">
                    Cancelar
                </x-button>

                <x-button type="submit" class="px-4">
                    <x-lucide-plus class="size-4" />
                    Criar lista
                </x-button>
            </div>
        </form>
    </x-card>
</x-layout.dashboard>
