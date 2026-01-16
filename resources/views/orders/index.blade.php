<x-layout.dashboard class="space-y-6" title="Pedidos">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-bold text-blue-dark">
            Pedidos
        </h1>
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
                    <th class="px-4 py-4 text-start text-sm text-grayin-400">#</th>
                    <th class="px-4 py-4 text-start text-sm text-grayin-400">Cliente</th>
                    <th class="px-4 py-4 text-start text-sm text-grayin-400">Total</th>
                    <th class="px-4 py-4 text-start text-sm text-grayin-400">Status Pedido</th>
                    <th class="px-4 py-4 text-start text-sm text-grayin-400">Status Pagamento</th>
                    <th class="px-4 py-4 text-start text-sm text-grayin-400">Data</th>
                    <th class="px-4 py-4 w-10"></th>
                </tr>
            </thead>

            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-t border-dotted border-grayin-500">
                        <td class="px-4 py-4">
                            <span class="text-sm font-medium text-grayin-200">
                                #{{ $order->id }}
                            </span>
                        </td>

                        <td class="px-4 py-4">
                            <div>
                                <span class="text-sm text-grayin-200 block">
                                    {{ $order->customer->name }}
                                </span>
                                <span class="text-xs text-grayin-400">
                                    {{ $order->customer->email }}
                                </span>
                            </div>
                        </td>

                        <td class="px-4 py-4">
                            <span class="text-sm font-semibold text-blue-base">
                                R$ {{ number_format($order->total_amount, 2, ',', '.') }}
                            </span>
                        </td>

                        <td class="px-4 py-4">
                            <span @class([
                                'px-2 py-1 rounded text-xs font-medium',
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
                        </td>

                        <td class="px-4 py-4">
                            @if ($order->payment)
                                <span @class([
                                    'px-2 py-1 rounded text-xs font-medium',
                                    'bg-yellow-100 text-yellow-800' => $order->payment->status === 'PENDING',
                                    'bg-green-100 text-green-800' => in_array($order->payment->status, ['RECEIVED', 'CONFIRMED']),
                                    'bg-red-100 text-red-800' => in_array($order->payment->status, ['OVERDUE', 'REFUNDED']),
                                ])>
                                    @switch($order->payment->status)
                                        @case('PENDING')
                                            Aguardando
                                            @break
                                        @case('RECEIVED')
                                        @case('CONFIRMED')
                                            Confirmado
                                            @break
                                        @case('OVERDUE')
                                            Vencido
                                            @break
                                        @default
                                            {{ $order->payment->status }}
                                    @endswitch
                                </span>
                            @else
                                <span class="text-sm text-grayin-400">-</span>
                            @endif
                        </td>

                        <td class="px-4 py-4">
                            <span class="text-sm text-grayin-200">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </span>
                        </td>

                        <td class="px-4 py-4">
                            <a
                                href="{{ route('orders.show', $order) }}"
                                class="flex items-center justify-center cursor-pointer size-7 bg-grayin-500 rounded"
                                title="Ver detalhes"
                            >
                                <x-lucide-eye class="size-3" />
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-grayin-400">
                            Nenhum pedido encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    @if ($orders->hasPages())
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</x-layout.dashboard>
