<?php

namespace Trysail\SDK\Resources;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Trysail\SDK\Config;
use Trysail\SDK\Exceptions\TrysailException;

class ControlPlaneResource extends BaseResource
{
    public function __construct(GuzzleClient $httpClient, Config $config)
    {
        parent::__construct($httpClient, $config);
    }
    
    /**
     * Get control plane status
     * 
     * @return array
     * @throws TrysailException
     */
    public function getStatus(): array
    {
        try {
            $response = $this->httpClient->get('/api/controlplane/status');
            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
    
    /**
     * Sync zones from license server
     * 
     * @return array
     * @throws TrysailException
     */
    public function syncZones(): array
    {
        try {
            $response = $this->httpClient->post('/api/controlplane/sync');
            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
    
    /**
     * Get all registered zones in control plane
     * 
     * @return array
     * @throws TrysailException
     */
    public function getRegisteredZones(): array
    {
        try {
            $response = $this->httpClient->get('/api/controlplane/zones');
            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
    
    /**
     * Register a zone with control plane
     * 
     * @param array $zoneData
     * @return array
     * @throws TrysailException
     */
    public function registerZone(array $zoneData): array
    {
        try {
            $response = $this->httpClient->post('/api/controlplane/zones/register', [
                'json' => $zoneData,
            ]);
            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
    
    /**
     * Get metrics from control plane
     * 
     * @return array
     * @throws TrysailException
     */
    public function getMetrics(): array
    {
        try {
            $response = $this->httpClient->get('/api/controlplane/metrics');
            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
}