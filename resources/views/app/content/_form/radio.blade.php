@props([
    'label' => '',
    'name' => '',
    'placeholder' => '',
    'required' => false,
    'options' => [],
    'value' => '',
])

<div class="input @error($name) -invalid @enderror">
    <label class="input_ttl @if($required) -req @endif">{{ $label }}</label>
    <div class="input_list -inline">
        @foreach ($options as $option)
        <label><input type="radio" name="{{ $name }}" value="{{ $option->value }}"
                @if($option->value == $value) checked @endif @if($required) required @endif>{{ $option->label }}</label>
        @endforeach
    </div>
    @if($placeholder !== '')
    <p>{{ $placeholder }}</p>
    @endif
    @error($name)
    <div class="input_feedback">
        <span>{{ $message }}</span>
    </div>
    @enderror
</div>