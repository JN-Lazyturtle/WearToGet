<?php

namespace TheFeed\Listener;
use Framework\Event\ErrorResponseHandlingEvent;
use Framework\Event\RouteAccessEvent;
use Framework\Exception\UserNotLoggedException;
use Framework\Exception\UserAlreadyLoggedException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use TheFeed\Business\Services\UtilisateurService;
use Twig\Environment;

class AppListener implements EventSubscriberInterface
{
    private UtilisateurService $serviceUtilisateur;

    private Environment $twig;

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UtilisateurService $serviceUtilisateur, Environment $twig, UrlGeneratorInterface $urlGenerator)
    {
        $this->serviceUtilisateur = $serviceUtilisateur;
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    public function checkRoute(RouteAccessEvent $event) {
        $routeData = $event->getRoutedata();
        if(array_key_exists("_logged", $routeData)) {
            if($routeData["_logged"]) {
                if(!$this->serviceUtilisateur->estConnecte()) {
                    throw new UserNotLoggedException();
                }
            }
        }
        if(array_key_exists("_force_not_logged", $routeData)) {
            if($routeData["_force_not_logged"]) {
                if($this->serviceUtilisateur->estConnecte()) {
                    throw new UserAlreadyLoggedException();
                }
            }
        }
    }

    public function updateErrorResponse(ErrorResponseHandlingEvent $event) {
        $status = $event->getStatus();
        if($status == 404) {
            $response = new Response($this->twig->render('Erreurs/notFound.html.twig'), $status);
        }
        else if($status == 401) {
            $response = new RedirectResponse($this->urlGenerator->generate('connexion'));
        }
        else if($status == 403) {
            $response = new RedirectResponse($this->urlGenerator->generate('feed'));
        }
        else {
            $response = new Response($this->twig->render('Erreurs/error.html.twig', ['message' => $event->getException()->getMessage()]), $status);
        }
        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            'onRouteAccess' => 'checkRoute',
            'onErrorResponseHandling' => 'updateErrorResponse'
        ];
    }
}