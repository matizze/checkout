<div x-data="{}">
    <form method="POST" action="{{ route('products.contacts.store', $collection) }}">
        @csrf

        <x-card class="flex flex-col w-full bg-white p-4 gap-4">
            <div class="flex flex-col space-y-3">
                <h1 class="font-bold text-lg text-gray-900">Adicionar lead</h1>

                <div class="space-y-2">
                    <label for="lead_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nome (opcional)
                    </label>
                    <input
                        id="lead_name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        placeholder="Ex.: Maria Silva"
                        class="block w-full border border-gray-500 bg-white rounded-md px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    >
                </div>

                <div class="space-y-2">
                    <label for="lead_phone" class="block text-sm font-medium text-gray-700 mb-1">
                        Telefone
                    </label>
                    <input
                        id="lead_phone"
                        name="phone"
                        type="text"
                        value="{{ old('phone') }}"
                        placeholder="Ex.: 5511999999999"
                        class="block w-full border border-gray-500 bg-white rounded-md px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        required
                    >
                    <p class="text-xs text-gray-600">
                        Use o formato E.164 (ex.: 55 + DDD + número).
                    </p>
                </div>
            </div>

            <div class="flex gap-2">
                <x-button
                    type="button"
                    class="w-full"
                    variant="ghost"
                    @click="$dispatch('close-modal')"
                >
                    Cancelar
                </x-button>

                <x-button type="submit" class="w-full">
                    Salvar
                </x-button>
            </div>
        </x-card>
    </form>
</div>
