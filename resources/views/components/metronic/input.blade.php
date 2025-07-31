@props([
    'id' => null,
    'type' => 'text',
    'name',
    'value' => '',
    'placeholder' => 'Complete the field',
    'required' => false,
    'step' => null,
    'maxlength' => null, // <== make it dynamic
    'error' => null,
])

@php
    $inputClasses = 'form-control';
    if ($error) {
        $inputClasses .= ' is-invalid';
    }
@endphp

<input id="{{ $id ?? $name }}" class="{{ $inputClasses }}" type="{{ $type }}" name="{{ $name }}"
    value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}" aria-label="{{ $placeholder }}"
    {{ $required ? 'required' : '' }} {{ $step ? "step=$step" : '' }} {{ $maxlength ? "maxlength=$maxlength" : '' }}>

@if ($error)
    <div class="invalid-feedback">
        {{ $error }}
    </div>
@endif

{{--
<input class="form-control @error($name)is-invalid @enderror" id="{{ $id ?? '' }}" {{ $required ? 'required' : '' }} type="{{ $type ?? 'text' }}"
    name="{{ $name }}" step="0.01" placeholder="{{ $placeholder ?? 'Complete the field' }}" maxlength="250"
    value="{{ old($name, $value ?? '') }}" aria-label="{{ $placeholder ?? 'input' }} example">

@error($name)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror --}}

{{-- <input id="{{ $id ?? '' }}" type="{{ $type ?? 'text' }}"
    class="form-control form-control-solid @error($name)is-invalid @enderror" name="{{ $name }}" step="0.01"
    maxlength="250" placeholder="{{ $placeholder ?? '' }}" value="{{ old($name, $value ?? '') }}"
    {{ $required ?? '' }} />
@error($name)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror --}}
{{-- <x-input id="full_name" type="text" name="full_name" placeholder="Enter full name"
    colSize="col-lg-8"></x-input> --}}
