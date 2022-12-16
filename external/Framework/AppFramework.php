<?php

namespace Framework;

use Exception;
use Framework\Event\ErrorResponseHandlingEvent;
use Framework\Event\RequestHandlingEvent;
use Framework\Event\ResponseHandlingEvent;
use Framework\Event\RouteAccessEvent;
use Framework\Exception\UserNotLoggedException;
use Framework\Exception\UserAlreadyLoggedException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class AppFramework
{
    private $urlMatcher;
    private $controllerResolver;
    private $argumentResolver;
    private $eventDispatcher;


    public function __construct(
        UrlMatcherInterface $urlMatcher,
        ControllerResolverInterface $controllerResolver,
        ArgumentResolverInterface $argumentResolver,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->urlMatcher = $urlMatcher;
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver = $argumentResolver;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(Request $request): Response
    {
        $this->eventDispatcher->dispatch(new RequestHandlingEvent($request), 'onRequestHandling');
        $this->urlMatcher->getContext()->fromRequest($request);

        try{
            $routeData = $this->urlMatcher->match($request->getPathInfo());
            $request->attributes->add($routeData);
            $controller = $this->controllerResolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);
            $this->eventDispatcher->dispatch(new RouteAccessEvent($routeData), 'onRouteAccess');
            $response = call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $exception) {
            $response = $this->eventDispatcher->dispatch(new ErrorResponseHandlingEvent(404), 'onErrorResponseHandling')->getResponse();

        } catch (UserNotLoggedException $exception) {
            $response = $this->eventDispatcher->dispatch(new ErrorResponseHandlingEvent(401, $exception), 'onErrorResponseHandling')->getResponse();
        } catch (UserAlreadyLoggedException $exception) {
            $response = $this->eventDispatcher->dispatch(new ErrorResponseHandlingEvent(403, $exception), 'onErrorResponseHandling')->getResponse();
        } catch (Exception $exception) {
            $response = $this->eventDispatcher->dispatch(new ErrorResponseHandlingEvent(500, $exception), 'onErrorResponseHandling')->getResponse();
        }
        $this->eventDispatcher->dispatch(new ResponseHandlingEvent($response), 'onResponseHandling');
        return $response;
    }

}