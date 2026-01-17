<x-layout.dashboard class="space-y-6" title="Pedido #{{ $order->id }}">
    <header class="flex flex-wrap items-start justify-between gap-4">
        <div class="space-y-3">
            <h1 class="text-2xl font-bold text-blue-dark">
                Pedido #{{ $order->id }}
            </h1>

            <div class="flex flex-wrap items-center gap-3 text-sm text-grayin-300">
                <a href="{{ route('orders.index') }}" class="font-semibold hover:text-blue-base">
                    ← Voltar
                </a>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span @class([
                'px-3 py-1 rounded-full text-sm font-medium',
                'bg-yellow-100 text-yellow-800' => $order->status === 'pending',
                'bg-green-100 text-green-800' => $order->status === 'paid',
                'bg-red-100 text-red-800' => $order->status === 'cancelled',
            ])>
                @switch($order->status)
                    @case('pending')
                        Pendente
                        @break
                    @case('paid')
                        Pago
                        @break
                    @case('cancelled')
                        Cancelado
                        @break
                @endswitch
            </span>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Dados do Cliente --}}
        <x-card>
            <h3 class="text-lg font-semibold text-grayin-200 mb-4">Cliente</h3>

            <div class="space-y-3">
                <div>
                    <p class="text-sm text-grayin-400">Nome</p>
                    <p class="text-grayin-200">{{ $order->customer->name }}</p>
                </div>

                <div>
                    <p class="text-sm text-grayin-400">E-mail</p>
                    <p class="text-grayin-200">{{ $order->customer->email }}</p>
                </div>

                <div>
                    <p class="text-sm text-grayin-400">CPF</p>
                    <p class="text-grayin-200">{{ $order->customer->cpf_cnpj }}</p>
                </div>

                @if ($order->customer->phone)
                    <div>
                        <p class="text-sm text-grayin-400">Telefone</p>
                        <p class="text-grayin-200">{{ $order->customer->phone }}</p>
                    </div>
                @endif
            </div>
        </x-card>

        {{-- Dados do Pagamento --}}
        <x-card>
            <h3 class="text-lg font-semibold text-grayin-200 mb-4">Pagamento</h3>

            @if ($order->payment)
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-grayin-400">Metodo</p>
                        <p class="text-grayin-200 flex items-center">
                            <x-lucide-landmark class="size-4 mr-2 text-blue-base" />
                            {{ $order->payment->billing_type }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-grayin-400">Status</p>
                        <span @class([
                            'inline-block px-2 py-1 rounded text-xs font-medium',
                            'bg-yellow-100 text-yellow-800' => $order->payment->status === 'PENDING',
                            'bg-green-100 text-green-800' => in_array($order->payment->status, ['RECEIVED', 'CONFIRMED']),
                            'bg-red-100 text-red-800' => in_array($order->payment->status, ['OVERDUE', 'REFUNDED']),
                        ])>
                            {{ $order->payment->status }}
                        </span>
                    </div>

                    <div>
                        <p class="text-sm text-grayin-400">ID Asaas</p>
                        <p class="text-grayin-200 text-xs font-mono">{{ $order->payment->asaas_id }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-grayin-400">Vencimento</p>
                        <p class="text-grayin-200">{{ $order->payment->due_date->format('d/m/Y') }}</p>
                    </div>

                    @if ($order->payment->paid_at)
                        <div>
                            <p class="text-sm text-grayin-400">Pago em</p>
                            <p class="text-grayin-200">{{ $order->payment->paid_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-grayin-400">Nenhum pagamento registrado.</p>
            @endif
        </x-card>

        {{-- Resumo --}}
        <x-card>
            <h3 class="text-lg font-semibold text-grayin-200 mb-4">Resumo</h3>

            <div class="space-y-3">
                <div>
                    <p class="text-sm text-grayin-400">Data do Pedido</p>
                    <p class="text-grayin-200">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div>
                    <p class="text-sm text-grayin-400">Itens</p>
                    <p class="text-grayin-200">{{ $order->items->count() }}</p>
                </div>

                <div class="pt-3 border-t border-grayin-500">
                    <p class="text-sm text-grayin-400">Total</p>
                    <p class="text-2xl font-bold text-blue-base">
                        {{ $order->total_amount }}
                    </p>
                </div>
            </div>
        </x-card>
    </div>

    {{-- Itens do Pedido --}}
    <x-card>
        <h3 class="text-lg font-semibold text-grayin-200 mb-4">Itens do Pedido</h3>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-grayin-500">
                        <th class="px-4 py-3 text-left text-sm text-grayin-400">Produto</th>
                        <th class="px-4 py-3 text-center text-sm text-grayin-400">Qtd</th>
                        <th class="px-4 py-3 text-right text-sm text-grayin-400">Preco Unit.</th>
                        <th class="px-4 py-3 text-right text-sm text-grayin-400">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr class="border-b border-grayin-500/50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($item->product->image)
                                        <img
                                            src="{{ $item->product->image_url }}"
                                            alt="{{ $item->product->name }}"
                                            class="size-10 object-cover rounded"
                                        >
                                    @else
                                        <div class="size-10 bg-grayin-500 rounded flex items-center justify-center">
                                            <x-lucide-image class="size-4 text-grayin-400" />
                                        </div>
                                    @endif
                                    <span class="text-grayin-200">{{ $item->product->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center text-grayin-200">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-4 py-3 text-right text-grayin-200">
                                {{ $item->unit_price }}
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-grayin-200">
                                {{ $item->subtotal }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right font-semibold text-grayin-200">
                            Total
                        </td>
                        <td class="px-4 py-3 text-right text-lg font-bold text-blue-base">
                            {{ $order->total_amount }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </x-card>
</x-layout.dashboard>
