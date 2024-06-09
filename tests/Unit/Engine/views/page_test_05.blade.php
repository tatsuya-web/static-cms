<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $title }}</title>
</head>
<body>
    @parts('header_nav', ['title' => $header_title, 'nav' => $nav])
    {{ $message }}
    @parts('footer', ['title' => $footer_title])
</body>
</html>