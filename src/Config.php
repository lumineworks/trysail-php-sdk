<?php

namespace Trysail\SDK;

use GuzzleHttp\Client as GuzzleClient;

class Config
{
    private string $baseUrl;
    private int $timeout;
    private bool $verifySsl;
    private ?GuzzleClient $httpClient;
    private array $headers;
    
    public function __construct(
        string $baseUrl,
        int $timeout = 30,
        bool $verifySsl = true,
        ?GuzzleClient $httpClient = null,
        array $headers = []
    ) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->timeout = $timeout;
        $this->verifySsl = $verifySsl;
        $this->httpClient = $httpClient;
        $this->headers = $headers;
    }
    
    public static function fromArray(array $config): self
    {
        return new self(
            $config['base_url'] ?? '',
            $config['timeout'] ?? 30,
            $config['verify_ssl'] ?? true,
            $config['http_client'] ?? null,
            $config['headers'] ?? []
        );
    }
    
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
    
    public function getTimeout(): int
    {
        return $this->timeout;
    }
    
    public function shouldVerifySsl(): bool
    {
        return $this->verifySsl;
    }
    
    public function getHttpClient(): ?GuzzleClient
    {
        return $this->httpClient;
    }
    
    public function getHeaders(): array
    {
        return $this->headers;
    }
    
    public function withBaseUrl(string $baseUrl): self
    {
        $config = clone $this;
        $config->baseUrl = rtrim($baseUrl, '/');
        return $config;
    }
    
    public function withTimeout(int $timeout): self
    {
        $config = clone $this;
        $config->timeout = $timeout;
        return $config;
    }
    
    public function withVerifySsl(bool $verifySsl): self
    {
        $config = clone $this;
        $config->verifySsl = $verifySsl;
        return $config;
    }
    
    public function withHeader(string $key, string $value): self
    {
        $config = clone $this;
        $config->headers[$key] = $value;
        return $config;
    }
}