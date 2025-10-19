<?php

namespace Trysail\SDK\Resources;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;
use Trysail\SDK\Config;
use Trysail\SDK\Exceptions\TrysailException;

abstract class BaseResource
{
    protected GuzzleClient $httpClient;
    protected Config $config;
    
    public function __construct(GuzzleClient $httpClient, Config $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
    }
    
    /**
     * Parse HTTP response and return decoded JSON data
     * 
     * @param ResponseInterface $response
     * @return array
     * @throws TrysailException
     */
    protected function parseResponse(ResponseInterface $response): array
    {
        $body = (string) $response->getBody();
        
        if (empty($body)) {
            return [];
        }
        
        $data = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new TrysailException(
                'Failed to parse JSON response: ' . json_last_error_msg(),
                $response->getStatusCode()
            );
        }
        
        return $data;
    }
    
    /**
     * Build query string from parameters
     * 
     * @param array $params
     * @return string
     */
    protected function buildQueryString(array $params): string
    {
        $filtered = array_filter($params, fn($value) => $value !== null);
        
        if (empty($filtered)) {
            return '';
        }
        
        return '?' . http_build_query($filtered);
    }
}