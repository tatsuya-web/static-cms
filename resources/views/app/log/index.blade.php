<x-app-layout title="ログ">

    <header class="main_hd">
        <h1 class="main_hd_ttl mb-0">ログ</h1>
        <ol class="main_hd_pnkz">
            <li><a href="{{ route('app.site_tree.index') }}">HOME</a></li>
            <li>ログ</li>
        </ol>
    </header>
    <div class="main_cnt">
        <div class="grid">
            <div class="col-12">
                <div class="card">
                    <table id="table" class="-striped responsive display" style="width: 100%">
                        <thead>
                            <tr>
                                <th data-priority="1">#</th>
                                <th data-priority="1">パス</th>
                                <th data-priority="1">メソッド</th>
                                <th data-priority="1">ステータス</th>
                                <th data-priority="1">ユーザーエージェント</th>
                                <th data-priority="1">IPアドレス</th>
                                <th data-priority="1">日時</th>
                                <th data-priority="1">管理ユーザー</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>{{ $log->path }}</td>
                                <td>{{ $log->method }}</td>
                                <td>{{ $log->response_status }}</td>
                                <td>{{ $log->user_agent }}</td>
                                <td>{{ $log->ip_address }}</td>
                                <td>{{ $log->created_at }}</td>
                                <td>
                                    @isset($log->user)
                                    <a href="{{ route('app.user.edit', ['user' => $log->user]) }}" target="_blank" rel="noopener noreferrer">{{ $log->user->id }}</a>
                                    @endisset
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>