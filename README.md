# tetthys/crypto-price

> Lightweight, framework-agnostic crypto price utility for PHP 8.3+.  
> Provides simple USD spot price access, crypto-to-USD conversion, and scale-aware math using BCMath.

---

## 📦 Installation

```bash
composer require tetthys/crypto-price
````

Requires **PHP 8.3+** and the **bcmath** extension.

---

## 🚀 Quick Example

```php
use Tetthys\CryptoPrice\Providers\CallableCryptoPriceProvider;
use Tetthys\CryptoPrice\Services\CryptoPriceService;

$provider = new CallableCryptoPriceProvider(
    fn(string $symbol) => match (strtoupper($symbol)) {
        'BTC' => '98765.4321',
        'ETH' => '3456.78',
        'XMR' => '150.50',
        default => throw new RuntimeException("Unknown symbol: {$symbol}")
    }
);

$service = new CryptoPriceService($provider);

echo $service->usd('BTC'); // "98765.4321"
echo $service->cryptoToUsd('0.015', 'BTC'); // "1481.48"
echo $service->usdToCrypto('1000', 'BTC');  // "0.01012345"
```

---

## 🧩 Core Concepts

### `CryptoPriceProviderInterface`

Defines a source that returns USD spot price per crypto symbol.

```php
public function getUsdPrice(string $symbol): string;
```

Example:

* `getUsdPrice('BTC')` → `"98765.4321"`
* `getUsdPrice('ETH')` → `"3456.78"`

---

### `CryptoPriceService`

A high-level service that performs conversions and handles decimal precision per asset.

```php
$service = new CryptoPriceService($provider);

$usd  = $service->cryptoToUsd('0.005', 'BTC'); // "493.82"
$btc  = $service->usdToCrypto('100', 'BTC');   // "0.0010132"
$spot = $service->usd('BTC');                  // "98765.4321"
```

All math uses **BCMath** to ensure fixed-scale precision.

---

### `CryptoScale`

A static mapping of default decimal scales per symbol:

| Symbol | Default scale |
| -----: | :------------ |
|    BTC | 8             |
|    LTC | 8             |
|    XMR | 12            |
|    ETH | 8             |
|   USDT | 6             |

Use `CryptoScale::get('BTC')` to retrieve the default scale.

---

### `CallableCryptoPriceProvider`

A minimal provider that wraps a closure.
Perfect for tests or static pricing environments.

```php
$provider = new CallableCryptoPriceProvider(
    fn(string $symbol) => ['BTC' => '98765.43', 'ETH' => '3456.78'][$symbol] ?? '0'
);
```

---

## ⚙️ Integrating with Laravel

You can easily wire this package into Laravel using a small provider:

```php
use App\Support\CryptoPrice\CoinMarketCapProvider;
use Tetthys\CryptoPrice\Services\CryptoPriceService;

$provider = new CoinMarketCapProvider(apiKey: env('COINMARKETCAP_API_KEY'));
$service  = new CryptoPriceService($provider);

echo $service->cryptoToUsd('0.5', 'ETH');
```

or register both in a custom `CryptoPriceServiceProvider`:

```php
$this->app->singleton(CryptoPriceProviderInterface::class, fn () =>
    new CoinMarketCapProvider(env('COINMARKETCAP_API_KEY'))
);

$this->app->bind(CryptoPriceService::class, fn ($app) =>
    new CryptoPriceService($app->make(CryptoPriceProviderInterface::class))
);
```

---

## 🧪 Testing with a Mock Provider

```php
use Tetthys\CryptoPrice\Providers\CallableCryptoPriceProvider;
use Tetthys\CryptoPrice\Services\CryptoPriceService;

$mock = new CallableCryptoPriceProvider(fn($s) => match ($s) {
    'BTC' => '10000', 'ETH' => '1000', default => '1'
});

$svc = new CryptoPriceService($mock);
expect($svc->cryptoToUsd('0.5', 'BTC'))->toBe('5000.00');
```

---

## 🪪 License

MIT © Tetthys Labs