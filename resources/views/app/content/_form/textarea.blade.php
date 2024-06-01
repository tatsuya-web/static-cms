@props([
    'label' => '',
    'name' => '',
    'placeholder' => '',
    'required' => false,
    'value' => '',
])

<div class="input @error($name) -invalid @enderror">
    <label class="input_ttl @if($required) -req @endif">{{ $label }}</label>
    <textarea name="{{ $name }}" id="ID__{{ $name }}" cols="30" rows="3" placeholder="{{ $placeholder }}" @if($required) required @endif>{!! nl2br(e($value)) !!}</textarea>
    @error($name)
    <div class="input_feedback">
        <span>{{ $message }}</span>
    </div>
    @enderror
</div>