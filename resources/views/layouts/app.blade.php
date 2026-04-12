<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO --}}
    <title>@yield('title', config('cafe-blog.site_name', 'CaféHistoire'))</title>
    <meta name="description" content="@yield('meta_description', config('cafe-blog.site_description'))">
    @hasSection('meta_keywords')
    <meta name="keywords" content="@yield('meta_keywords')">
    @endif

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('title', config('cafe-blog.site_name'))">
    <meta property="og:description" content="@yield('meta_description', config('cafe-blog.site_description'))">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    @hasSection('og_image')
    <meta property="og:image" content="@yield('og_image')">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', config('cafe-blog.site_name'))">
    <meta name="twitter:description" content="@yield('meta_description', config('cafe-blog.site_description'))">

    {{-- RSS Feed --}}
    @if(config('cafe-blog.rss_enabled'))
    <link rel="alternate" type="application/rss+xml" title="{{ config('cafe-blog.site_name') }}" href="{{ route('cafe-blog.feed') }}">
    @endif

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Lora:ital,wght@0,400;0,500;1,400&family=Source+Sans+3:wght@300;400;500&display=swap" rel="stylesheet">

    {{-- Package CSS --}}
    <link rel="stylesheet" href="{{ asset('vendor/cafe-blog/css/cafe-blog.css') }}">

    @stack('styles')

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">
</head>
<body class="cafe-blog-body">

    {{-- Navigation --}}
    @include('cafe-blog::partials.navbar')

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="cafe-alert cafe-alert--success" role="alert">
        <span>{{ session('success') }}</span>
        <button class="cafe-alert__close" aria-label="Fermer">&times;</button>
    </div>
    @endif
    @if(session('error'))
    <div class="cafe-alert cafe-alert--error" role="alert">
        <span>{{ session('error') }}</span>
        <button class="cafe-alert__close" aria-label="Fermer">&times;</button>
    </div>
    @endif

    {{-- Main Content --}}
    <main id="main-content">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('cafe-blog::partials.footer')

    {{-- Package JS --}}
    <script src="{{ asset('vendor/cafe-blog/js/cafe-blog.js') }}" defer></script>

    @stack('scripts')

</body>
</html>
