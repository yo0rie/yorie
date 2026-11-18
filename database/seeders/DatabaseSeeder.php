<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\StatusPesanan;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create(['name'=>'Admin Toko','email'=>'admin@toko.test','password'=>Hash::make('password'),'no_hp'=>'081111111111','alamat'=>'Sekolah','role'=>'admin']);
        for ($i=1;$i<=3;$i++) User::create(['name'=>"Pelanggan $i",'email'=>"pelanggan$i@toko.test",'password'=>Hash::make('password'),'no_hp'=>"081234500$i",'alamat'=>"Alamat pelanggan $i",'role'=>'pelanggan']);
        foreach (['Pakaian','Alat Tulis','Aksesoris','Makanan'] as $index=>$nama) {
            $kategori=Kategori::create(['nama'=>$nama,'slug'=>Str::slug($nama)]);
            for ($i=1;$i<=4;$i++) {
                $namaProduk="$nama Produk $i";
                Produk::create(['kategori_id'=>$kategori->id,'nama'=>$namaProduk,'slug'=>Str::slug($namaProduk).'-'.($index+1).'-'.$i,'deskripsi'=>"Deskripsi $namaProduk untuk kebutuhan sekolah.",'harga'=>($i*75000)+(($index+1)*10000),'stok'=>$i===4?3:12,'aktif'=>true]);
            }
        }
        $pelanggan=User::where('role','pelanggan')->first();
        $produk=Produk::first();
        $pesanan=Pesanan::create(['kode_pesanan'=>'INV-'.now()->format('Ymd').'-0001','user_id'=>$pelanggan->id,'nama_penerima'=>$pelanggan->name,'no_hp'=>$pelanggan->no_hp,'alamat_pengiriman'=>$pelanggan->alamat,'subtotal'=>$produk->harga,'ongkir'=>15000,'total'=>$produk->harga+15000,'status'=>StatusPesanan::MenungguPembayaran]);
        DetailPesanan::create(['pesanan_id'=>$pesanan->id,'produk_id'=>$produk->id,'nama_produk'=>$produk->nama,'harga_satuan'=>$produk->harga,'jumlah'=>1,'subtotal'=>$produk->harga]);
    }
}
