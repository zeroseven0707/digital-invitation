<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invitation->bride_name }} & {{ $invitation->groom_name }} - Undangan Pernikahan</title>
    <meta name="description" content="Undangan pernikahan {{ $invitation->bride_name }} dan {{ $invitation->groom_name }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $invitation->bride_name }} & {{ $invitation->groom_name }}">
    <meta property="og:description" content="Undangan pernikahan {{ $invitation->bride_name }} dan {{ $invitation->groom_name }}">
    @if($invitation->galleries->isNotEmpty())
    <meta property="og:image" content="{{ asset('storage/' . $invitation->galleries->first()->photo_path) }}">
    @endif

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="{{ $invitation->bride_name }} & {{ $invitation->groom_name }}">
    <meta property="twitter:description" content="Undangan pernikahan {{ $invitation->bride_name }} dan {{ $invitation->groom_name }}">
    @if($invitation->galleries->isNotEmpty())
    <meta property="twitter:image" content="{{ asset('storage/' . $invitation->galleries->first()->photo_path) }}">
    @endif

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>💍</text></svg>">
</head>
<body>
    {!! $renderedTemplate !!}
</body>
</html>
