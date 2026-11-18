<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'admin' => auth()->user(),
            'jumlahProduk' => Produk::count(),
            'jumlahKategori' => Kategori::count(),
            'jumlahPesanan' => Pesanan::count(),
            'totalPenjualan' => DetailPesanan::whereHas('pesanan', function ($query) {
                $query->where('status', 'selesai');
            })->sum('subtotal'),
        ]);
    }
}
