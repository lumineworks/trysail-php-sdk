# Trysail PHP SDK

PHP SDK for interacting with Trysail License Server and WireGuard Control Plane.

## Requirements

- PHP 8.1 or higher
- Composer
- Guzzle HTTP Client

## Installation

### Via Composer

```bash
composer require lumineworks/trysail-php-sdk
```

### Manual Installation

```bash
git clone https://github.com/lumineworks/trysail-php-sdk.git
cd php-sdk
composer install
```

## Quick Start

### Basic Usage

```php
<?php

require 'vendor/autoload.php';

use Trysail\SDK\TrysailClient;
use Trysail\SDK\Config;

// Create client
$config = new Config([
    'base_url' => 'http://localhost:8080',
    'timeout' => 30,
]);

$client = new TrysailClient($config);

// List zones
$zones = $client->zones()->list();

foreach ($zones as $zone) {
    echo "Zone: {$zone->name} - {$zone->endpoint}\n";
}

// Get specific zone
$zone = $client->zones()->get('zone-a');
echo "Zone Status: " . ($zone->healthy ? 'Healthy' : 'Unhealthy') . "\n";

// Register new zone
$newZone = $client->zones()->create([
    'name' => 'zone-c',
    'endpoint' => 'http://wg-zone-c:8082',
    'connection_mode' => 'direct',
    'metadata' => [
        'region' => 'us-west',
        'capacity' => 100,
    ],
]);
```

## Laravel Integration

### Service Provider

The SDK includes a Laravel service provider for easy integration.

#### Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Trysail\SDK\Laravel\TrysailServiceProvider"
```

Edit `config/trysail.php`:

```php
<?php

return [
    'base_url' => env('TRYSAIL_BASE_URL', 'http://localhost:8080'),
    'timeout' => env('TRYSAIL_TIMEOUT', 30),
    'api_key' => env('TRYSAIL_API_KEY'),
    'verify_ssl' => env('TRYSAIL_VERIFY_SSL', true),
];
```

Add to `.env`:

```env
TRYSAIL_BASE_URL=http://localhost:8080
TRYSAIL_API_KEY=your-api-key-here
TRYSAIL_TIMEOUT=30
```

#### Usage in Laravel

```php
<?php

namespace App\Http\Controllers;

use Trysail\SDK\TrysailClient;

class ZoneController extends Controller
{
    public function index(TrysailClient $client)
    {
        $zones = $client->zones()->list();
        
        return view('zones.index', compact('zones'));
    }
    
    public function show(TrysailClient $client, string $zoneName)
    {
        try {
            $zone = $client->zones()->get($zoneName);
            return response()->json($zone);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
    
    public function store(Request $request, TrysailClient $client)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'endpoint' => 'required|url',
            'connection_mode' => 'required|in:direct,indirect',
        ]);
        
        $zone = $client->zones()->create($validated);
        
        return response()->json($zone, 201);
    }
}
```

#### Facade

Use the facade for quick access:

```php
<?php

use Trysail\SDK\Facades\Trysail;

// List zones
$zones = Trysail::zones()->list();

// Get zone
$zone = Trysail::zones()->get('zone-a');

// Create zone
$zone = Trysail::zones()->create([
    'name' => 'zone-c',
    'endpoint' => 'http://wg-zone-c:8082',
]);
```

## API Reference

### Zones

#### List Zones

```php
$zones = $client->zones()->list();

// Returns array of Zone objects
foreach ($zones as $zone) {
    echo $zone->name;
    echo $zone->endpoint;
    echo $zone->healthy;
}
```

#### Get Zone

```php
$zone = $client->zones()->get('zone-a');

echo $zone->name;
echo $zone->endpoint;
echo $zone->connectionMode;
echo $zone->healthy;
echo $zone->lastSeen->format('Y-m-d H:i:s');
```

#### Create Zone

```php
$zone = $client->zones()->create([
    'name' => 'zone-c',
    'endpoint' => 'http://wg-zone-c:8082',
    'connection_mode' => 'direct',
    'metadata' => [
        'region' => 'us-west',
        'capacity' => 100,
    ],
]);
```

#### Update Zone

```php
$zone = $client->zones()->update('zone-c', [
    'endpoint' => 'http://new-endpoint:8082',
    'metadata' => [
        'region' => 'us-east',
    ],
]);
```

#### Delete Zone

```php
$client->zones()->delete('zone-c');
```

#### Check Zone Health

```php
$health = $client->zones()->health('zone-a');

echo $health->zone;
echo $health->status; // 'healthy' or 'unhealthy'
```

### Control Plane

#### Get Control Plane Zones

```php
$zones = $client->controlPlane()->zones();
```

#### Register Zone via Control Plane

```php
$zone = $client->controlPlane()->registerZone([
    'name' => 'zone-d',
    'endpoint' => 'http://wg-zone-d:8082',
]);
```

## Error Handling

```php
<?php

use Trysail\SDK\Exceptions\ZoneNotFoundException;
use Trysail\SDK\Exceptions\ApiException;
use Trysail\SDK\Exceptions\NetworkException;

try {
    $zone = $client->zones()->get('non-existent');
} catch (ZoneNotFoundException $e) {
    echo "Zone not found: " . $e->getMessage();
} catch (ApiException $e) {
    echo "API Error: " . $e->getMessage();
    echo "Status Code: " . $e->getStatusCode();
} catch (NetworkException $e) {
    echo "Network Error: " . $e->getMessage();
}
```

## Advanced Usage

### Custom HTTP Client

```php
<?php

use GuzzleHttp\Client as GuzzleClient;
use Trysail\SDK\TrysailClient;
use Trysail\SDK\Config;

$httpClient = new GuzzleClient([
    'timeout' => 60,
    'verify' => false, // Disable SSL verification
]);

$config = new Config([
    'base_url' => 'http://localhost:8080',
    'http_client' => $httpClient,
]);

$client = new TrysailClient($config);
```

### Retry Logic

```php
<?php

$config = new Config([
    'base_url' => 'http://localhost:8080',
    'max_retries' => 3,
    'retry_delay' => 1000, // milliseconds
]);

$client = new TrysailClient($config);
```

### Logging

```php
<?php

use Psr\Log\LoggerInterface;

$config = new Config([
    'base_url' => 'http://localhost:8080',
    'logger' => $logger, // PSR-3 Logger
    'log_requests' => true,
]);

$client = new TrysailClient($config);
```

## Testing

### Unit Tests

```bash
composer test
```

### With Code Coverage

```bash
composer test:coverage
```

### Integration Tests

```bash
composer test:integration
```

## License

MIT License

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Support

- Issues: https://github.com/trysail/php-sdk/issues
- Documentation: https://docs.trysail.io/sdk/php
- Email: support@trysail.io