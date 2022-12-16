<?php

namespace Framework\Event;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class ErrorResponseHandlingEvent extends Event
{

    private int $status;

    private $exception;

    private Response $response;

    public function __construct(int $status, $exception = null)
    {
        $this->status = $status;
        $this->exception = $exception;
        $this->response = new Response("Erreur $status", $status);
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response): void
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