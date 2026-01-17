@props([
    'title' => null,
    'step' => 1,
])

<x-layout.app :title="$title">
    <div class="min-h-screen bg-surface py-8 px-4 dark:bg-surface-dark">
        <div class="max-w-4xl mx-auto">
            {{-- Progress Steps (Penguin UI) --}}
            <ol class="flex w-full items-center justify-center gap-2 mb-8" aria-label="checkout progress">
                {{-- Step 1 --}}
                <li class="text-sm" aria-label="produto">
                    <div class="flex items-center gap-2">
                        @if($step > 1)
                            {{-- Completed --}}
                            <span class="flex size-8 items-center justify-center rounded-full border border-primary bg-primary text-on-primary dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark">
                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                                <span class="sr-only">completed</span>
                            </span>
                            <span class="hidden w-max text-sm font-medium text-primary sm:inline dark:text-primary-dark">Produto</span>
                        @else
                            {{-- Current --}}
                            <span class="flex size-8 items-center justify-center rounded-full border border-primary bg-primary font-bold text-on-primary outline-2 outline-offset-2 outline-primary dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark dark:outline-primary-dark" aria-current="step">1</span>
                            <span class="hidden w-max text-sm font-bold text-primary sm:inline dark:text-primary-dark">Produto</span>
                        @endif
                    </div>
                </li>

                {{-- Step 2 --}}
                <li class="flex w-full items-center text-sm" aria-label="dados" @if($step === 2) aria-current="step" @endif>
                    {{-- Connector --}}
                    <span class="h-0.5 w-full {{ $step > 1 ? 'bg-primary dark:bg-primary-dark' : 'bg-outline dark:bg-outline-dark' }}" aria-hidden="true"></span>
                    <div class="flex items-center gap-2 pl-2">
                        @if($step > 2)
                            {{-- Completed --}}
                            <span class="flex size-8 shrink-0 items-center justify-center rounded-full border border-primary bg-primary text-on-primary dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark">
                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                                <span class="sr-only">completed</span>
                            </span>
                            <span class="hidden w-max text-sm font-medium text-primary sm:inline dark:text-primary-dark">Dados</span>
                        @elseif($step === 2)
                            {{-- Current --}}
                            <span class="flex size-8 shrink-0 items-center justify-center rounded-full border border-primary bg-primary font-bold text-on-primary outline-2 outline-offset-2 outline-primary dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark dark:outline-primary-dark">2</span>
                            <span class="hidden w-max text-sm font-bold text-primary sm:inline dark:text-primary-dark">Dados</span>
                        @else
                            {{-- Upcoming --}}
                            <span class="flex size-8 shrink-0 items-center justify-center rounded-full border border-outline bg-surface-alt font-medium text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">2</span>
                            <span class="hidden w-max text-sm text-on-surface sm:inline dark:text-on-surface-dark">Dados</span>
                        @endif
                    </div>
                </li>

                {{-- Step 3 --}}
                <li class="flex w-full items-center text-sm" aria-label="pagamento" @if($step === 3) aria-current="step" @endif>
                    {{-- Connector --}}
                    <span class="h-0.5 w-full {{ $step > 2 ? 'bg-primary dark:bg-primary-dark' : 'bg-outline dark:bg-outline-dark' }}" aria-hidden="true"></span>
                    <div class="flex items-center gap-2 pl-2">
                        @if($step > 3)
                            {{-- Completed --}}
                            <span class="flex size-8 shrink-0 items-center justify-center rounded-full border border-primary bg-primary text-on-primary dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark">
                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                                <span class="sr-only">completed</span>
                            </span>
                            <span class="hidden w-max text-sm font-medium text-primary sm:inline dark:text-primary-dark">Pagamento</span>
                        @elseif($step === 3)
                            {{-- Current --}}
                            <span class="flex size-8 shrink-0 items-center justify-center rounded-full border border-primary bg-primary font-bold text-on-primary outline-2 outline-offset-2 outline-primary dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark dark:outline-primary-dark">3</span>
                            <span class="hidden w-max text-sm font-bold text-primary sm:inline dark:text-primary-dark">Pagamento</span>
                        @else
                            {{-- Upcoming --}}
                            <span class="flex size-8 shrink-0 items-center justify-center rounded-full border border-outline bg-surface-alt font-medium text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">3</span>
                            <span class="hidden w-max text-sm text-on-surface sm:inline dark:text-on-surface-dark">Pagamento</span>
                        @endif
                    </div>
                </li>
            </ol>

            {{-- Content --}}
            <div {{ $attributes->class(['space-y-6']) }}>
                {{ $slot }}
            </div>
        </div>
    </div>
</x-layout.app>
