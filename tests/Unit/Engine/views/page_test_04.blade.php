<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $title }}</title>
</head>
<body>
    @parts('header', ['title' => $header_title])
    {{ $message }}
</body>
</html>