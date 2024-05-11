<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<title>{{ $title }} | {{ config('app.name') }}</title>
	<meta name="robots" content="noindex">
	<meta name="viewport" content="width=device-width">
	<meta name="format-detection" content="telephone=no">
	<link rel="icon" href="/img/cmn/logo.svg" type="image/svg+xml">
	<link rel="stylesheet" href="/css/reset.css" media="all">
	<link rel="stylesheet" href="/css/form.css" media="all">
	<link rel="stylesheet" href="/css/frame.css" media="all">
	<link rel="stylesheet" href="/css/style.css" media="all">

	<!-- font awesome -->
	<script src="https://kit.fontawesome.com/ea21566daa.js" crossorigin="anonymous"></script>

	<!-- DataTables -->
	<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" />
	<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- Torstar.js -->

    @slot('head')
</head>

<body>
	<header class="sphd">
		<a class="sphd_logo" href="{{ route('app.site_tree.index') }}" title="ネットワールド ホームページ管理システム">
			<span class="sphd_logo_ttl">staticCMS</span>
		</a>
		<button id="sphd_menu" class="sphd_menu" type="button">
			<span class="-top"></span>
			<span class="-btm"></span>
		</button>
	</header>
	<div class="wrapper">
		<aside id="side" class="side">
			<a class="side_logo" href="{{ route('app.site_tree.index') }}" title="ネットワールド ホームページ管理システム">
				<span class="sphd_logo_ttl">staticCMS</span>
			</a>
			<div class="side_cnt">
				<nav class="side_nav">
					<ul class="side_nav_list">
						<li class="-title">構成管理</li>
						<li><a href="{{ route('app.site_tree.index') }}"><i class="fa-solid fa-folder-tree"></i><span>サイト構成</span></a></li>
						<li><a href="/template.html"><i class="fa-solid fa-crop-simple"></i><span>テンプレート</span></a></li>
						<li class="-title mt-3">コンテンツ管理</li>
						<li><a href="/contents/topics_list.html"><i class="fa-solid fa-newspaper"></i><span>Topics</span></a></li>
						<li><a href="/contents/work_list.html"><i class="fa-brands fa-creative-commons-nd"></i><span>制作事例</span></a></li>
						<li><a href="/contents/recruit_list.html"><i class="fa-solid fa-briefcase"></i><span>採用情報</span></a></li>
						<li class="-title mt-3">設定</li>
						<li><a href="/user_list.html"><i class="fa-solid fa-address-card"></i><span>管理ユーザー</span></a></li>
						<li><a href="/log.html"><i class="fa-solid fa-clock"></i><span>ログ</span></a></li>
						<li><a href="/"><i class="fa-solid fa-right-from-bracket"></i><span>ログアウト</span></a></li>
					</ul>
				</nav>
			</div>
		</aside>
		<main class="main">
            {{ $slot }}
		</main>
		<footer class="footer">
			<div class="footer_copy">© <a class="-noicon" href="https://www.networld-jp.net/" target="_blank">NETWORLD INC</a>. All Rights Reserved.</div>
			<dl class="footer_ver">
				<dt>Version</dt>
				<dd>1.0.0</dd>
			</dl>
		</footer>
	</div>
    <script>
		// DataTables
		new DataTable('#table', {
			bStateSave: true,
			pageLength: 25,
			language: {
				url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/ja.json',
			},
		});
	</script>
	<script src="/js/common.js"></script>

    @slot('footer')
</body>

</html>