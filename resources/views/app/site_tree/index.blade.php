<x-app-layout title="サイト構成">

    <header class="main_hd">
        <h1 class="main_hd_ttl mb-0">サイト構成</h1>
        <ol class="main_hd_pnkz">
            <li><a href="/">HOME</a></li>
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
                                <span class="folder -open"><i class="fa-solid fa-folder"></i>サイトトップ<a href="/site_tree_add.html"><i class="fa-solid fa-plus"></i></a></span>
                                <ul>
                                    <li><a href="site_tree_edit.html" class="file"><i class="fa-brands fa-html5"></i>index.html</a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></li>
                                    <li>
                                        <span class="folder -open"><i class="fa-solid fa-folder"></i>about<a href="/site_tree_add.html"><i class="fa-solid fa-plus"></i><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></a></span>
                                        <ul>
                                            <li><a href="site_tree_edit.html" class="file"><i class="fa-brands fa-html5"></i>index.html</a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></li>
                                            <li><a href="/data/_index_240201.html" class="file"><i class="fa-brands fa-html5"></i>_index_240201.html</a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <span class="folder -open"><i class="fa-solid fa-folder"></i>service<a href="/site_tree_add.html"><i class="fa-solid fa-plus"></i></a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></span>
                                        <ul>
                                            <li><a href="site_tree_edit.html" class="file"><i class="fa-brands fa-html5"></i>index.html</a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <span class="folder -open"><i class="fa-solid fa-folder"></i>recruit<a href="/contents/recruit_new.html"><i class="fa-solid fa-plus"></i></a><button class="ibtn" onclick="delete_dialog(1)"></button></span>
                                        <ul>
                                            <li><a href="/contents/recruit_edit.html" class="file"><i class="fa-brands fa-html5"></i>r_01001939.html</a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <span class="folder -open"><i class="fa-solid fa-folder"></i>cmn<a href="/site_tree_add.html"><i class="fa-solid fa-plus"></i></a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></span>
                                        <ul>
                                            <li><a href="site_tree_edit.html" class="file"><i class="fa-solid fa-file"></i>readme.txt</a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></li>
                                            <li>
                                                <span class="folder -open"><i class="fa-solid fa-folder"></i>cmn<a href="/site_tree_add.html"><i class="fa-solid fa-plus"></i></a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></span>
                                                <ul>
                                                    <li>
                                                        <span class="folder -open"><i class="fa-solid fa-folder"></i>js<a href="/site_tree_add.html"><i class="fa-solid fa-plus"></i></a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></span>
                                                        <ul>
                                                            <li><a href="site_tree_edit.html" class="file"><i class="fa-brands fa-square-js"></i>script.js</a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <span class="folder -open"><i class="fa-solid fa-folder"></i>css<a href="/site_tree_add.html"><i class="fa-solid fa-plus"></i></a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></span>
                                                        <ul>
                                                            <li><a href="site_tree_edit.html" class="file"><i class="fa-brands fa-css3-alt"></i>style.css</a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <span class="folder -open"><i class="fa-solid fa-folder"></i>img<a href="/site_tree_add.html"><i class="fa-solid fa-plus"></i></a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></span>
                                                        <ul>
                                                            <li><a href="site_tree_edit.html" class="file"><i class="fa-solid fa-image"></i>img.ipg</a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></li>
                                                            <li><a href="site_tree_edit.html" class="file"><i class="fa-solid fa-image"></i>img.svg</a><button class="ibtn" onclick="delete_dialog(1)"><i class="fa-solid fa-trash"></i></button></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
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
                <h2 class="card_ttl">XXXXの削除</h2>
                <p class="card_text">XXXXを削除してもよろしいですか？</p>
                <div class="text-end mt-3">
                    <form class="bgroup" action="" method="delete">
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
                    folders[i].addEventListener('click', function () {
                        this.classList.toggle('-open');
                        let ul = this.nextElementSibling;

                        if (ul) {
                            ul.style.display = ul.style.display === 'none' ? 'block' : 'none';
                        }
                    });

                    let ul = folders[i].nextElementSibling;

                    if (ul) {
                        ul.style.display = 'block';
                    }
                }
            });
        </script>
    </x-slot>
</x-app-layout>