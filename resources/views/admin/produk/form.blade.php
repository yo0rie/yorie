@extends('admin.layout')

@php($isEditing = $produk->exists)

@section('title', ($isEditing ? 'Edit Produk' : 'Tambah Produk') . ' — Jeli Store')

@section('content')
<main class="container admin-main">
    <div class="admin-welcome">
        <div>
            <span class="eyebrow"><span class="eyebrow-dot"></span> Kelola katalog</span>
            <h1>{{ $isEditing ? 'Edit produk' : 'Tambah produk' }} <span>♡</span></h1>
            <p>{{ $isEditing ? 'Perbarui informasi produk pilihanmu.' : 'Isi detail produk baru untuk ditampilkan di toko.' }}</p>
        </div>
        <a class="admin-store-link" href="{{ route('admin.produk.index') }}">← Kembali ke produk</a>
    </div>

    @if($errors->any())
        <div class="admin-flash admin-flash-error" role="alert">Periksa kembali data produk. Ada bagian yang perlu diperbaiki.</div>
    @endif

    <form class="admin-edit-form" action="{{ $isEditing ? route('admin.produk.update', $produk) : route('admin.produk.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        @if($isEditing)
            @method('PUT')
        @endif

        <section class="admin-form-card">
            <div class="admin-form-heading"><span class="admin-stat-icon">✿</span><div><strong>Informasi produk</strong><p>Nama, kategori, dan informasi yang dilihat pelanggan.</p></div></div>
            <div class="admin-form-grid">
                <div class="admin-field">
                    <label for="nama">Nama produk <span>*</span></label>
                    <input id="nama" name="nama" value="{{ old('nama', $produk->nama) }}" maxlength="255" required>
                    @error('nama')<small class="field-error">{{ $message }}</small>@enderror
                </div>
                <div class="admin-field">
                    <label for="slug">Slug URL <span>*</span></label>
                    <input id="slug" name="slug" value="{{ old('slug', $produk->slug) }}" maxlength="255" required>
                    <small>Gunakan huruf kecil, angka, dan tanda hubung.</small>
                    @error('slug')<small class="field-error">{{ $message }}</small>@enderror
                </div>
                <div class="admin-field admin-field-full">
                    <label for="kategori_id">Kategori <span>*</span></label>
                    <select id="kategori_id" name="kategori_id" required>
                        <option value="">Pilih kategori</option>
                        @foreach($kategori as $item)
                            <option value="{{ $item->id }}" @selected(old('kategori_id', $produk->kategori_id) == $item->id)>{{ $item->nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id')<small class="field-error">{{ $message }}</small>@enderror
                </div>
                <div class="admin-field admin-field-full">
                    <label for="deskripsi">Deskripsi <span>*</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                    @error('deskripsi')<small class="field-error">{{ $message }}</small>@enderror
                </div>
            </div>
        </section>

        <section class="admin-form-card">
            <div class="admin-form-heading"><span class="admin-stat-icon">Rp</span><div><strong>Harga dan persediaan</strong><p>Atur harga jual dan jumlah stok saat ini.</p></div></div>
            <div class="admin-form-grid">
                <div class="admin-field">
                    <label for="harga">Harga (Rupiah) <span>*</span></label>
                    <div class="input-prefix"><span>Rp</span><input id="harga" type="number" name="harga" value="{{ old('harga', $produk->harga) }}" min="0" max="4294967295" step="1" required></div>
                    @error('harga')<small class="field-error">{{ $message }}</small>@enderror
                </div>
                <div class="admin-field">
                    <label for="stok">Stok <span>*</span></label>
                    <input id="stok" type="number" name="stok" value="{{ old('stok', $produk->stok ?? 0) }}" min="0" max="4294967295" step="1" required>
                    @error('stok')<small class="field-error">{{ $message }}</small>@enderror
                </div>
            </div>
        </section>

        <section class="admin-form-card">
            <div class="admin-form-heading"><span class="admin-stat-icon">♡</span><div><strong>Foto dan visibilitas</strong><p>Foto JPG, PNG, atau WEBP maksimal 3 MB.</p></div></div>
            <div class="admin-form-grid">
                <div class="admin-field admin-field-full">
                    <label for="gambar">Foto produk</label>
                    <input class="file-input" id="gambar" type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                    @error('gambar')<small class="field-error">{{ $message }}</small>@enderror
                    @if($isEditing && $produk->gambar)
                        <div class="existing-image">
                            <img src="{{ asset('storage/' . $produk->gambar) }}" alt="Foto {{ $produk->nama }}">
                            <label class="remember-option"><input type="checkbox" name="hapus_gambar" value="1"><span>Hapus foto saat ini</span></label>
                        </div>
                    @endif
                </div>
                <div class="admin-field admin-field-full">
                    <label class="remember-option product-active-option">
                        <input type="checkbox" name="aktif" value="1" @checked(old('aktif', $produk->exists ? $produk->aktif : true))>
                        <span>Tampilkan produk di katalog (aktif)</span>
                    </label>
                </div>
            </div>
        </section>

        <div class="admin-form-actions">
            <a class="admin-cancel-button" href="{{ route('admin.produk.index') }}">Batal</a>
            <button class="admin-add-button" type="submit">{{ $isEditing ? 'Simpan perubahan' : 'Tambah produk' }} <span>↗</span></button>
        </div>
    </form>
</main>
@endsection
