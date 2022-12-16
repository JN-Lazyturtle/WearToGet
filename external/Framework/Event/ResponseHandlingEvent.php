<?php

namespace Framework\Event;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class ResponseHandlingEvent extends Event
{
    private Response $response;

    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}