<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#fff8f8">
    <title>Masuk — Jeli Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="auth-page">
    <main class="auth-shell">
        <a class="brand auth-brand" href="{{ route('home') }}" aria-label="Kembali ke Jeli Store">
            <span class="brand-mark"><img src="{{ asset('images/logo-photo.jfif') }}" alt=""></span>
            <span class="brand-name">jeli<span>store</span></span>
        </a>

        <section class="auth-card">
            <div class="auth-art">
                <img src="{{ asset('images/hero-cookie.jfif') }}" alt="" class="auth-photo">
                <span class="auth-art-label">a little joy<br>for your day <i>♡</i></span>
                <span class="auth-art-flower">✿</span>
            </div>
            <div class="auth-form-panel">
                <a class="auth-back" href="{{ route('home') }}">← <span>Kembali ke toko</span></a>
                <span class="eyebrow"><span class="eyebrow-dot"></span> Senang melihatmu lagi</span>
                <h1>Masuk ke<br><em>Jeli Store.</em></h1>
                <p class="auth-intro">Masuk untuk melanjutkan hari yang lebih ceria.</p>

                @if($errors->any())
                    <div class="auth-alert" role="alert">{{ $errors->first() }}</div>
                @endif

                <form class="auth-form" action="{{ route('login.store') }}" method="post">
                    @csrf
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="username" required autofocus>

                    <label for="password">Kata sandi</label>
                    <input id="password" type="password" name="password" placeholder="Masukkan kata sandi" autocomplete="current-password" required>

                    <label class="remember-option">
                        <input type="checkbox" name="remember" value="1">
                        <span>Ingat saya di perangkat ini</span>
                    </label>

                    <button class="primary-button auth-submit" type="submit">Masuk <span>↗</span></button>
                </form>

                <p class="auth-footnote">Belum punya akses? Hubungi admin koperasi sekolah.</p>
            </div>
        </section>
        <p class="auth-copyright">© {{ date('Y') }} Jeli Store · Made with <span>♡</span></p>
    </main>
</body>
</html>
