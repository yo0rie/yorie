<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $kategoriDipilih = $request->string('kategori')->toString();

        $produk = Produk::query()
            ->with('kategori')
            ->where('aktif', true)
            ->when($kategoriDipilih, function ($query) use ($kategoriDipilih) {
                $query->whereHas('kategori', function ($kategori) use ($kategoriDipilih) {
                    $kategori->where('slug', $kategoriDipilih);
                });
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('nama', 'like', '%' . $request->q . '%');
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('storefront', [
            'storefront' => [
                'page' => 'catalog',
                'homeUrl' => route('home'),
                'loginUrl' => route('login'),
                'dashboardUrl' => route('admin.dashboard'),
                'logoutUrl' => route('logout'),
                'csrfToken' => csrf_token(),
                'user' => auth()->user()?->only(['name', 'role']),
                'search' => $request->string('q')->toString(),
                'selectedCategory' => $kategoriDipilih,
                'categories' => Kategori::withCount('produk')->get()
                    ->map(fn (Kategori $kategori) => [
                        'name' => $kategori->nama,
                        'slug' => $kategori->slug,
                        'count' => $kategori->produk_count,
                    ])->values(),
                'products' => $produk->getCollection()
                    ->map(fn (Produk $item) => $this->productCard($item))->values(),
                'pagination' => [
                    'current' => $produk->currentPage(),
                    'last' => $produk->lastPage(),
                ],
            ],
        ]);
    }

    public function show(string $slug)
    {
        $produk = Produk::with('kategori')
            ->where('slug', $slug)
            ->where('aktif', true)
            ->firstOrFail();

        $produkTerkait = Produk::with('kategori')
            ->where('aktif', true)
            ->where('kategori_id', $produk->kategori_id)
            ->whereKeyNot($produk->id)
            ->latest()
            ->limit(4)
            ->get();

        return view('storefront', [
            'storefront' => [
                'page' => 'product',
                'homeUrl' => route('home'),
                'loginUrl' => route('login'),
                'dashboardUrl' => route('admin.dashboard'),
                'logoutUrl' => route('logout'),
                'csrfToken' => csrf_token(),
                'user' => auth()->user()?->only(['name', 'role']),
                'product' => $this->productCard($produk) + [
                    'description' => $produk->deskripsi,
                    'stock' => $produk->stok,
                ],
                'relatedProducts' => $produkTerkait
                    ->map(fn (Produk $item) => $this->productCard($item))->values(),
            ],
        ]);
    }

    private function productCard(Produk $produk): array
    {
        return [
            'name' => $produk->nama,
            'slug' => $produk->slug,
            'category' => $produk->kategori->nama,
            'categoryId' => $produk->kategori_id,
            'categorySlug' => $produk->kategori->slug,
            'price' => $produk->harga,
            'stock' => $produk->stok,
            'image' => $produk->gambar ? asset('storage/' . $produk->gambar) : null,
            'url' => route('produk.show', $produk->slug),
        ];
    }
}
