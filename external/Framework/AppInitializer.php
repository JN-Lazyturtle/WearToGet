<?php

namespace Framework;

use Framework\Listener\FrameworkListener;
use Framework\Twig\Extension\TwigAppFrameworkExtension;
use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Twig\Loader\FilesystemLoader;

class AppInitializer
{
    public function initializeApplication($configClass) {
        $containerBuilder = new DependencyInjection\ContainerBuilder();

        //Settings

        $appRoot = $configClass::appRoot;
        $viewsFolder = $appRoot . '/' . $configClass::views;

        $containerBuilder->setParameter('debug', $configClass::debug);
        $containerBuilder->setParameter('environment', $configClass::environment);

        foreach ($configClass::parameters as $parameter => $value) {
            $containerBuilder->setParameter($parameter, $value);
        }

        //Routing
        $containerBuilder->register('context', Routing\RequestContext::class);

        $routes = new RouteCollection();
        foreach ($configClass::routes as $routeName => $routeData) {
            $route = new Route($routeData["path"],$routeData["parameters"]);
            $route->setMethods($routeData["methods"]);
            $routes->add($routeName, $route);
        }

        $containerBuilder->register('matcher', Routing\Matcher\UrlMatcher::class)
            ->setArguments([$routes, new Reference('context')])
        ;

        $containerBuilder->register('url_generator', Routing\Generator\UrlGenerator::class)
            ->setArguments([$routes, new Reference('context')])
        ;

        $containerBuilder->register('request_stack', \Symfony\Component\HttpFoundation\RequestStack::class);

        //Arguments

        $containerBuilder->register('argument_resolver', HttpKernel\Controller\ArgumentResolver::class);

        //Controllers
        $containerBuilder->register('controller_resolver', HttpKernel\Controller\ContainerControllerResolver::class)
            ->setArguments([$containerBuilder])
        ;

        foreach ($configClass::controllers as $controllerName => $controllerClass) {
            $containerBuilder->register($controllerName, $controllerClass)
                ->setArguments([$containerBuilder])
            ;
        }

        //Repositories

        $containerBuilder->register('repository_manager', $configClass::repositoryManager["manager"])
            ->setArguments([
                $configClass::repositoryManager["dataSourceParameters"],
            ])
            ->addMethodCall('registerRepositories', [$configClass::repositories])
        ;

        //Twig

        $containerBuilder->register('twig_app_framework_extension',TwigAppFrameworkExtension::class)
            ->setArguments([
                new Reference('context'),
                new Reference('url_generator'),
                "%debug%"
            ])
        ;

        $twigLoader = new FilesystemLoader($viewsFolder);
        $twig = $containerBuilder->register('twig', \Twig\Environment::class)
            ->setArguments([
                $twigLoader,
                ['autoescape' => 'html']
            ])
            ->addMethodCall('addExtension', [new Reference('twig_app_framework_extension')])
        ;

        $twig->addMethodCall('addGlobal', ['debug', $configClass::debug]);

        //Session manager
        $sessionManager = $containerBuilder->register('session_manager',$configClass::userSessionManager["manager"]);
        $sessionManager->setArguments($configClass::userSessionManager["parameters"]);

        //Events

        $containerBuilder->register('framework_listener',FrameworkListener::class)
            ->setArguments([
                new Reference('twig'),
                new Reference('session_manager'),
            ])
        ;

        $eventDispatcherReference = $containerBuilder->register('event_dispatcher', \Symfony\Component\EventDispatcher\EventDispatcher::class)
            ->addMethodCall('addSubscriber', [new Reference('framework_listener')])
        ;

        foreach ($configClass::listeners as $listener) {
            $eventDispatcherReference->addMethodCall('addSubscriber', [new Reference($listener)]);
        }

        //Services
        $configClass::services($containerBuilder);

        //Framework

        $containerBuilder->register('framework', AppFramework::class)
            ->setArguments([
                new Reference('matcher'),
                new Reference('controller_resolver'),
                new Reference('argument_resolver'),
                new Reference('event_dispatcher'),
            ])
        ;
        return $containerBuilder;
    }
}