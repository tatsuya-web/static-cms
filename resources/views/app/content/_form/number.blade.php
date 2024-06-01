@props([
    'label' => '',
    'name' => '',
    'placeholder' => '',
    'required' => false,
    'min' => null,
    'max' => null,
    'value' => null,
])

<div class="input @error($name) -invalid @enderror">
    <label class="input_ttl @if($required) -req @endif">{{ $label }}</label>
    <input type="number" name="{{ $name }}" id="ID__{{ $name }}" placeholder="{{ $placeholder }}" @if($required) required @endif value="{{ $value }}" @if($min) min="{{ $min }}" @endif @if($max) max="{{ $max }}" @endif>
    @error($name)
    <div class="input_feedback">
        <span>{{ $message }}</span>
    </div>
    @enderror
</div>