<?php

declare(strict_types=1);

namespace Tetthys\CryptoPrice\Services;

use Tetthys\CryptoPrice\Contracts\CryptoPriceProviderInterface;
use Tetthys\CryptoPrice\Support\CryptoScale;

final class CryptoPriceService
{
    public function __construct(private readonly CryptoPriceProviderInterface $provider) {}

    public function usd(string $symbol): string
    {
        return $this->provider->getUsdPrice($symbol);
    }

    public function usdToCrypto(string $usdAmount, string $symbol, ?int $scale = null): string
    {
        $p = $this->provider->getUsdPrice($symbol);
        $s = $scale ?? CryptoScale::get($symbol);
        return bcdiv($usdAmount, $p, $s);
    }

    public function cryptoToUsd(string $cryptoAmount, string $symbol, int $scale = 2): string
    {
        $p = $this->provider->getUsdPrice($symbol);
        return bcmul($cryptoAmount, $p, $scale);
    }
}
