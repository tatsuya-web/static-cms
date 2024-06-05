<x-app-layout title="{{ $template->name }}">

    <header class="main_hd">
        <h1 class="main_hd_ttl mb-0">{{ $template->name }}</h1>
        <ol class="main_hd_pnkz">
            <li><a href="{{ route('app.site_tree.index') }}">HOME</a></li>
            <li>{{ $template->name }}</li>
        </ol>
    </header>
    <div class="main_cnt">
        <div class="grid">
            <div class="col-12">
                <div class="card">
                    <div class="flex mt-3">
                        <div class="bgroup">
                            <a href="{{ route('app.content.create', ['template' => $template]) }}" class="btn" type="button">新規登録</a>
                        </div>
                    </div>
                    <table id="table" class="-striped responsive display nowrap" style="width: 100%">
                        <thead>
                            <tr>
                                <th data-priority="1">#</th>
                                @foreach ($template->hasIndexLabels() as $label)
                                    <th data-priority="1">{{ $label }}</th>
                                @endforeach
                                <th data-priority="1">作成者</th>
                                <th data-priority="1">作成日時</th>
                                <th data-priority="1">更新日時</th>
                                <th data-priority="1">アクション</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($template->contents as $content)
                            <tr>
                                <td>{{ $content->id }}</td>
                                @foreach ($template->hasIndexNames() as $name)
                                    <td>{{ $content->getIndexValue($name) }}</td>
                                @endforeach
                                <td>{{ $content->user->name }}</td>
                                <td>{{ $content->created_at }}</td>
                                <td>{{ $content->updated_at }}</td>
                                <td>
                                    <a href="{{ route('app.content.edit', ['template' => $template, 'content' => $content]) }}"><i class="fa-solid fa-pen"></i></a>
                                    <button class="ibtn" onclick="delete_dialog({{ $content->id }})"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <div id="dialog" class="dialog">
            <div class="card">
                <h2 class="card_ttl">管理ユーザーの削除</h2>
                <p class="card_text">管理ユーザーを削除してもよろしいですか？</p>
                <div class="text-end mt-3">
                    <form class="bgroup" action="{{ route('app.user.destroy') }}" method="post">
                        @csrf
                        @method('delete')
                        <input type="hidden" name="delete_id">
                        <button id="dialog_close" class="btn -gray" type="button">キャンセル</button>
                        <button class="btn" type="submit">削除する</button>
                    </form>
                </div>
            </div>
        </div>
        <script>
            // 削除ダイアログ
            function delete_dialog(id) {
                $('#dialog').fadeIn().css('display', 'flex');
                $('[name="delete_id"]').val(id);
            }
            $('#dialog, #dialog_close').on('click', function (e) {
                if (e.target !== e.currentTarget) return;
                $('#dialog').fadeOut();
                $('[name="delete_id"]').val('');
            });
        </script>
    </x-slot>
</x-app-layout>