<x-app-layout title="テンプレート">

    <header class="main_hd">
        <h1 class="main_hd_ttl mb-0">テンプレート</h1>
        <ol class="main_hd_pnkz">
            <li><a href="{{ route('app.site_tree.index') }}">HOME</a></li>
            <li>テンプレート</li>
        </ol>
    </header>
    <div class="main_cnt">
        <div class="grid">
            <div class="col-12">
                <div class="card mb-3">
                    <h2 class="card_ttl">共通テンプレート</h2>
                    <div class="flex mt-3">
                        <div class="bgroup">
                            <a href="{{ route('app.template.common.create') }}" class="btn" type="button">新規登録</a>
                        </div>
                    </div>
                    <table id="table" class="-striped responsive display" style="width: 100%">
                        <thead>
                            <tr>
                                <th data-priority="1">#</th>
                                <th data-priority="1">タイトル</th>
                                <th data-priority="1">説明</th>
                                <th data-priority="1">作成者</th>
                                <th data-priority="1">作成日時</th>
                                <th data-priority="1">更新日時</th>
                                <th data-priority="1">アクション</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commons as $template)
                            <tr>
                                <td>{{ $template->id }}</td>
                                <td>{{ $template->name }}</td>
                                <td>{{ $template->description }}</td>
                                <td><a href="{{ route('app.user.edit', ['user' => $template->user]) }}" target="_blank" rel="noopener noreferrer">{{ $template->user->id }}</a></td>
                                <td>{{ $template->created_at }}</td>
                                <td>{{ $template->updated_at }}</td>
                                <td>
                                    <a href="{{ route('app.template.common.edit', ['template' => $template]) }}"><i class="fa-solid fa-pen"></i></a>
                                    <button class="ibtn" onclick="delete_dialog({{ $template->id }})"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card">
                    <h2 class="card_ttl">ページテンプレート</h2>
                    <div class="flex mt-3">
                        <div class="bgroup">
                            <a href="{{ route('app.template.page.create') }}" class="btn" type="button">新規登録</a>
                        </div>
                    </div>
                    <table id="table" class="-striped responsive display" style="width: 100%">
                        <thead>
                            <tr>
                                <th data-priority="1">#</th>
                                <th data-priority="1">タイトル</th>
                                <th data-priority="1">説明</th>
                                <th data-priority="1">作成者</th>
                                <th data-priority="1">作成日時</th>
                                <th data-priority="1">更新日時</th>
                                <th data-priority="1">アクション</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pages as $template)
                            <tr>
                                <td>{{ $template->id }}</td>
                                <td>{{ $template->name }}</td>
                                <td>{{ $template->description }}</td>
                                <td><a href="{{ route('app.user.edit', ['user' => $template->user]) }}" target="_blank" rel="noopener noreferrer">{{ $template->user->id }}</a></td>
                                <td>{{ $template->created_at }}</td>
                                <td>{{ $template->updated_at }}</td>
                                <td>
                                    <a href="{{ route('app.template.page.edit', ['template' => $template]) }}"><i class="fa-solid fa-pen"></i></a>
                                    <button class="ibtn" onclick="delete_dialog({{ $template->id }})"><i class="fa-solid fa-trash"></i></button>
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
                <h2 class="card_ttl">テンプレートの削除</h2>
                <p class="card_text">テンプレートを削除してもよろしいですか？</p>
                <div class="text-end mt-3">
                    <form class="bgroup" action="{{ route('app.template.destroy') }}" method="post">
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