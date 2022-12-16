<?php

namespace Framework\Event;

use Symfony\Contracts\EventDispatcher\Event;

class RouteAccessEvent extends Event
{
    private array $routedata;

    public function __construct(array $routedata)
    {
        $this->routedata = $routedata;
    }

    /**
     * @return array
     */
    public function getRoutedata(): array
    {
        return $this->routedata;
    }
}