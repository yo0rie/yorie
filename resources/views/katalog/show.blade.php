@extends('layouts.app')

@section('title', $produk->nama . ' - Jeli Store')

@section('content')
<section class="detail-section">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span>/</span>
            <a href="{{ route('home', ['kategori' => $produk->kategori->slug]) }}">{{ $produk->kategori->nama }}</a>
            <span>/</span>
            <span>{{ $produk->nama }}</span>
        </nav>
        <div class="detail-grid">
            <div class="detail-image">
                <span class="detail-visual visual-{{ $produk->kategori_id }}">{{ substr($produk->nama, 0, 1) }}</span>
            </div>
            <div class="detail-copy">
                <span class="eyebrow">{{ $produk->kategori->nama }}</span>
                <h1>{{ $produk->nama }}</h1>
                <strong class="detail-price">Rp{{ number_format($produk->harga, 0, ',', '.') }}</strong>
                <p>{{ $produk->deskripsi }}</p>
                <div class="stock-info">
                    <span class="{{ $produk->stok > 0 ? 'in-stock' : 'out-stock' }}"></span>
                    {{ $produk->stok > 0 ? 'Stok tersedia: ' . $produk->stok : 'Stok habis' }}
                </div>
                <div class="detail-actions">
                    <button class="primary-button" type="button" {{ $produk->stok < 1 ? 'disabled' : '' }}>Tambah ke keranjang</button>
                </div>
            </div>
        </div>
    </div>
</section>

@if($produkTerkait->count())
<section class="product-section related">
    <div class="container">
        <div class="section-heading">
            <div><span class="eyebrow">Pilihan lain</span><h2>Produk terkait</h2></div>
        </div>
        <div class="product-grid">
            @foreach($produkTerkait as $item)
                <article class="product-card">
                    <a class="product-image" href="{{ route('produk.show', $item->slug) }}">
                        <span class="product-visual visual-{{ $item->kategori_id }}"><span>{{ substr($item->nama, 0, 1) }}</span></span>
                    </a>
                    <div class="product-info">
                        <span class="product-category">{{ $item->kategori->nama }}</span>
                        <h3><a href="{{ route('produk.show', $item->slug) }}">{{ $item->nama }}</a></h3>
                        <div class="product-bottom"><strong>Rp{{ number_format($item->harga, 0, ',', '.') }}</strong><a class="add-button" href="{{ route('produk.show', $item->slug) }}">→</a></div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
