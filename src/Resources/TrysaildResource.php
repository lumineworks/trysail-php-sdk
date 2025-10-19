<?php

namespace Trysail\SDK\Resources;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Trysail\SDK\Config;
use Trysail\SDK\Exceptions\TrysailException;

class TrysaildResource extends BaseResource
{
    public function __construct(GuzzleClient $httpClient, Config $config)
    {
        parent::__construct($httpClient, $config);
    }
    
    /**
     * Get Trysaild server status
     * 
     * @return array
     * @throws TrysailException
     */
    public function getStatus(): array
    {
        try {
            $response = $this->httpClient->get('/api/trysaild/status');
            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
    
    /**
     * Get Trysaild configuration
     * 
     * @param string $zoneId
     * @return array
     * @throws TrysailException
     */
    public function getConfiguration(string $zoneId): array
    {
        try {
            $response = $this->httpClient->get("/api/trysaild/{$zoneId}/config");
            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
    
    /**
     * Execute command on Trysaild server
     * 
     * @param string $zoneId
     * @param array $command
     * @return array
     * @throws TrysailException
     */
    public function executeCommand(string $zoneId, array $command): array
    {
        try {
            $response = $this->httpClient->post("/api/trysaild/{$zoneId}/execute", [
                'json' => $command,
            ]);
            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
    
    /**
     * Get Trysaild peers
     * 
     * @param string $zoneId
     * @return array
     * @throws TrysailException
     */
    public function getPeers(string $zoneId): array
    {
        try {
            $response = $this->httpClient->get("/api/trysaild/{$zoneId}/peers");
            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
    
    /**
     * Add peer to Trysaild
     * 
     * @param string $zoneId
     * @param array $peerData
     * @return array
     * @throws TrysailException
     */
    public function addPeer(string $zoneId, array $peerData): array
    {
        try {
            $response = $this->httpClient->post("/api/trysaild/{$zoneId}/peers", [
                'json' => $peerData,
            ]);
            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
    
    /**
     * Remove peer from Trysaild
     * 
     * @param string $zoneId
     * @param string $peerId
     * @return bool
     * @throws TrysailException
     */
    public function removePeer(string $zoneId, string $peerId): bool
    {
        try {
            $this->httpClient->delete("/api/trysaild/{$zoneId}/peers/{$peerId}");
            return true;
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
    
    /**
     * Get Trysaild logs
     * 
     * @param string $zoneId
     * @param array $options
     * @return array
     * @throws TrysailException
     */
    public function getLogs(string $zoneId, array $options = []): array
    {
        try {
            $queryString = $this->buildQueryString($options);
            $response = $this->httpClient->get("/api/trysaild/{$zoneId}/logs{$queryString}");
            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
    
    /**
     * Restart Trysaild service
     * 
     * @param string $zoneId
     * @return array
     * @throws TrysailException
     */
    public function restart(string $zoneId): array
    {
        try {
            $response = $this->httpClient->post("/api/trysaild/{$zoneId}/restart");
            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw TrysailException::fromGuzzleException($e);
        }
    }
}