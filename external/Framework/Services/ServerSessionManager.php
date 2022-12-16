<?php

namespace Framework\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;

class ServerSessionManager implements UserSessionManager
{
    private $environment;

    private AttributeBag $environmentSession;

    /**
     * @param $environment
     */
    public function __construct($environment)
    {
        $this->environment = $environment;
    }

    public function updateSessionFromRequest(Request $request) {
        $session = new Session();
        $session->start();
        if($session->has($this->environment)) {
            $this->environmentSession = $session->get($this->environment);
        }
        else {
            $this->environmentSession = new AttributeBag();
            $session->set($this->environment, $this->environmentSession);
        }
    }

    public function getFlashBag() : AttributeBag {
        if(!$this->environmentSession->has('flashes')) {
            $this->environmentSession->set('flashes', new AttributeBag());
        }
        return $this->environmentSession->get('flashes');
    }

    public function addFlash($type, $message) {
        $bag = $this->getFlashBag();
        $flashes = [];
        if($bag->has($type)) {
            $flashes = $bag->get($type);
        }
        $flashes[] = $message;
        $bag->set($type, $flashes);
    }

    public function consumeFlashes($type) {
        $flashes = [];
        $bag = $this->getFlashBag();
        if($bag->has($type)) {
            $flashes = $bag->get($type);
            $bag->remove($type);
        }
        return $flashes;
    }

    public function get($key)
    {
        return $this->environmentSession->get($key);
    }

    public function set($key, $value)
    {
        $this->environmentSession->set($key, $value);
    }

    public function has($key)
    {
        return $this->environmentSession->has($key);
    }

    public function remove($key)
    {
        $this->environmentSession->remove($key);
    }

    public function updateResponse(Response $response)
    {
        //Do nothing for now
    }
}