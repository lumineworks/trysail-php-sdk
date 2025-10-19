<?php

namespace Trysail\SDK;

use GuzzleHttp\Client as GuzzleClient;
use Trysail\SDK\Resources\ZoneResource;
use Trysail\SDK\Resources\ControlPlaneResource;
use Trysail\SDK\Resources\TrysaildResource;

class TrysailClient
{
    private GuzzleClient $httpClient;
    private Config $config;
    
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->httpClient = $config->getHttpClient() ?? $this->createDefaultHttpClient();
    }
    
    private function createDefaultHttpClient(): GuzzleClient
    {
        return new GuzzleClient([
            'base_uri' => $this->config->getBaseUrl(),
            'timeout' => $this->config->getTimeout(),
            'verify' => $this->config->shouldVerifySsl(),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Trysail-PHP-SDK/1.0',
            ],
        ]);
    }
    
    public function zones(): ZoneResource
    {
        return new ZoneResource($this->httpClient, $this->config);
    }
    
    public function controlPlane(): ControlPlaneResource
    {
        return new ControlPlaneResource($this->httpClient, $this->config);
    }
    
    public function trysaild(): TrysaildResource
    {
        return new TrysaildResource($this->httpClient, $this->config);
    }
    
    public function getHttpClient(): GuzzleClient
    {
        return $this->httpClient;
    }
    
    public function getConfig(): Config
    {
        return $this->config;
    }
}