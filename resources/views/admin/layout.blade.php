<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#fff8f8">
    <title>@yield('title', 'Admin — Jeli Store')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="admin-page">
    <header class="site-header">
        <div class="container header-inner admin-header-inner">
            <a class="brand" href="{{ route('home') }}" aria-label="Jeli Store">
                <span class="brand-mark"><img src="{{ asset('images/logo-photo.jfif') }}" alt=""></span>
                <span class="brand-name">jeli<span>store</span></span>
            </a>
            <nav class="admin-nav" aria-label="Navigasi admin">
                <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Ringkasan</a>
                <a class="{{ request()->routeIs('admin.produk.*') ? 'active' : '' }}" href="{{ route('admin.produk.index') }}">Produk</a>
            </nav>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button class="logout-button" type="submit">Keluar <span>↗</span></button>
            </form>
        </div>
    </header>

    @yield('content')
</body>
</html>
