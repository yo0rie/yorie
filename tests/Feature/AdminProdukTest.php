<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProdukTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_a_product(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $kategori = Kategori::create(['nama' => 'Alat Tulis', 'slug' => 'alat-tulis']);
        $kategoriBaru = Kategori::create(['nama' => 'Aksesoris', 'slug' => 'aksesoris']);

        $this->actingAs($admin)
            ->post(route('admin.produk.store'), [
                'kategori_id' => $kategori->id,
                'nama' => 'Buku Catatan',
                'slug' => 'buku-catatan',
                'deskripsi' => 'Buku untuk mencatat.',
                'harga' => 18000,
                'stok' => 24,
                'gambar' => UploadedFile::fake()->image('buku.png'),
                'aktif' => '1',
            ])
            ->assertRedirect(route('admin.produk.index'));

        $produk = Produk::where('slug', 'buku-catatan')->firstOrFail();
        $gambarLama = $produk->gambar;
        Storage::disk('public')->assertExists($gambarLama);
        $this->assertDatabaseHas('produk', [
            'nama' => 'Buku Catatan',
            'harga' => 18000,
            'stok' => 24,
            'aktif' => 1,
        ]);

        $this->put(route('admin.produk.update', $produk), [
            'kategori_id' => $kategoriBaru->id,
            'nama' => 'Buku Catatan Harian',
            'slug' => 'buku-catatan-harian',
            'deskripsi' => 'Buku catatan dengan halaman baru.',
            'harga' => 22000,
            'stok' => 12,
            'hapus_gambar' => '1',
        ])->assertRedirect(route('admin.produk.index'));

        $produk->refresh();
        $this->assertSame('Buku Catatan Harian', $produk->nama);
        $this->assertSame($kategoriBaru->id, $produk->kategori_id);
        $this->assertSame('buku-catatan-harian', $produk->slug);
        $this->assertSame('Buku catatan dengan halaman baru.', $produk->deskripsi);
        $this->assertSame(22000, $produk->harga);
        $this->assertSame(12, $produk->stok);
        $this->assertFalse($produk->aktif);
        $this->assertNull($produk->gambar);
        Storage::disk('public')->assertMissing($gambarLama);

        $this->delete(route('admin.produk.destroy', $produk))
            ->assertRedirect(route('admin.produk.index'));

        $this->assertDatabaseMissing('produk', ['id' => $produk->id]);
    }

    public function test_customers_cannot_manage_products(): void
    {
        $customer = User::factory()->create(['role' => 'pelanggan']);

        $this->actingAs($customer)
            ->get(route('admin.produk.index'))
            ->assertForbidden();
    }

    public function test_uploaded_product_photo_is_passed_to_the_storefront(): void
    {
        Storage::fake('public');
        $kategori = Kategori::create(['nama' => 'Alat Tulis', 'slug' => 'alat-tulis']);
        $gambar = UploadedFile::fake()->image('buku.png')->store('produk', 'public');
        $produk = Produk::create([
            'kategori_id' => $kategori->id,
            'nama' => 'Buku Foto',
            'slug' => 'buku-foto',
            'deskripsi' => 'Buku dengan foto produk.',
            'harga' => 18000,
            'stok' => 4,
            'gambar' => $gambar,
            'aktif' => true,
        ]);
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(basename($gambar), false);

        $this->get(route('produk.show', $produk->slug))
            ->assertOk()
            ->assertSee(basename($gambar), false);
    }
}
