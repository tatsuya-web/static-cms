@props([
    'label' => '',
    'name' => '',
    'placeholder' => '',
    'required' => false,
    'accept' => '',
    'value' => '',
])

<div class="input @error($name) -invalid @enderror">
    <label class="input_ttl @if($required) -req @endif">{{ $label }}</label>
    <input type="file" name="{{ $name }}" accept="{{ $accept }}" @if($required) required @endif>
    @error($name)
    <div class="input_feedback">
        <span>{{ $message }}</span>
    </div>
    @enderror
</div>