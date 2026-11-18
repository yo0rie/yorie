@extends('admin.layout')

@section('title', 'Kelola Produk — Jeli Store')

@section('content')
<main class="container admin-main">
    <div class="admin-welcome">
        <div>
            <span class="eyebrow"><span class="eyebrow-dot"></span> Kelola katalog</span>
            <h1>Produk kamu <span>♡</span></h1>
            <p>Atur nama, harga, stok, foto, dan detail produk di sini.</p>
        </div>
        <a class="admin-add-button" href="{{ route('admin.produk.create') }}"><span>＋</span> Tambah produk</a>
    </div>

    @if(session('status'))
        <div class="admin-flash" role="status">{{ session('status') }}</div>
    @endif
    @if($errors->has('produk'))
        <div class="admin-flash admin-flash-error" role="alert">{{ $errors->first('produk') }}</div>
    @endif

    <section class="admin-product-panel">
        <div class="admin-product-toolbar">
            <div><strong>Daftar produk</strong><span>{{ $produk->total() }} produk</span></div>
            <form class="admin-search" action="{{ route('admin.produk.index') }}" method="get">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama produk..." aria-label="Cari produk">
                <button type="submit">Cari</button>
            </form>
        </div>

        @if($produk->count())
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produk as $item)
                            <tr>
                                <td>
                                    <div class="admin-product-cell">
                                        @if($item->gambar)
                                            <img class="admin-product-thumb" src="{{ asset('storage/' . $item->gambar) }}" alt="">
                                        @else
                                            <span class="admin-product-thumb admin-product-placeholder">{{ mb_strtoupper(mb_substr($item->nama, 0, 1)) }}</span>
                                        @endif
                                        <span><strong>{{ $item->nama }}</strong><small>{{ $item->slug }}</small></span>
                                    </div>
                                </td>
                                <td>{{ $item->kategori->nama }}</td>
                                <td>Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->stok, 0, ',', '.') }}</td>
                                <td><span class="product-status {{ $item->aktif ? 'status-active' : 'status-inactive' }}">{{ $item->aktif ? 'Aktif' : 'Nonaktif' }}</span></td>
                                <td>
                                    <div class="admin-row-actions">
                                        <a class="admin-edit-link" href="{{ route('admin.produk.edit', $item) }}">Edit</a>
                                        <form action="{{ route('admin.produk.destroy', $item) }}" method="post" onsubmit="return confirm('Hapus produk {{ $item->nama }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="admin-delete-button" type="submit">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="admin-pagination">{{ $produk->links() }}</div>
        @else
            <div class="admin-empty"><span>✿</span><strong>Belum ada produk yang cocok</strong><p>Coba kata kunci lain atau tambahkan produk baru.</p></div>
        @endif
    </section>
</main>
@endsection
