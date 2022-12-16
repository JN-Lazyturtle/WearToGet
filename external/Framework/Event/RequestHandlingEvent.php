<?php

namespace Framework\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class RequestHandlingEvent extends Event
{

    private Request $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}