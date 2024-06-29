<x-app-layout title="更新 | {{ $content->id }} | {{ $template->name }}">
    <header class="main_hd">
        <h1 class="main_hd_ttl mb-0">更新</h1>
        <ol class="main_hd_pnkz">
            <li><a href="{{ route('app.site_tree.index') }}">HOME</a></li>
            <li><a href="{{ route('app.content.index', ['template' => $template]) }}">{{ $template->name }}</a></li>
            <li>{{ $content->id }}</li>
        </ol>
    </header>
    <div class="main_cnt">
        <form action="{{ route('app.content.update', ['template' => $template, 'content' => $content]) }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="template_id" value="{{ $template->id }}">
            <div class="grid">
                <div class="col-12">
                    <div class="card">
                        <h2 class="card_ttl">更新</h2>
                        <p>
                            入力項目に必要事項を入力してくし、「更新する」ボタンをクリックすることで更新が完了します。<br>
                        </p>
                        <div class="col-12">
                            @foreach ($template->format_items as $item)
                                @include('app.content._form_item' , ['item' => $item], ['content' => $content])
                            @endforeach
                        </div>
                        <div class="flex mt-3 -end">
                            <div class="bgroup">
                                <button class="btn" type="submit">更新する</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <x-slot name="footer">
        <script>
            function filedel(_this, name, value) {
                if (confirm('削除しますか？')) {
                    // 直近の親要素を削除する
                    _this.parentNode.remove();
                    // input :hiddenでnameがnameのvalueがvalueの要素を取得する
                    const input = document.querySelector('input[name="' + name + '"][value="' + value + '"]');

                    // もしinputがあれば、削除する
                    if (input) {
                        input.remove();
                    }
                }
            }
        </script>
        </script>
    </x-slot>
</x-app-layout>