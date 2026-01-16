@props(['clients', 'selected' => null])

<div x-data="{ submitting: false }">
    <label for="client" class="block text-sm font-medium text-gray-700 mb-1 sr-only">
        Escolha um cliente
    </label>

    <form method="POST" action="{{ route('client.switch') }}" @submit="submitting = true">
        @csrf

        <div class="relative">
            <select
                id="client"
                name="client_id"
                @change="$el.form.submit()"
                class="block w-full appearance-none pr-10 border border-grayin-300/20 bg-grayin-300/10 rounded-md px-3 py-2 text-xs text-grayin-300 focus:outline-none focus:ring-none transition-colors disabled:bg-grayin-100 disabled:text-grayin-300 disabled:cursor-not-allowed"
                :disabled="submitting"
            >
                @if (count($clients) === 0)
                    <option value="" selected disabled class="text-grayin-400">
                        Nenhum cliente disponível para selecionar
                    </option>
                @else
                    <option value="" disabled {{ !$selected ? 'selected' : '' }} class="text-grayin-400">
                        Selecione um cliente
                    </option>
                    @foreach ($clients as $client)
                        <option
                            value="{{ $client->id }}"
                            {{ $selected == $client->id ? 'selected' : '' }}
                        >
                            {{ $client->name }}
                        </option>
                    @endforeach
                @endif
            </select>

            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                <x-lucide-chevron-down class="size-3 text-grayin-400" />
            </div>
        </div>
    </form>
</div>
