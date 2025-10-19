<?php

require __DIR__ . '/../vendor/autoload.php';

use Trysail\SDK\Config;
use Trysail\SDK\TrysailClient;
use Trysail\SDK\Exceptions\TrysailException;

// Create configuration
$config = new Config(
    baseUrl: 'http://localhost:8081',
    timeout: 30,
    verifySsl: false
);

// Create client
$client = new TrysailClient($config);

try {
    // List all zones
    echo "Fetching zones...\n";
    $zones = $client->zones()->list();
    
    echo "Found " . count($zones) . " zones:\n";
    foreach ($zones as $zone) {
        echo "  - {$zone->getName()} ({$zone->getId()}): {$zone->getStatus()}\n";
    }
    
    // Get a specific zone
    if (!empty($zones)) {
        $firstZone = $zones[0];
        echo "\nFetching zone details for: {$firstZone->getId()}\n";
        $zone = $client->zones()->get($firstZone->getId());
        echo "Zone: {$zone->getName()}\n";
        echo "Public Key: {$zone->getPublicKey()}\n";
        echo "Endpoint: {$zone->getEndpoint()}\n";
        echo "Status: {$zone->getStatus()}\n";
    }
    
    // Get control plane status
    echo "\nFetching control plane status...\n";
    $status = $client->controlPlane()->getStatus();
    print_r($status);
    
    // Create a new zone
    echo "\nCreating new zone...\n";
    $newZone = $client->zones()->create([
        'name' => 'example-zone',
        'public_key' => 'example_public_key_here',
        'endpoint' => '192.168.1.1:51820',
        'allowed_ips' => ['10.0.0.0/24'],
    ]);
    echo "Created zone: {$newZone->getName()} ({$newZone->getId()})\n";
    
    // Update the zone
    echo "\nUpdating zone...\n";
    $updatedZone = $client->zones()->update($newZone->getId(), [
        'status' => 'inactive',
    ]);
    echo "Updated zone status: {$updatedZone->getStatus()}\n";
    
    // Delete the zone
    echo "\nDeleting zone...\n";
    $client->zones()->delete($newZone->getId());
    echo "Zone deleted successfully\n";
    
} catch (TrysailException $e) {
    echo "Error: {$e->getMessage()}\n";
    echo "Status Code: {$e->getCode()}\n";
    
    if ($e->isNotFound()) {
        echo "Resource not found\n";
    } elseif ($e->isUnauthorized()) {
        echo "Unauthorized access\n";
    } elseif ($e->isServerError()) {
        echo "Server error occurred\n";
    }
    
    if ($responseData = $e->getResponseData()) {
        echo "Response data:\n";
        print_r($responseData);
    }
}