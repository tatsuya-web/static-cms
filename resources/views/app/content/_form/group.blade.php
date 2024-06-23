@props([
    'label' => '',
    'name' => '',
    'placeholder' => '',
    'items' => [],
    'content' => null,
])

@php
    $items = $item->getItems();
@endphp

<div class="input -has_item @error($name) -invalid @enderror">
    <label class="input_ttl @if($required) -req @endif">{{ $label }}</label>
    @if($placeholder)
    <p>{{ $placeholder }}</p>
    @endif
    @error($name)
    <div class="input_feedback">
        <span>{{ $message }}</span>
    </div>
    @enderror
    @foreach ($items as $item)
        @php
            $value = old($item->getValidationName(), $content?->getValue($item));
        @endphp
        @include('app.content._form_item' , ['item' => $item, 'value' => $value])
    @endforeach
</div>