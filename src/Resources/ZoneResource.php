<?php

namespace Trysail\SDK\Resources;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Trysail\SDK\Config;
use Trysail\SDK\Models\Zone;
use Trysail\SDK\Exceptions\TrysailException;

class ZoneResource extends BaseResource
{
    public function __construct(GuzzleClient $httpClient, Config $config)
    {
        parent::__construct($httpClient, $config);
    }
    
    /**
     * List all zones
     * 
     * @return Zone[]
     * @throws TrysailException
     */
    public function list(): array
    {
        try {
            $response = $this->httpClient->get('/api/zones');
            $data = $this->parseResponse($response);
            
            return array_map(fn($item) => Zone::fromArray($item), $data);
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
    
    /**
     * Get a specific zone by ID
     * 
     * @param string $zoneId
     * @return Zone
     * @throws TrysailException
     */
    public function get(string $zoneId): Zone
    {
        try {
            $response = $this->httpClient->get("/api/zones/{$zoneId}");
            $data = $this->parseResponse($response);
            
            return Zone::fromArray($data);
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
    
    /**
     * Create a new zone
     * 
     * @param array $data
     * @return Zone
     * @throws TrysailException
     */
    public function create(array $data): Zone
    {
        try {
            $response = $this->httpClient->post('/api/zones', [
                'json' => $data,
            ]);
            $responseData = $this->parseResponse($response);
            
            return Zone::fromArray($responseData);
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
    
    /**
     * Update an existing zone
     * 
     * @param string $zoneId
     * @param array $data
     * @return Zone
     * @throws TrysailException
     */
    public function update(string $zoneId, array $data): Zone
    {
        try {
            $response = $this->httpClient->put("/api/zones/{$zoneId}", [
                'json' => $data,
            ]);
            $responseData = $this->parseResponse($response);
            
            return Zone::fromArray($responseData);
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
    
    /**
     * Delete a zone
     * 
     * @param string $zoneId
     * @return bool
     * @throws TrysailException
     */
    public function delete(string $zoneId): bool
    {
        try {
            $this->httpClient->delete("/api/zones/{$zoneId}");
            return true;
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
    
    /**
     * Get zone configuration
     * 
     * @param string $zoneId
     * @return array
     * @throws TrysailException
     */
    public function getConfiguration(string $zoneId): array
    {
        try {
            $response = $this->httpClient->get("/api/zones/{$zoneId}/config");
            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
}