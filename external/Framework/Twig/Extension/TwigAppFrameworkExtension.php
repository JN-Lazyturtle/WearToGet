<?php

namespace Framework\Twig\Extension;

use Framework\Services\ServerSessionManager;
use Framework\Services\UserSessionManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigAppFrameworkExtension extends AbstractExtension
{
    private $context;

    private $generator;

    private $debug;

    public function __construct(RequestContext $context, UrlGeneratorInterface $generator, bool $debug)
    {
        $this->context = $context;
        $this->generator = $generator;
        $this->debug = $debug;
    }

    public function getBaseUrl() {
        return $this->context->getBaseUrl();
    }

    public function asset($path) {
        if($this->debug) {
            return $this->getBaseUrl().'/../assets/'.$path;
        }
        else {
            return $this->getBaseUrl().'/assets/'.$path;
        }
    }

    public function url($path) {
        return $this->getBaseUrl() . 'TwigAppFrameworkExtension.php/' .$path;
    }

    public function route($routeName, $args = []) {
        return $this->generator->generate($routeName, $args);
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('asset', [$this, 'asset']),
            new TwigFunction('url', [$this, 'url']),
            new TwigFunction('route', [$this, 'route']),
            new TwigFunction('getBaseUrl', [$this, 'getBaseUrl']),
        ];
    }

}