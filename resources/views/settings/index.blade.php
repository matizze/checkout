<x-layout.dashboard title="Configurações">
    <div class="space-y-6">
        {{-- Header --}}
        <header>
            <h1 class="text-2xl font-bold text-blue-dark">
                Configurações
            </h1>
            <p class="text-sm text-grayin-300 mt-1">
                Gerencie seu perfil e configurações da conta
            </p>
        </header>

        {{-- Layout Grid: Sidebar + Content --}}
        <div class="grid grid-cols-[250px_minmax(0,1fr)] gap-6">
            {{-- Sidebar com Abas --}}
            <aside class="h-fit space-y-2">
                <a
                    href="{{ route('settings.index', ['tab' => 'profile']) }}"
                    @class([
                        'flex items-center gap-3 px-4 py-3 text-sm transition-colors border-l-4',
                        'border-blue-base text-blue-dark font-semibold' => $active === 'profile',
                        'border-transparent text-grayin-300 hover:text-grayin-100 hover:border-grayin-500' => $active !== 'profile',
                    ])
                >
                    <x-lucide-user class="size-4" />
                    <span>Perfil</span>
                </a>

                <a
                    href="{{ route('settings.index', ['tab' => 'password']) }}"
                    @class([
                        'flex items-center gap-3 px-4 py-3 text-sm transition-colors border-l-4',
                        'border-blue-base text-blue-dark font-semibold' => $active === 'password',
                        'border-transparent text-grayin-300 hover:text-grayin-100 hover:border-grayin-500' => $active !== 'password',
                    ])
                >
                    <x-lucide-lock class="size-4" />
                    <span>Redefinir Senha</span>
                </a>
            </aside>

            {{-- Conteúdo da Aba Ativa --}}
            <main>
                @switch($active)
                    @case('profile')
                        @include('settings.partials.profile')
                        @break
                    @case('password')
                        @include('settings.partials.password')
                        @break
                @endswitch
            </main>
        </div>
    </div>
</x-layout.dashboard>
