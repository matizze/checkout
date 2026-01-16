<div x-data="{}">
    <x-card class="w-full bg-white">
        <form action="{{ route('products.contacts.import', $collection) }}" method="POST" enctype="multipart/form-data" class="flex flex-col space-y-3 w-full">
            @csrf

            <div class="flex flex-col space-y-3">
                <h1 class="font-bold text-lg text-gray-900">
                    Importar leads via CSV
                </h1>

                <p class="text-sm text-gray-300">
                    Envie um arquivo CSV contendo as colunas:
                </p>

                <ul class="list-disc pl-5 text-sm text-gray-200 space-y-1">
                    <li>
                        <span class="font-semibold">Telefone</span> <span class="text-sm text-feedback-danger">*</span>
                    </li>
                    <li>
                        <span class="font-semibold">Nome</span>
                    </li>
                </ul>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-400 mb-1">
                        Arquivo CSV
                    </label>
                        <input
                            type="file"
                            name="file"
                            id="file"
                            class="block w-full border border-gray-400 bg-white rounded-md px-3 py-2 text-sm text-gray-300/80 disabled:bg-gray-400 disabled:text-gray-300 disabled:cursor-not-allowed"
                        >
                </div>
            </div>

            <div class="flex gap-2">
                <x-button type="submit" class="w-full" variant="primary">
                    Importar
                </x-button>
                <x-button type="button" class="w-full" variant="ghost" @click="$dispatch('close-modal')">
                    Fechar
                </x-button>
            </div>
        </form>
    </x-card>
</div>
