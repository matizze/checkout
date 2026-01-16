@props([
    'class' => null
])

<div {{ $attributes->class(["flex border border-gray-500 rounded-xl p-7", $class]) }}>
    {{ $slot }}
</div>
