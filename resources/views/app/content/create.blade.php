<x-app-layout title="新規登録 | {{ $template->name }}">
    <header class="main_hd">
        <h1 class="main_hd_ttl mb-0">新規登録</h1>
        <ol class="main_hd_pnkz">
            <li><a href="{{ route('app.site_tree.index') }}">HOME</a></li>
            <li><a href="{{ route('app.content.index', ['template' => $template]) }}">{{ $template->name }}</a></li>
            <li>新規登録</li>
        </ol>
    </header>
    <div class="main_cnt">
        <form action="{{ route('app.user.store') }}" method="post">
            @csrf
            <div class="grid">
                <div class="col-12">
                    <div class="card">
                        <h2 class="card_ttl">新規登録</h2>
                        <p>
                            入力項目に必要事項を入力してくし、「登録する」ボタンをクリックすることで新規登録が完了します。<br>
                        </p>
                        <div class="col-12">
                            @foreach ($template->format_items as $item)
                                @include('app.content._form_item' , ['item' => $item])
                            @endforeach
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