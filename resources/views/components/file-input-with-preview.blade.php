@props([
    'name',
    'label',
    'currentImage' => null,
    'imageWidth' => 120,
    'id' => null,
    'accept' => null,
])

@php
    $id = $id ?? $name;
    $errorExists = $errors->has($name);
    $errorMessage = $errors->first($name);
@endphp

<div {{ $attributes->merge(['class' => 'mb-3']) }}>
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>

    @if ($currentImage)
        <div class="mb-2">
            <img src="{{ $currentImage }}" alt="Foto atual" class="img-thumbnail" width="{{ $imageWidth }}">
        </div>
    @endif

    <input
        type="file"
        name="{{ $name }}"
        id="{{ $id }}"
        class="form-control {{ $errorExists ? 'is-invalid' : '' }}"
        @if($accept) accept="{{ $accept }}" @endif
        {{ $attributes->except(['class']) }}
    >
    @if ($errorExists)
        <div class="invalid-feedback">{{ $errorMessage }}</div>
    @endif
</div>
