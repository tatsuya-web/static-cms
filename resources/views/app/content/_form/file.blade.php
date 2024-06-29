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
    @if($accept === 'image/*' && $value)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        <div class="file">
            <div class="del" onclick="filedel(this, '{{ $name }}', '{{ $value }}')">X</div>
            <img src="/{{ $value }}" alt="画像">
        </div>
    @elseif($accept === 'video/*' && $value)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        <div class="file">
            <div class="del" onclick="filedel(this, '{{ $name }}', '{{ $value }}')">X</div>
            <video src="/{{ $value }}" controls></video>
        </div>
    @elseif($accept === 'document' && $value)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        <div class="file">
            <div class="del" onclick="filedel(this, '{{ $name }}', '{{ $value }}')">X</div>
            <a href="{{ $value }}" target="_blank" rel="noopener noreferrer">添付ファイル</a>
        </div>
    @endif
    @error($name)
    <div class="input_feedback">
        <span>{{ $message }}</span>
    </div>
    @enderror
</div>