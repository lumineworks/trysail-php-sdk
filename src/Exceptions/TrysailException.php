<?php

namespace Trysail\SDK\Exceptions;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class TrysailException extends Exception
{
    private ?ResponseInterface $response;
    private ?array $responseData;
    
    public function __construct(
        string $message = "",
        int $code = 0,
        ?Exception $previous = null,
        ?ResponseInterface $response = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
        $this->responseData = $this->parseResponseData();
    }
    
    public static function fromGuzzleException(GuzzleException $e): self
    {
        $message = $e->getMessage();
        $code = $e->getCode();
        $response = null;
        
        if ($e instanceof RequestException && $e->hasResponse()) {
            $response = $e->getResponse();
            $code = $response->getStatusCode();
            
            // Try to extract error message from response body
            $body = (string) $response->getBody();
            $data = json_decode($body, true);
            
            if (isset($data['error'])) {
                $message = $data['error'];
            } elseif (isset($data['message'])) {
                $message = $data['message'];
            }
        }
        
        return new self($message, $code, $e, $response);
    }
    
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
    
    public function getResponseData(): ?array
    {
        return $this->responseData;
    }
    
    public function isClientError(): bool
    {
        return $this->code >= 400 && $this->code < 500;
    }
    
    public function isServerError(): bool
    {
        return $this->code >= 500 && $this->code < 600;
    }
    
    public function isNotFound(): bool
    {
        return $this->code === 404;
    }
    
    public function isUnauthorized(): bool
    {
        return $this->code === 401;
    }
    
    public function isForbidden(): bool
    {
        return $this->code === 403;
    }
    
    private function parseResponseData(): ?array
    {
        if ($this->response === null) {
            return null;
        }
        
        $body = (string) $this->response->getBody();
        
        if (empty($body)) {
            return null;
        }
        
        $data = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }
        
        return $data;
    }
}