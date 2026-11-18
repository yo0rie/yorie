@extends('admin.layout')

@section('title', 'Dashboard Admin — Jeli Store')

@section('content')
<main class="container admin-main">
    <div class="admin-welcome">
        <div>
            <span class="eyebrow"><span class="eyebrow-dot"></span> Ringkasan toko</span>
            <h1>Hai, {{ $admin->name }} <span>♡</span></h1>
            <p>Senang melihat toko kamu tumbuh. Ini ringkasan Jeli Store hari ini.</p>
        </div>
        <div class="admin-welcome-actions">
            <a class="admin-store-link" href="{{ route('admin.produk.index') }}">Kelola produk <span>↗</span></a>
            <a class="admin-store-link admin-store-secondary" href="{{ route('home') }}">Lihat toko <span>↗</span></a>
        </div>
    </div>

    <section class="admin-stats" aria-label="Ringkasan data toko">
        <article class="admin-stat">
            <span class="admin-stat-icon">✿</span>
            <span class="admin-stat-label">Total produk</span>
            <strong>{{ number_format($jumlahProduk, 0, ',', '.') }}</strong>
            <small>produk di katalog</small>
        </article>
        <article class="admin-stat">
            <span class="admin-stat-icon">✧</span>
            <span class="admin-stat-label">Kategori</span>
            <strong>{{ number_format($jumlahKategori, 0, ',', '.') }}</strong>
            <small>kategori aktif</small>
        </article>
        <article class="admin-stat">
            <span class="admin-stat-icon">♡</span>
            <span class="admin-stat-label">Pesanan</span>
            <strong>{{ number_format($jumlahPesanan, 0, ',', '.') }}</strong>
            <small>pesanan tercatat</small>
        </article>
        <article class="admin-stat admin-stat-highlight">
            <span class="admin-stat-icon">Rp</span>
            <span class="admin-stat-label">Penjualan selesai</span>
            <strong>Rp{{ number_format($totalPenjualan, 0, ',', '.') }}</strong>
            <small>dari pesanan berstatus selesai</small>
        </article>
    </section>

    <section class="admin-note">
        <span>✿</span>
        <div><strong>Kelola katalog dengan mudah</strong><p>Tambah produk, ubah detail dan foto, atau nonaktifkan produk dari satu halaman.</p></div>
        <a href="{{ route('admin.produk.index') }}">Buka produk <span>→</span></a>
    </section>
</main>
@endsection
