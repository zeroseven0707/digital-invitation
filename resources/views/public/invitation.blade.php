<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invitation->bride_name }} & {{ $invitation->groom_name }} - Undangan Pernikahan</title>
    <meta name="description" content="Undangan pernikahan {{ $invitation->bride_name }} dan {{ $invitation->groom_name }}. Kami mengundang Anda untuk berbagi kebahagiaan di hari istimewa kami.">

    <!-- Canonical URL -->
    <meta property="og:url" content="{{ url()->current() }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="nikahin - Undangan Digital">
    <meta property="og:title" content="Undangan Pernikahan {{ $invitation->bride_name }} & {{ $invitation->groom_name }}">
    <meta property="og:description" content="Kami mengundang Anda untuk berbagi kebahagiaan di hari istimewa kami. {{ $invitation->bride_name }} & {{ $invitation->groom_name }}">
    @if($invitation->galleries->isNotEmpty())
    <meta property="og:image" content="{{ asset('storage/' . $invitation->galleries->first()->photo_path) }}">
    <meta property="og:image:secure_url" content="{{ asset('storage/' . $invitation->galleries->first()->photo_path) }}">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Foto {{ $invitation->bride_name }} & {{ $invitation->groom_name }}">
    @else
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta property="og:image:secure_url" content="{{ asset('images/logo.png') }}">
    <meta property="og:image:alt" content="nikahin - Undangan Digital">
    @endif

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@nikahin">
    <meta name="twitter:title" content="Undangan Pernikahan {{ $invitation->bride_name }} & {{ $invitation->groom_name }}">
    <meta name="twitter:description" content="Kami mengundang Anda untuk berbagi kebahagiaan di hari istimewa kami. {{ $invitation->bride_name }} & {{ $invitation->groom_name }}">
    @if($invitation->galleries->isNotEmpty())
    <meta name="twitter:image" content="{{ asset('storage/' . $invitation->galleries->first()->photo_path) }}">
    @else
    <meta name="twitter:image" content="{{ asset('images/logo.png') }}">
    @endif

    <!-- WhatsApp Specific -->
    <meta property="og:locale" content="id_ID">

    <!-- Additional Meta Tags -->
    <meta name="robots" content="index, follow">
    <meta name="author" content="nikahin">
    <meta name="theme-color" content="#d4af37">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
</head>
<body>
    {!! $renderedTemplate !!}
</body>
</html>
