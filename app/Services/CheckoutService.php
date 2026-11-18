<?php

namespace App\Services;

class CheckoutService
{
    public static function ongkir(int $subtotal): int
    {
        return $subtotal >= 500000 ? 0 : 15000;
    }
}
