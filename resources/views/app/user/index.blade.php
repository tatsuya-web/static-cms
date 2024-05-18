<x-app-layout title="管理ユーザー">

    <header class="main_hd">
        <h1 class="main_hd_ttl mb-0">管理ユーザー</h1>
        <ol class="main_hd_pnkz">
            <li><a href="{{ route('app.site_tree.index') }}">HOME</a></li>
            <li>管理ユーザー</li>
        </ol>
    </header>
    <div class="main_cnt">
        <div class="grid">
            <div class="col-12">
                <div class="card">
                    <p>
                        管理ユーザーには管理権限と一般権限、投稿権限の3つの権限があります。<br>
                        ・管理権限：サイト構成の管理、コンテンツの管理、管理ユーザーの管理<br>
                        ・一般権限：コンテンツの管理<br>
                        ・投稿権限：コンテンツの登録のみで登録したコンテンツのは承認されるまで非公開。
                    </p>
                    <div class="flex mt-3">
                        <div class="bgroup">
                            <a href="{{ route('app.user.create') }}" class="btn" type="button">新規登録</a>
                        </div>
                    </div>
                    <table id="table" class="-striped responsive display nowrap" style="width: 100%">
                        <thead>
                            <tr>
                                <th data-priority="1">#</th>
                                <th data-priority="1">ユーザー名</th>
                                <th data-priority="1">メールアドレス</th>
                                <th data-priority="1">権限</th>
                                <th data-priority="1">アクション</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role->getNameJa() }}</td>
                                <td>
                                    <a href="{{ route('app.user.edit', ['user' => $user]) }}"><i class="fa-solid fa-pen"></i></a>
                                    <button class="ibtn" onclick="delete_dialog({{ $user->id }})"><i class="fa-solid fa-trash"></i></button>
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