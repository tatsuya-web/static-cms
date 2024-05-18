<x-app-layout title="新規登録 | 管理ユーザー">
    <header class="main_hd">
        <h1 class="main_hd_ttl mb-0">新規登録 | 管理ユーザー</h1>
        <ol class="main_hd_pnkz">
            <li><a href="{{ route('app.site_tree.index') }}">HOME</a></li>
            <li><a href="{{ route('app.user.index') }}">管理ユーザー</a></li>
            <li>新規登録 | 管理ユーザー</li>
        </ol>
    </header>
    <div class="main_cnt">
        <form action="{{ route('app.user.store') }}" method="post">
            @csrf
            <div class="grid">
                <div class="col-6">
                    <div class="card">
                        <h2 class="card_ttl">新規登録</h2>
                        <p>
                            入力項目に必要事項を入力してくし、「登録する」ボタンをクリックすることで新規登録が完了します。<br>
                        </p>
                        <div class="col-12">
                            <div class="input @error('name') -invalid @enderror">
                                <label class="input_ttl -req">ユーザー名</label>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="ユーザー名を入力してください" required>
                                @error('name')
                                <div class="input_feedback">
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                            <div class="input @error('email') -invalid @enderror"> 
                                <label class="input_ttl -req">メールアドレス</label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="emailを入力してください" required>
                                @error('email')
                                <div class="input_feedback">
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                            <div class="input @error('role') -invalid @enderror">
                                <label class="input_ttl -req">権限</label>
                                <select name="role">
                                    <option>選択してください</option>
                                    @foreach(\App\Enums\Role::cases() as $role)
                                        <option value="{{ $role }}" @if(old('role') == $role) selected @endif>{{ $role->getNameJa() }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                <div class="input_feedback">
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                            <div class="input @error('password') -invalid @enderror">
                                <label class="input_ttl">パスワード</label>
                                <input type="password" name="password">
                                @error('password')
                                <div class="input_feedback">
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                            <div class="input">
                                <label class="input_ttl">パスワード(確認)</label>
                                <input type="password" name="password_confirmation">
                            </div>
                        </div>
                        <div class="flex mt-3 -end">
                            <div class="bgroup">
                                <button class="btn" type="submit">登録する</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>