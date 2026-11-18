<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\StatusPesanan;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $fillable = [
        'kode_pesanan',
        'user_id',
        'nama_penerima',
        'no_hp',
        'alamat_pengiriman',
        'subtotal',
        'ongkir',
        'total',
        'status',
        'bukti_bayar',
        'no_resi',
    ];

    protected function casts(): array
    {
        return [
            'status' => StatusPesanan::class,
            'subtotal' => 'integer',
            'ongkir' => 'integer',
            'total' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}
