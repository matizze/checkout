@props([
    'class' => null
])

<div {{ $attributes->class(["flex flex-col border border-gray-500 rounded-xl p-7", $class]) }}>
    {{ $slot }}
</div>
