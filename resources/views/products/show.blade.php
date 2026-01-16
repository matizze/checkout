<x-layout.dashboard class="space-y-6" title="Pedido: {{ $collection->name }}">
    {{-- Header --}}
    <header class="flex flex-wrap items-start justify-between gap-4">
        <div class="space-y-3">
            <h1 class="text-2xl font-bold text-blue-dark">
                {{ $collection->name }}
            </h1>

            @if (!empty($collection->description))
                <p class="text-sm text-gray-300 max-w-3xl">
                    {{ $collection->description }}
                </p>
            @else
                <p class="text-sm text-gray-400">
                    —
                </p>
            @endif

            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-300">
                <a href="{{ route('products.index') }}" class="font-semibold hover:text-blue-base">
                    ← Voltar
                </a>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{-- Edit --}}
            <a
                href="{{ route('products.edit', $collection) }}"
                class="flex items-center justify-center cursor-pointer size-9 bg-gray-500 rounded"
                title="Editar lista"
            >
                <x-lucide-pen-line class="size-4" />
            </a>

            {{-- Delete --}}
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
                        class="flex items-center justify-center cursor-pointer size-9 bg-red-100 text-red-600 rounded"
                        title="Excluir lista"
                    >
                        <x-lucide-trash class="size-4" />
                    </button>
                </form>
            </div>
        </div>
    </header>

    @if (session('status'))
        <x-card class="border border-feedback-done/30 bg-feedback-done/10">
            <p class="text-sm text-gray-300">{{ session('status') }}</p>
        </x-card>
    @endif

    {{-- Metrics --}}
    <section class="grid w-full grid-cols-1 gap-4 sm:grid-cols-3">
        <x-card class="w-full">
            <div class="space-y-2">
                <p class="text-xs text-gray-400">Total de contatos</p>
                <p class="text-2xl font-bold text-gray-100">
                    {{ $metrics['total'] ?? 0 }}
                </p>
            </div>
        </x-card>

        <x-card class="w-full">
            <div class="space-y-2">
                <p class="text-xs text-gray-400">Leads ativos</p>
                <p class="text-2xl font-bold text-gray-100">
                    {{ $metrics['opt_in'] ?? 0 }}
                </p>
            </div>
        </x-card>

        <x-card class="w-full">
            <div class="space-y-2">
                <p class="text-xs text-gray-400">Opt-out</p>
                <p class="text-2xl font-bold text-gray-100">
                    {{ $metrics['opt_out'] ?? 0 }}
                </p>
            </div>
        </x-card>
    </section>

    {{-- Actions + Filters --}}
    <x-card class="w-full">
        <div class="flex w-full flex-col gap-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-3">
                    {{-- Adicionar contato - modal --}}
                    <x-modal title="Adicionar Lead" size="max-w-lg">
                        <x-slot name="trigger">
                            <x-button class="px-4">
                                <x-lucide-plus class="size-4" />
                                Adicionar contato
                            </x-button>
                        </x-slot>

                        @include('products.partials.contact.create')
                    </x-modal>

                    {{-- Import CSV modal --}}
                    <x-modal title="Importar Leads (CSV)" size="max-w-lg">
                        <x-slot name="trigger">
                            <x-button variant="ghost" class="px-4">
                                <x-lucide-upload class="size-4" />
                                Importar CSV
                            </x-button>
                        </x-slot>

                        @include('products.partials.contact.import', ['collection' => $collection])
                    </x-modal>
                </div>

                {{-- Search --}}
                <form method="GET" action="{{ route('products.show', $collection) }}" class="flex items-center gap-2">
                    <div class="relative">
                        <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-gray-400" />
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Buscar por nome ou telefone..."
                            class="pl-10 pr-4 py-2 text-sm bg-gray-600 border border-gray-500 rounded-lg text-gray-100 placeholder-gray-400 focus:outline-none focus:border-gray-300 w-64"
                        />
                    </div>
                    @if (request('search'))
                        <a href="{{ route('products.show', $collection) }}" class="text-gray-400 hover:text-gray-200" title="Limpar busca">
                            <x-lucide-x class="size-5" />
                        </a>
                    @endif
                </form>
            </div>

            {{-- Leads Table --}}
            <section class="w-full border-dotted border border-gray-500 rounded-lg overflow-hidden">
                <table class="w-full table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-4 text-start text-sm text-gray-400">Nome</th>
                            <th class="px-4 py-4 text-start text-sm text-gray-400">Telefone</th>
                            <th class="px-4 py-4 text-start text-sm text-gray-400">Status</th>
                            <th class="px-4 py-4 w-10"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($contacts as $contact)
                            @php
                                $isActive = $contact->opt_in;
                                $label = $isActive ? 'Opt-in' : 'Opt-out';
                            @endphp
                            <tr class="border-t border-dotted border-gray-500">
                                <td class="px-4 py-4">
                                    <span class="text-sm text-gray-100">
                                        {{ $contact->name ?? '—' }}
                                    </span>
                                </td>

                                <td class="px-4 py-4">
                                    <span class="text-sm text-gray-200">
                                        {{ $contact->phone }}
                                    </span>
                                </td>

                                <td class="px-4 py-4">
                                    <span @class([
                                        'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold',
                                        'bg-feedback-done/20 text-gray-200' => $isActive,
                                        'bg-red-100 text-red-600' => ! $isActive,
                                    ])>
                                        {{ $label }}
                                    </span>
                                </td>

                                <td class="flex gap-2 px-4 py-4 justify-end">
                                    @if ($isActive)
                                        <div x-data="{
                                            confirmOptOut() {
                                                return confirm('Marcar este contact como Opt-out? Ele permanecerá na lista, mas não receberá mensagens.');
                                            }
                                        }">
                                            <form
                                                method="POST"
                                                :action="`{{ route('contacts.opt-out', [$collection, $contact]) }}`"
                                                @submit="if (!confirmOptOut()) $event.preventDefault()"
                                            >
                                                @method('PATCH')
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="flex items-center justify-center cursor-pointer size-7 bg-gray-500 rounded"
                                                    title="Marcar como Opt-out"
                                                >
                                                    <x-lucide-ban class="size-3" />
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div x-data="{
                                            confirmOptIn() {
                                                return confirm('Marcar este contact como Opt-in?');
                                            }
                                        }">
                                            <form
                                                method="POST"
                                                :action="`{{ route('contacts.opt-in', [$collection, $contact]) }}`"
                                                @submit="if (!confirmOptIn()) $event.preventDefault()"
                                            >
                                                @method('PATCH')
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="flex items-center justify-center cursor-pointer size-7 bg-gray-500 rounded"
                                                    title="Marcar como Opt-in"
                                                >
                                                    <x-lucide-check class="size-3" />
                                                </button>
                                            </form>
                                        </div>
                                    @endif

                                    <div x-data="{
                                        confirmRemove() {
                                            return confirm('Remover este contact da lista?');
                                        }
                                    }">
                                        <form
                                            method="POST"
                                            :action="`{{ route('products.contacts.destroy', [$collection, $contact]) }}`"
                                            @submit="if (!confirmRemove()) $event.preventDefault()"
                                        >
                                            @method('DELETE')
                                            @csrf
                                            <button
                                                type="submit"
                                                class="flex items-center justify-center cursor-pointer size-7 bg-red-100 text-red-600 rounded"
                                                title="Remover contact"
                                            >
                                                <x-lucide-trash class="size-3" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-400">
                                    Nenhum contact encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>

            {{-- Pagination --}}
            <x-pagination :paginator="$contacts" />
        </div>
    </x-card>
</x-layout.dashboard>
