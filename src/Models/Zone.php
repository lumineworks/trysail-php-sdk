<?php

namespace Trysail\SDK\Models;

class Zone
{
    private string $id;
    private string $name;
    private string $publicKey;
    private ?string $endpoint;
    private array $allowedIPs;
    private ?string $persistentKeepalive;
    private string $status;
    private ?string $lastSeen;
    private array $metadata;
    
    public function __construct(
        string $id,
        string $name,
        string $publicKey,
        ?string $endpoint = null,
        array $allowedIPs = [],
        ?string $persistentKeepalive = null,
        string $status = 'active',
        ?string $lastSeen = null,
        array $metadata = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->publicKey = $publicKey;
        $this->endpoint = $endpoint;
        $this->allowedIPs = $allowedIPs;
        $this->persistentKeepalive = $persistentKeepalive;
        $this->status = $status;
        $this->lastSeen = $lastSeen;
        $this->metadata = $metadata;
    }
    
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? '',
            $data['name'] ?? '',
            $data['public_key'] ?? '',
            $data['endpoint'] ?? null,
            $data['allowed_ips'] ?? [],
            $data['persistent_keepalive'] ?? null,
            $data['status'] ?? 'active',
            $data['last_seen'] ?? null,
            $data['metadata'] ?? []
        );
    }
    
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'public_key' => $this->publicKey,
            'endpoint' => $this->endpoint,
            'allowed_ips' => $this->allowedIPs,
            'persistent_keepalive' => $this->persistentKeepalive,
            'status' => $this->status,
            'last_seen' => $this->lastSeen,
            'metadata' => $this->metadata,
        ];
    }
    
    public function getId(): string
    {
        return $this->id;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
    
    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }
    
    public function getAllowedIPs(): array
    {
        return $this->allowedIPs;
    }
    
    public function getPersistentKeepalive(): ?string
    {
        return $this->persistentKeepalive;
    }
    
    public function getStatus(): string
    {
        return $this->status;
    }
    
    public function getLastSeen(): ?string
    {
        return $this->lastSeen;
    }
    
    public function getMetadata(): array
    {
        return $this->metadata;
    }
    
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}