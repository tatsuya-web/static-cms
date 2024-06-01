@props([
    'label' => '',
    'name' => '',
    'placeholder' => '',
    'required' => false,
    'value' => null,
])
<div class="input @error($name) -invalid @enderror">
<label class="input_ttl @if($required) -req @endif">{{ $label }}</label>
<textarea name="{{ $name }}" id="ID__{{ $name }}" placeholder="{{ $placeholder }}" @if($required) required @endif>{{ $value }}</textarea>
@error($name)
<div class="input_feedback">
    <span>{{ $message }}</span>
</div>
@enderror
</div>
<script>
    tinymce.init({
        selector: "#ID__{{ $name }}",
        height: 500,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
    });
</script>