<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $siteName = setting('identity.name', config('portfolio.name', 'Muhammad Rayhan'));
        $siteRole = setting('identity.role', config('portfolio.role', 'Junior Web Developer & UI/UX Designer'));
        $title = $siteName.' — '.$siteRole;
        $desc = setting('identity.bio_summary', 'Portofolio '.$siteName.', '.$siteRole.' yang berfokus pada pengembangan website modern dan pengalaman digital yang intuitif.');
        $ogImage = asset(setting('images.hero', config('portfolio.images.hero')));
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
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:locale" content="id_ID">
    <meta name="twitter:card" content="summary_large_image">

    <script>document.documentElement.classList.add('js');</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    {{-- Global Scroll Progress Indicator --}}
    <div id="scroll-progress" class="fixed inset-x-0 top-0 z-[9999] h-[3px] origin-left scale-x-0 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600 transition-transform duration-75 ease-out" aria-hidden="true"></div>

    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-full focus:bg-coral focus:px-5 focus:py-3 focus:text-white">Lewati ke konten utama</a>

    <x-navbar />

    <main id="main">
        @yield('content')
    </main>

    <x-footer />
</body>
</html>
