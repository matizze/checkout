@props(['title' => null])

<x-layout.app :title="$title">
    <div x-data="notifications"></div>
    <div class="pt-3 h-full grid grid-cols-[200px_minmax(0,1fr)]">
        <aside class="flex flex-col items-center justify-between">
            <header class="flex flex-col items-center justify-center px-5 py-6 space-x-3 w-full">
                <img
                    src="/brand/nav-header-white.svg"
                    alt="Dashboard"
                    class="size-30 select-none pointer-events-none -m-6"
                />
            </header>

            <nav class="flex flex-1 px-4 py-5 flex-col space-y-1 w-full">
                {{-- <x-nav-item tag="a" icon="server" class="justify-start gap-3 p-3" route="instances.index">
                    Instâncias
                </x-nav-item> --}}

                <x-nav-item tag="a" icon="package" class="justify-start gap-3 p-3" route="products.index">
                    Produtos
                </x-nav-item>

                {{-- <x-nav-item tag="a" icon="megaphone" class="justify-start gap-3 p-3" route="campaigns.index">
                    Campanhas
                </x-nav-item> --}}
            </nav>

            <footer class="px-4 py-5 w-full space-y-4 border-t border-gray-200">
                <x-user-menu class=""/>
            </footer>
        </aside>

        <main {{ $attributes->class(["overflow-y-auto bg-white text-gray-100 rounded-tl-2xl px-12 py-13 min-h-0"]) }}>
            {{ $slot }}
        </main>
    </div>
</x-layout.app>
