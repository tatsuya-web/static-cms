<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $title }}</title>
</head>
<body>
    @foreach ($messages as $message)
        {{ $message }}
    @endforeach
</body>
</html>