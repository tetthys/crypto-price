<?php

declare(strict_types=1);

namespace Tetthys\CryptoPrice\Providers;

use Tetthys\CryptoPrice\Contracts\CryptoPriceProviderInterface;

final class CallableCryptoPriceProvider implements CryptoPriceProviderInterface
{
    public function __construct(private readonly \Closure $resolver) {}

    public function getUsdPrice(string $symbol): string
    {
        return (string) ($this->resolver)(strtoupper($symbol));
    }
}
