<?php

namespace App;

enum StatusPesanan: string
{
    case MenungguPembayaran = 'menunggu_pembayaran';
    case MenungguVerifikasi = 'menunggu_verifikasi';
    case Diproses = 'diproses';
    case Dikirim = 'dikirim';
    case Selesai = 'selesai';
    case Dibatalkan = 'dibatalkan';

    public function bolehKe(self $tujuan): bool
    {
        return match ($this) {
            self::MenungguPembayaran => in_array($tujuan, [self::MenungguVerifikasi, self::Dibatalkan], true),
            self::MenungguVerifikasi => in_array($tujuan, [self::Diproses, self::Dibatalkan], true),
            self::Diproses => in_array($tujuan, [self::Dikirim, self::Dibatalkan], true),
            self::Dikirim => $tujuan === self::Selesai,
            default => false,
        };
    }
}
