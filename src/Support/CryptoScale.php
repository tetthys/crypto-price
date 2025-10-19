<?php

declare(strict_types=1);

namespace Tetthys\CryptoPrice\Support;

final class CryptoScale
{
    /** @var array<string,int> */
    private const MAP = [
        'BTC' => 8,
        'LTC' => 8,
        'XMR' => 12,
        'ETH' => 8,
        'USDT' => 6,
    ];

    public static function get(string $symbol, int $fallback = 8): int
    {
        return self::MAP[strtoupper($symbol)] ?? $fallback;
    }
}
