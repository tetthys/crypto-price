<?php

declare(strict_types=1);

namespace Tetthys\CryptoPrice\Contracts;

interface CryptoPriceProviderInterface
{
    public function getUsdPrice(string $symbol): string; // e.g. "BTC" -> "98765.1234"
}
