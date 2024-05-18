<x-app-layout title="サイト構成">

    <header class="main_hd">
        <h1 class="main_hd_ttl mb-0">サイト構成</h1>
        <ol class="main_hd_pnkz">
            <li><a href="{{ route('app.site_tree.index') }}">HOME</a></li>
            <li>サイト構成</li>
        </ol>
    </header>
    <div class="main_cnt">
        <div class="grid">
            <div class="col-12">
                <div class="card">
                    <h2 class="card_ttl mb-3">サイトツリー</h2>
                    <div class="tree">
                        <ul>
                            <li>
                                <span class="folder -open">
                                    <i class="fa-solid fa-folder"></i>
                                    <span class="folder_name">
                                        サイトトップ
                                    </span>
                                    <a href="{{ route('app.site_tree.create') }}">
                                        <i class="fa-solid fa-plus"></i>
                                    </a>
                                </span>
                                {{-- $treeをループさせる childrenがあれば再帰してchildrenがなくなるまで繰り返す --}}
                                <ul>
                                    @foreach($trees as $tree)
                                        @include('app.site_tree._child', ['tree' => $tree])
                                    @endforeach
                                </ul>
                                {{--<ul>
                                    <li><a href="site_tree_edit.html" class="file"><i class="fa-brands fa-html5"></i>index.html</a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></li>
                                    <li>
                                        <span class="folder -open"><i class="fa-solid fa-folder"></i>about<a href="/site_tree_add.html"><i class="fa-solid fa-plus"></i><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></a></span>
                                        <ul>
                                            <li><a href="site_tree_edit.html" class="file"><i class="fa-brands fa-html5"></i>index.html</a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></li>
                                            <li><a href="/data/_index_240201.html" class="file"><i class="fa-brands fa-html5"></i>_index_240201.html</a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></li>
                                        </ul>
                                    </li>
                                </ul>--}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <div id="dialog" class="dialog">
            <div class="card">
                <h2 class="card_ttl">ファイル/フォルダの削除</h2>
                <p class="card_text">ファイル/フォルダを削除してもよろしいですか？</p>
                <div class="text-end mt-3">
                    <form class="bgroup" action="{{ route('app.site_tree.destroy') }}" method="post">
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
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const folders = document.querySelectorAll('.folder');

                for (let i = 0; i < folders.length; i++) {
                    // folders[i] 直下の.folder_nameをクリックした時の処理
                    let folderName = folders[i].querySelector('.folder_name');
                    if(folderName) {
                        folderName.addEventListener('click', function () {
                            let parent = folders[i];
                            parent.classList.toggle('-open');
                            let ul = parent.nextElementSibling;
    
                            if (ul) {
                                ul.style.display = ul.style.display === 'none' ? 'block' : 'none';
                            }
                        });
                    }

                    let ul = folders[i].nextElementSibling;

                    if (ul) {
                        ul.style.display = 'block';
                    }
                }
            });
        </script>
    </x-slot>
</x-app-layout>