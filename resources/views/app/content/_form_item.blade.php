{{-- itemのgetTypeからの値によって出力するフォームを切り替える --}}
@props([
    'item' => null,
    'value' => null,
    'content' => null,
])

@php
    $type = $item->getType();
    $name = $item->getInputName();
    $label = $item->getLabel();
    $required = $item->isRequired();
    $placeholder = $item->getPlaceholder();
    $options = $item->hasOptions() ? $item->getOptions() : [];
    $min = $item->hasMin() ? $item->getMin() : null;
    $max = $item->hasMax() ? $item->getMax() : null;
    $accept = $item->hasAccept() ? $item->getAccept() : null;
    $value = old($name, $content?->getValue($item));
@endphp

@include('app.content._form.' . $type, compact('name', 'label', 'required', 'placeholder', 'options', 'min', 'max', 'accept', 'value', 'content'))
