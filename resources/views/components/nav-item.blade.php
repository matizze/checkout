@props([
    'icon' => 'menu',
    'route' => '#',
])

@php
    $active = $route
        ? request()->routeIs($route)
        : request()->fullUrl() === $href;
@endphp

<a
    href="{{ $route ? route($route) : $href }}"
    @class([
        'flex items-center justify-start rounded gap-3 p-3',
        'bg-blue-dark text-gray-600' => $active,
        'bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-500' => ! $active,
    ])
>
    <x-icon :name="'lucide-' . $icon" class="size-5" />
    <span class="text-sm font-normal">{{ $slot }}</span>
</a>
