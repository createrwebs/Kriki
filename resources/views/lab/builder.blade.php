<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>Design workspace · Krikkit</title>
    <x-layouts.partials.fonts />
    @vite('resources/js/builder/builder.jsx')
</head>
<body>
    <script type="application/json" id="builder-bootstrap">{!! json_encode($bootstrap, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!}</script>
    <div id="builder-root"><p style="padding:32px">Loading your design workspace…</p></div>
    <noscript>This design workspace needs JavaScript. Your saved drafts remain on your account.</noscript>
</body>
</html>
