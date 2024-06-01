@props([
    'label' => '',
    'name' => '',
    'placeholder' => '',
    'required' => false,
    'options' => [],
    'value' => null,
])

<div class="input @error($name) -invalid @enderror">
    <label class="input_ttl @if($required) -req @endif">{{ $label }}</label>
    <div class="input_list -inline">
        @foreach ($options as $key => $option)
        <label><input type="checkbox" name="{{ $name }}[{{ $key }}]" value="{{ $option->value }}"
                @if($value !== null && in_array($option->value, $value)) checked @endif><span>{{ $option->label }}</span></label>
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