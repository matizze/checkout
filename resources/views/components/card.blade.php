@props([
    'class' => null
])

<div {{ $attributes->class(["flex flex-col border border-grayin-500 rounded-xl p-7", $class]) }}>
    {{ $slot }}
</div>
