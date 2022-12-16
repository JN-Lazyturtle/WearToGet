<?php

namespace Framework\Listener;

use Framework\Event\RequestHandlingEvent;
use Framework\Event\ErrorResponseHandlingEvent;
use Framework\Event\ResponseHandlingEvent;
use Framework\Services\ServerSessionManager;
use Framework\Services\UserSessionManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Environment;

class FrameworkListener implements EventSubscriberInterface
{

    private Environment $twig;

    private UserSessionManager $sessionManager;

    public function __construct(Environment $twig, UserSessionManager $sessionManager)
    {
        $this->twig = $twig;
        $this->sessionManager = $sessionManager;
    }

    public function setupAppFromRequest(RequestHandlingEvent $event) {
        $request = $event->getRequest();
        $this->sessionManager->updateSessionFromRequest($request);
        $this->twig->addGlobal('session', $this->sessionManager);
    }

    public function setupAppOnResponse(ResponseHandlingEvent $event) {
        $response = $event->getResponse();
        $this->sessionManager->updateResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            'onRequestHandling' => 'setupAppFromRequest',
             'onResponseHandling' => 'setupAppOnResponse'
        ];
    }
}