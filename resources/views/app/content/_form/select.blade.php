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
    <select name="{{ $name }}" @if($required) required @endif>
        <option>選択してください</option>
        @foreach($options as $option)
            <option value="{{ $option->value }}" @if(old($name) == $option->value) selected @endif
                @if($value !== null && in_array($option->value, $value)) checked @endif>{{ $option->label }}</option>
        @endforeach
    </select>
    @if($placeholder !== '')
    <p>{{ $placeholder }}</p>
    @endif
    @error($name)
    <div class="input_feedback">
        <span>{{ $message }}</span>
    </div>
    @enderror
</div>