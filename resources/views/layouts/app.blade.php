<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Jeli Store')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="announcement">Gratis ongkir untuk belanja minimal Rp500.000</div>
    <header class="header">
        <div class="container header-inner">
            <a class="brand" href="{{ route('home') }}" aria-label="Jeli Store">
                <span class="brand-mark">J</span>
                <span>Jeli Store</span>
            </a>
            <form class="search" action="{{ route('home') }}" method="get">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari produk..." aria-label="Cari produk">
                <button type="submit" aria-label="Cari">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m21 21-4.35-4.35m1.35-5.15a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z"/></svg>
                </button>
            </form>
            <nav class="header-actions">
                <a href="{{ route('home') }}">Katalog</a>
                <button class="icon-button" type="button" aria-label="Keranjang">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3h2l2.4 11.2a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L20.5 7H6.1M10 20h.01M18 20h.01"/></svg>
                </button>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container footer-inner">
            <div>
                <a class="brand brand-light" href="{{ route('home') }}"><span class="brand-mark">J</span><span>Jeli Store</span></a>
                <p>Produk kebutuhan sekolah pilihan untuk siswa, guru, dan koperasi.</p>
            </div>
            <p>&copy; {{ date('Y') }} Jeli Store</p>
        </div>
    </footer>
</body>
</html>
