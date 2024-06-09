<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $title }}</title>
</head>
<body>
    @isset($message)
    {{ $message }}
    @endisset
</body>
</html>