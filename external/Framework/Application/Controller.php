<?php

namespace Framework\Application;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{

    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function render($path, $arguments = []) : Response {
        return new Response($this->container->get('twig')->render($path, $arguments));
    }

    public function redirectTo($url) : Response {
        return new RedirectResponse($url);
    }

    public function redirectToRoute($routeName, $args = []) : Response {
        $generator = $this->container->get("url_generator");
        $url = $generator->generate($routeName, $args);
        return $this->redirectTo($url);
    }

    public function addFlash($type, $message) {
        $sessionManager = $this->container->get('session_manager');
        $sessionManager->addFlash($type, $message);
    }
}