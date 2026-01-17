<x-layout.dashboard class="space-y-6 px-40!" title="Novo Produto">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-bold text-blue-dark">
            Novo Produto
        </h1>

        <x-button tag="a" variant="ghost" href="{{ route('products.index') }}" class="px-4">
            Voltar
        </x-button>
    </div>

    @if (session('status'))
        <x-card class="border border-feedback-done/30 bg-feedback-done/10">
            <p class="text-sm text-grayin-300">{{ session('status') }}</p>
        </x-card>
    @endif

    <x-card class="w-full">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 w-full">
            @csrf

            <div class="space-y-2">
                <label for="name" class="text-sm font-semibold text-grayin-300">
                    Nome do produto
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Ex.: Camiseta Preta"
                    class="w-full rounded-md bg-white border border-grayin-500 px-4 py-3 text-grayin-100 placeholder:text-grayin-300 focus:outline-none focus:ring-2 focus:ring-blue-base"
                    required
                >

                @error('name')
                    <p class="text-sm font-semibold text-feedback-danger">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="description" class="text-sm font-semibold text-grayin-300">
                    Descricao (opcional)
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    placeholder="Ex.: Camiseta 100% algodao"
                    class="w-full rounded-md bg-white border border-grayin-500 px-4 py-3 text-grayin-100 placeholder:text-grayin-300 focus:outline-none focus:ring-2 focus:ring-blue-base"
                >{{ old('description') }}</textarea>

                @error('description')
                    <p class="text-sm font-semibold text-feedback-danger">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="space-y-2" x-data="{
                displayPrice: '{{ old('price', '') }}',
                toCents(value) {
                    return value.replace(/\./g, '').replace(',', '');
                }
            }">
                <label for="price" class="text-sm font-semibold text-grayin-300">
                    Preço
                </label>

                <div class="relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-grayin-300 font-semibold">R$</span>
                    <input
                        type="text"
                        id="price"
                        x-model="displayPrice"
                        x-mask:dynamic="$money($input, ',', '.')"
                        placeholder="0,00"
                        class="w-full rounded-md bg-white border border-grayin-500 pl-10 pr-4 py-3 text-grayin-100 placeholder:text-grayin-300 focus:outline-none focus:ring-2 focus:ring-blue-base"
                        required
                    >
                </div>
                <input type="hidden" name="price" :value="toCents(displayPrice)">

                @error('price')
                    <p class="text-sm font-semibold text-feedback-danger">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="image" class="text-sm font-semibold text-grayin-300">
                    Imagem (opcional)
                </label>

                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/*"
                    class="w-full rounded-md bg-white border border-grayin-500 px-4 py-3 text-grayin-100 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-grayin-500 file:text-grayin-200 hover:file:bg-grayin-400"
                >

                @error('image')
                    <p class="text-sm font-semibold text-feedback-danger">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="attachment" class="text-sm font-semibold text-grayin-300">
                    Anexo do email (opcional)
                </label>

                <p class="text-xs text-grayin-400">
                    Arquivo que será enviado como anexo no email de confirmação de compra (PDF, ZIP, etc.)
                </p>

                <input
                    type="file"
                    id="attachment"
                    name="attachment"
                    class="w-full rounded-md bg-white border border-grayin-500 px-4 py-3 text-grayin-100 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-grayin-500 file:text-grayin-200 hover:file:bg-grayin-400"
                >

                @error('attachment')
                    <p class="text-sm font-semibold text-feedback-danger">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3 pt-2">
                <x-button tag="a" variant="ghost" href="{{ route('products.index') }}" class="px-4">
                    Cancelar
                </x-button>

                <x-button type="submit" class="px-4">
                    <x-lucide-plus class="size-4" />
                    Criar produto
                </x-button>
            </div>
        </form>
    </x-card>
</x-layout.dashboard>
