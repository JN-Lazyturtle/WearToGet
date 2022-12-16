<?php

namespace Framework\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;

interface UserSessionManager
{
    public function updateSessionFromRequest(Request $request);

    public function updateResponse(Response $response);

    public function get($key);

    public function set($key, $value);

    public function remove($key);

    public function has($key);

    public function getFlashBag(): AttributeBag;

    public function addFlash($type, $message);

    public function consumeFlashes($type);
}