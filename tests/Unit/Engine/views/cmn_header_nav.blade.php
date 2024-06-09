<header>
    <h1>{{ $title }}</h1>
    @if($nav)
    <nav>
        <ul>
            @foreach ($nav as $item)
                <li><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
            @endforeach
        </ul>
    </nav>
    @endif
</header>