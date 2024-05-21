<x-app-layout title="新規登録 | 共通テンプレート | サイト構成">
    <header class="main_hd">
        <h1 class="main_hd_ttl mb-0">新規登録 | 共通テンプレート</h1>
        <ol class="main_hd_pnkz">
            <li><a href="{{ route('app.site_tree.index') }}">HOME</a></li>
            <li><a href="{{ route('app.template.index') }}">テンプレート</a></li>
            <li>新規登録</li>
        </ol>
    </header>
    <div class="main_cnt">
        <form action="{{ route('app.template.common.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="grid">
                <div class="col-12">
                    <div class="card">
                        <h2 class="card_ttl mb-3">新規登録</h2>
                        <div class="grid">
                            <div class="col-12">
                                <div class="input @error('show_name') -invalid @enderror">
                                    <label class="input_ttl -req">テンプレート表示名</label>
                                    <input type="text" name="show_name" id="" placeholder="テンプレート表示名を入力してください" required value="{{ old('show_name') }}">
                                    @error('show_name')
                                    <div class="input_feedback">
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>
                                <div class="input @error('name') -invalid @enderror">
                                    <label class="input_ttl -req">テンプレート名</label>
                                    <input type="text" name="name" id="" placeholder="テンプレート名を入力してください" required value="{{ old('name') }}">
                                    @error('name')
                                    <div class="input_feedback">
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>
                                <div class="input @error('description') -invalid @enderror">
                                    <label class="input_ttl">説明</label>
                                    <input type="text" name="description" id="" placeholder="">
                                    @error('description')
                                    <div class="input_feedback">
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>
                                <div class="input @error('format') -invalid @enderror">
                                    <label class="input_ttl -req">入力フォーマットファイル</label>
                                    <input type="file" name="format" accept=".json" required>
                                    @error('format')
                                    <div class="input_feedback">
                                        <span>{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex mt-3 -end">
                            <div class="bgroup">
                                <button type="submit" class="btn">登録する</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>