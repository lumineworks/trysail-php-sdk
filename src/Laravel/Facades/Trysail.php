<?php

namespace Trysail\SDK\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Trysail\SDK\TrysailClient;

/**
 * @method static \Trysail\SDK\Resources\ZoneResource zones()
 * @method static \Trysail\SDK\Resources\ControlPlaneResource controlPlane()
 * @method static \GuzzleHttp\Client getHttpClient()
 * @method static \Trysail\SDK\Config getConfig()
 * 
 * @see \Trysail\SDK\TrysailClient
 */
class Trysail extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return TrysailClient::class;
    }
}