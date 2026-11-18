<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProdukController extends Controller
{
    public function index(Request $request): View
    {
        $produk = Produk::with('kategori')
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('nama', 'like', '%'.$request->string('q').'%');
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.produk.index', compact('produk'));
    }

    public function create(): View
    {
        return view('admin.produk.form', [
            'produk' => new Produk,
            'kategori' => Kategori::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());
        $data['aktif'] = $request->boolean('aktif');

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->route('admin.produk.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $produk): View
    {
        return view('admin.produk.form', [
            'produk' => $produk,
            'kategori' => Kategori::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Produk $produk): RedirectResponse
    {
        $data = $request->validate($this->rules($produk));
        $data['aktif'] = $request->boolean('aktif');

        $gambarLama = $produk->gambar;
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        } elseif ($request->boolean('hapus_gambar')) {
            $data['gambar'] = null;
        }

        $produk->update($data);

        if ($gambarLama && $produk->gambar !== $gambarLama) {
            Storage::disk('public')->delete($gambarLama);
        }

        return redirect()->route('admin.produk.index')->with('status', 'Perubahan produk berhasil disimpan.');
    }

    public function destroy(Produk $produk): RedirectResponse
    {
        if ($produk->detailPesanan()->exists()) {
            return back()->withErrors([
                'produk' => 'Produk sudah tercatat di pesanan dan tidak dapat dihapus. Nonaktifkan produk agar tetap tersimpan di riwayat.',
            ]);
        }

        $gambar = $produk->gambar;
        $produk->delete();

        if ($gambar) {
            Storage::disk('public')->delete($gambar);
        }

        return redirect()->route('admin.produk.index')->with('status', 'Produk berhasil dihapus.');
    }

    private function rules(?Produk $produk = null): array
    {
        $slug = Rule::unique('produk', 'slug');
        if ($produk) {
            $slug->ignore($produk->id);
        }

        return [
            'kategori_id' => ['required', 'integer', 'exists:kategori,id'],
            'nama' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', $slug],
            'deskripsi' => ['required', 'string'],
            'harga' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'stok' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'aktif' => ['nullable', 'boolean'],
            'hapus_gambar' => ['nullable', 'boolean'],
        ];
    }
}
