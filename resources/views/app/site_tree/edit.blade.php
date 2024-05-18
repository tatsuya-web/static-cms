<x-app-layout title="編集 | サイト構成">
    <header class="main_hd">
        <h1 class="main_hd_ttl mb-0">編集 | サイト構成</h1>
        <ol class="main_hd_pnkz">
            <li><a href="{{ route('app.site_tree.index') }}">HOME</a></li>
            <li>編集 | サイト構成</li>
        </ol>
    </header>
    <div class="main_cnt">
        <form action="{{ route('app.site_tree.update', ['tree' => $tree]) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="grid">
                <div class="col-12">
                    <div class="card">
                        <h2 class="card_ttl">編集</h2>
                        <div class="col-12">
                            <div class="input ">
                                <label class="input_ttl">ファイル名</label>
                                <input type="text" placeholder="ファイル/フォルダー名を入力してください" required value="{{ $tree->name }}" disabled>
                            </div>
                            <div class="input">
                                <label class="input_ttl">コンテンツ種別</label>
                                <select disabled>
                                    <option>選択してください</option>
                                    @foreach(\App\Enums\TreeType::cases() as $type)
                                        <option value="{{ $type->value }}" @if($type->value == $tree->type->value) selected @endif>{{ $type->getNameJa() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input @error('file') -invalid @enderror">
                                <label class="input_ttl -req">ファイル</label>
                                <input type="file" name="file" accept=".html,.css,.js,.xml,.txt,.jpg,.jpeg,.png,.gif,.svg,.pdf,.zip,.rar,.tar,.gz,.mp4,.mp3,.mov,.avi,.wmv,.flv,.webm" required>
                                @error('file')
                                <div class="input_feedback">
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                                <h2 class="card_ttl mt-3">ファイルをダウンロード</h2>
                                <p class="card_text mt-1">
                                    @if($tree->media)
                                        <a href="{{ route('app.media.download', ['media' => $tree->media]) }}" target="_blank">{{ $tree->media->name }}</a>
                                    @else
                                        ファイルがアップロードされていません
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex mt-3 -end">
                            <div class="bgroup">
                                <button type="submit" class="btn">更新する</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>