<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $title = 'Muhammad Rayhan — Junior Web Developer & UI/UX Designer';
        $desc = 'Portofolio Muhammad Rayhan, Junior Web Developer & UI/UX Designer yang berfokus pada pengembangan website modern dan pengalaman digital yang intuitif.';
    @endphp
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $desc }}">
    <meta name="theme-color" content="#0c0c0e">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="canonical" href="{{ url('/') }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $desc }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset(config('portfolio.images.hero')) }}">
    <meta property="og:locale" content="id_ID">
    <meta name="twitter:card" content="summary_large_image">

    <script>document.documentElement.classList.add('js');</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-full focus:bg-coral focus:px-5 focus:py-3 focus:text-white">Lewati ke konten utama</a>

    <x-navbar />

    <main id="main">
        @yield('content')
    </main>

    <x-footer />
</body>
</html>
