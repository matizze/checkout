@props([
    'tag' => 'button',
    'variant' => 'default',
])

@php
    $variants = [
        'base' => 'flex gap-2 items-center justify-center h-10 p-3 rounded-md text-sm cursor-pointer',
        'default' => 'bg-grayin-100 text-grayin-600',
        'ghost' => 'bg-grayin-500 text-grayin-200',
        'destructive' => 'bg-feedback-danger text-grayin-600',
        'destructive-outline' => 'bg-white border border-feedback-danger text-feedback-danger',
    ];

    $key = array_key_exists($variant, $variants) ? $variant : 'default';

    $extra = [];
    if ($tag === 'button' && ! $attributes->has('type')) {
        $extra['type'] = 'button';
    }
@endphp

<{{ $tag }}
    {{ $attributes->merge(['class' => $variants['base'] . ' ' . $variants[$key]])->merge($extra) }}
>
    {{ $slot }}
</{{ $tag }}>
