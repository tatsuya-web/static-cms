<x-app-layout title="追加 | サイト構成">
    <header class="main_hd">
        <h1 class="main_hd_ttl mb-0">追加 | サイト構成</h1>
        <ol class="main_hd_pnkz">
            <li><a href="{{ route('app.site_tree.index') }}">HOME</a></li>
            <li>追加 | サイト構成</li>
        </ol>
    </header>
    <div class="main_cnt">
        <form action="{{ route('app.site_tree.store', ['tree' => $tree]) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="grid">
                <div class="col-12">
                    <div class="card">
                        <h2 class="card_ttl">追加</h2>
                        <div class="col-12">
                            <div class="input @error('type') -invalid @enderror">
                                <label class="input_ttl -req">コンテンツ種別</label>
                                <select required name="type">
                                    <option>選択してください</option>
                                    @foreach(\App\Enums\TreeType::cases() as $type)
                                        <option value="{{ $type->value }}" @if($type->value == old('type')) selected @endif>{{ $type->getNameJa() }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                <div class="input_feedback">
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                            <div class="input @error('name') -invalid @enderror">
                                <label class="input_ttl">フォルダー名</label>
                                <input type="text" placeholder="フォルダー名を入力してください" name="name" value="{{ old('name') }}">
                                @error('name')
                                <div class="input_feedback">
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                            <div class="input @error('file') -invalid @enderror">
                                <label class="input_ttl">ファイル</label>
                                <input type="file" name="file" accept=".html,.css,.js,.xml,.txt,.jpg,.jpeg,.png,.gif,.svg,.pdf,.zip,.rar,.tar,.gz,.mp4,.mp3,.mov,.avi,.wmv,.flv,.webm">
                                @error('file')
                                <div class="input_feedback">
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="flex mt-3 -end">
                            <div class="bgroup">
                                <button type="submit" class="btn">追加する</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>