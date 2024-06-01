@props([
    'label' => '',
    'name' => '',
    'placeholder' => '',
    'required' => false,
    'value' => '',
])

<div class="input @error($name) -invalid @enderror">
    <label class="input_ttl @if($required) -req @endif">{{ $label }}</label>
    <input type="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" name="{{ $name }}" id="ID__{{ $name }}" placeholder="{{ $placeholder }}" @if($required) required @endif value="{{ $value }}">
    @error($name)
    <div class="input_feedback">
        <span>{{ $message }}</span>
    </div>
    @enderror
</div>