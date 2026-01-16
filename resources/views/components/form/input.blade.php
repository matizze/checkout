@props([
    'label',
    'name',
    'type' => 'text',
    'value' => null,
])

@error($name)
    @php
        $hasError = true;
    @endphp
@else
    @php
        $hasError = false;
    @endphp
@enderror

<div class="group">
    <label for={{ $name }} class="uppercase transition-colors text-xss block {{ $hasError ? 'text-red-500' : 'text-gray-300 group-focus-within:text-indigo-500' }}">{{ $label }}</label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        class="h-10 w-full text-sm text-gray-200 border-0 border-b border-gray-500 placeholder:text-gray-400 placeholder:text-sm focus:outline-none focus:border-indigo-500 focus:border-b-2 transition-colors"
        {{ $attributes }}
    />

    @error($name)
        <div class="text-red-500 text-xs mt-2 flex gap-1">
            <x-icon.circle-alert size='16' />
            {{ $message }}
        </div>
    @enderror

</div>
