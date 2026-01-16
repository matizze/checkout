@props([
    'title' => null,
    'step' => 1,
])

<x-layout.app :title="$title">
    <div class="min-h-screen bg-grayin-600 py-8 px-4">
        <div class="max-w-4xl mx-auto">
            {{-- Header --}}
            <header class="text-center mb-8">
                <h1 class="text-2xl font-bold text-blue-dark">{{ config('app.name') }}</h1>
                <p class="text-grayin-300 text-sm mt-1">Checkout Seguro</p>
            </header>

            {{-- Progress Steps --}}
            <div class="flex items-center justify-center mb-8">
                <div class="flex items-center space-x-4">
                    {{-- Step 1 --}}
                    <div class="flex items-center">
                        <div @class([
                            'flex items-center justify-center size-8 rounded-full text-sm font-semibold',
                            'bg-blue-base text-white' => $step >= 1,
                            'bg-grayin-500 text-grayin-400' => $step < 1,
                        ])>
                            1
                        </div>
                        <span @class([
                            'ml-2 text-sm font-medium',
                            'text-blue-dark' => $step >= 1,
                            'text-grayin-400' => $step < 1,
                        ])>Produto</span>
                    </div>

                    <div @class([
                        'w-12 h-0.5',
                        'bg-blue-base' => $step >= 2,
                        'bg-grayin-500' => $step < 2,
                    ])></div>

                    {{-- Step 2 --}}
                    <div class="flex items-center">
                        <div @class([
                            'flex items-center justify-center size-8 rounded-full text-sm font-semibold',
                            'bg-blue-base text-white' => $step >= 2,
                            'bg-grayin-500 text-grayin-400' => $step < 2,
                        ])>
                            2
                        </div>
                        <span @class([
                            'ml-2 text-sm font-medium',
                            'text-blue-dark' => $step >= 2,
                            'text-grayin-400' => $step < 2,
                        ])>Dados</span>
                    </div>

                    <div @class([
                        'w-12 h-0.5',
                        'bg-blue-base' => $step >= 3,
                        'bg-grayin-500' => $step < 3,
                    ])></div>

                    {{-- Step 3 --}}
                    <div class="flex items-center">
                        <div @class([
                            'flex items-center justify-center size-8 rounded-full text-sm font-semibold',
                            'bg-blue-base text-white' => $step >= 3,
                            'bg-grayin-500 text-grayin-400' => $step < 3,
                        ])>
                            3
                        </div>
                        <span @class([
                            'ml-2 text-sm font-medium',
                            'text-blue-dark' => $step >= 3,
                            'text-grayin-400' => $step < 3,
                        ])>Pagamento</span>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div {{ $attributes->class(['space-y-6']) }}>
                {{ $slot }}
            </div>
        </div>
    </div>
</x-layout.app>
