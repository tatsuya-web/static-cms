@props([
    'tree' => null
])

{{--<span class="folder -open"><i class="fa-solid fa-folder"></i>cmn<a href="/site_tree_add.html"><i class="fa-solid fa-plus"></i></a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></span>--}}
<li>
    @if($tree->is_folder)
    <span class="folder -open">
        <i class="fa-solid fa-folder"></i>
        <span class="folder_name">
            {{ $tree->name }}
        </span>
        <a href="{{ route('app.site_tree.create', ['tree' => $tree]) }}">
            <i class="fa-solid fa-plus"></i>
        </a>
        <button class="ibtn" onclick="delete_dialog({{ $tree->id }})">
            <i class="fa-solid fa-trash"></i>
        </button>
    </span>
    @endif
    @if($tree->is_file)
        <a href="{{ route('app.site_tree.edit', ['tree' => $tree]) }}" class="file">
            <i class="
            @if ($tree->media->mime == 'text/html')
            fa-brands fa-html5
            @elseif ($tree->media->mime == 'text/css')
            fa-brands fa-css3
            @elseif ($tree->media->mime == 'text/javascript')
            fa-brands fa-square-js
            @elseif ($tree->media->mime == 'image/jpeg' || $tree->media->mime == 'image/png' || $tree->media->mime == 'image/gif')
            fa-solid fa-image
            @elseif ($tree->media->mime == 'application/pdf')
            fa-brands fa-file-pdf
            @elseif ($tree->media->mime == 'application/msword' || $tree->media->mime == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
            fa-brands fa-file-word
            @elseif ($tree->media->mime == 'application/vnd.ms-excel' || $tree->media->mime == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            fa-brands fa-file-excel
            @elseif ($tree->media->mime == 'application/vnd.ms-powerpoint' || $tree->media->mime == 'application/vnd.openxmlformats-officedocument.presentationml.presentation')
            fa-brands fa-file-powerpoint
            @else
            fa-solid fa-file
            @endif
            "></i>
            {{ $tree->name }}
        </a>
        <button class="ibtn" onclick="delete_dialog({{ $tree->id }})">
            <i class="fa-solid fa-trash"></i>
        </button>
    @endif
    @if($tree->children->isNotEmpty())
        <ul>
            @foreach($tree->children as $child)
                @include('app.site_tree._child', ['tree' => $child])
            @endforeach
        </ul>
    @endif
</li>