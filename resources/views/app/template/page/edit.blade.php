<x-app-layout title="編集 | ページテンプレート | サイト構成">
    <header class="main_hd">
        <h1 class="main_hd_ttl mb-0">編集</h1>
        <ol class="main_hd_pnkz">
            <li><a href="{{ route('app.site_tree.index') }}">HOME</a></li>
            <li><a href="{{ route('app.template.index') }}">テンプレート</a></li>
            <li>編集</li>
        </ol>
    </header>
    <div class="main_cnt">
        <div class="grid">
            <div class="col-12">
                <div class="card">
                    <form action="{{ route('app.template.page.update', ['template' => $template]) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <h2 class="card_ttl mb-3">編集</h2>
                        <div class="grid">
                            <div class="col-12">
                                <div class="input @error('show_name') -invalid @enderror">
                                    <label class="input_ttl -req">テンプレート表示名</label>
                                    <input type="text" name="show_name" id="" placeholder="テンプレート表示名を入力してください" required value="{{ old('show_name', $template->show_name) }}">
                                    @error('show_name')
                                    <div class="input_feedback">
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>
                                <div class="input @error('name') -invalid @enderror">
                                    <label class="input_ttl -req">テンプレート名</label>
                                    <input type="text" name="name" id="" placeholder="テンプレート名を入力してください" required value="{{ old('name', $template->name) }}">
                                    @error('name')
                                    <div class="input_feedback">
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>
                                <div class="input">
                                    <label class="input_ttl">コンテンツ変数(単数)</label>
                                    <input type="text" placeholder="コンテンツ変数(単数)を入力してください" disabled value="{{ $template->single_value_name }}">
                                </div>
                                <div class="input">
                                    <label class="input_ttl -req">コンテンツ変数(複数形)</label>
                                    <input type="text" placeholder="コンテンツ変数(複数形)を入力してください" disabled value="{{ $template->multi_value_name }}">
                                </div>
                                <div class="input @error('description') -invalid @enderror">
                                    <label class="input_ttl">説明</label>
                                    <input type="text" name="description" id="" placeholder="" value="{{ old('description', $template->description) }}">
                                    @error('description')
                                    <div class="input_feedback">
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>
                                <div class="input @error('format') -invalid @enderror">
                                    <label class="input_ttl">入力フォーマットファイル</label>
                                    <input type="file" name="format" accept=".json">
                                    @error('format')
                                    <div class="input_feedback">
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>
                                <h2 class="card_ttl mt-6">入力フォーマットファイルをダウンロード</h2>
                                <p class="card_text mt-1">
                                    @if($template->format)
                                        <a href="{{ route('app.media.download', ['media' => $template->format]) }}" target="_blank">{{ $template->format->name }}</a>
                                    @else
                                        入力フォーマットファイルがアップロードされていません
                                    @endif
                                </p>
                                @if($template->format && $template->is_valided_format)
                                    <h2 class="card_ttl mt-6">設定項目一覧</h2>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>表示名</th>
                                                <th>項目名</th>
                                                <th>タイプ</th>
                                                <th>必須</th>
                                                <th>選択肢</th>
                                                <th>許容値</th>
                                                <th>一覧表示(管理画面)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @include('app.template.page._items', ['items' => $template->format_items])
                                        </tbody>
                                    </table>
                                @else
                                    <p style="color: rgb(225, 36, 36); font-weight: bold; margin-top: 1rem;">設定項目が間違っている可能性があります。入力フォーマットファイルを確認してください。</p>
                                @endif
                                <div class="input @error('src') -invalid @enderror">
                                    <label class="input_ttl">ソースファイル</label>
                                    <input type="file" name="src" accept=".html">
                                    @error('src')
                                    <div class="input_feedback">
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>
                                <h2 class="card_ttl mt-6">ソースファイルをダウンロード</h2>
                                <p class="card_text mt-1">
                                    @if($template->src)
                                        <a href="{{ route('app.media.download', ['media' => $template->src]) }}" target="_blank">{{ $template->src->name }}</a>
                                    @else
                                        ソースファイルがアップロードされていません
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex mt-3 -end">
                            <div class="bgroup">
                                <button type="submit" class="btn">更新する</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>