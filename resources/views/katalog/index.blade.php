@extends('layouts.app')

@section('title', 'Katalog - Jeli Store')

@section('content')
<section class="hero">
    <div class="container hero-inner">
        <div class="hero-copy">
            <span class="eyebrow">Koperasi Sekolah</span>
            <h1>Kebutuhan sekolah,<br><em>lebih mudah.</em></h1>
            <p>Temukan perlengkapan terbaik untuk belajar, berkreasi, dan beraktivitas setiap hari.</p>
            <a class="primary-button" href="#koleksi">Belanja sekarang</a>
        </div>
        <div class="hero-art" aria-hidden="true">
            <div class="shape shape-one"></div>
            <div class="shape shape-two"></div>
            <div class="bag">
                <span class="bag-handle"></span>
                <span class="bag-star">★</span>
            </div>
        </div>
    </div>
</section>

<section class="category-section" id="koleksi">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Belanja berdasarkan</span>
                <h2>Kategori pilihan</h2>
            </div>
        </div>
        <div class="category-grid">
            <a class="category-card {{ !$kategoriDipilih ? 'active' : '' }}" href="{{ route('home') }}">
                <span class="category-icon">✦</span>
                <span>Semua</span>
            </a>
            @foreach($kategori as $item)
                <a class="category-card {{ $kategoriDipilih === $item->slug ? 'active' : '' }}" href="{{ route('home', ['kategori' => $item->slug]) }}">
                    <span class="category-icon">
                        {{ match($item->nama) {'Pakaian'=>'T','Alat Tulis'=>'A','Aksesoris'=>'B','Makanan'=>'M', default=>'P'} }}
                    </span>
                    <span>{{ $item->nama }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="product-section">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Pilihan terbaik</span>
                <h2>Produk terbaru</h2>
            </div>
            @if($kategoriDipilih)
                <a class="text-link" href="{{ route('home') }}">Tampilkan semua</a>
            @endif
        </div>

        @if($produk->count())
            <div class="product-grid">
                @foreach($produk as $item)
                    <article class="product-card">
                        <a class="product-image" href="{{ route('produk.show', $item->slug) }}">
                            <span class="product-visual visual-{{ $item->kategori_id }}">
                                <span>{{ substr($item->nama, 0, 1) }}</span>
                            </span>
                            @if($item->stok <= 3)
                                <span class="stock-badge">Terbatas</span>
                            @endif
                        </a>
                        <div class="product-info">
                            <span class="product-category">{{ $item->kategori->nama }}</span>
                            <h3><a href="{{ route('produk.show', $item->slug) }}">{{ $item->nama }}</a></h3>
                            <div class="product-bottom">
                                <strong>Rp{{ number_format($item->harga, 0, ',', '.') }}</strong>
                                <a class="add-button" href="{{ route('produk.show', $item->slug) }}" aria-label="Lihat {{ $item->nama }}">→</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="pagination">{{ $produk->links() }}</div>
        @else
            <div class="empty-state">
                <span>⌕</span>
                <h3>Produk tidak ditemukan</h3>
                <p>Coba kata kunci atau kategori lain.</p>
            </div>
        @endif
    </div>
</section>
@endsection
