@props([
    'name',
    'label',
    'type' => 'text',
    'id' => null,
    'placeholder' => null,
    'value' => '',
    'required' => false,
    'autofocus' => false,
    'autocomplete' => 'off',
    'errorBag' => null,
])

@php
    $id = $id ?? $name;
    $placeholder = $placeholder ?? ucfirst($label);
    $errorExists = $errorBag 
        ? $errors->{$errorBag}->has($name) 
        : $errors->has($name);
    $errorMessage = $errorBag 
        ? $errors->{$errorBag}->first($name) 
        : $errors->first($name);
@endphp

<div {{ $attributes->merge(['class' => 'form-floating mb-3']) }}>
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        class="form-control {{ $errorExists ? 'is-invalid' : '' }}"
        placeholder="{{ $placeholder }}"
        value="{{ $type !== 'password' ? old($name, $value) : '' }}"
        {{ $required ? 'required' : '' }}
        {{ $autofocus ? 'autofocus' : '' }}
        autocomplete="{{ $autocomplete }}"
    >
    <label for="{{ $id }}">
        {{ $label }}
        @if($required)
            <span class="text-danger" title="Campo obrigatório">*</span>
        @endif
    </label>

    @if ($errorExists)
        <div class="invalid-feedback">
            {{ $errorMessage }}
        </div>
    @endif
</div>
