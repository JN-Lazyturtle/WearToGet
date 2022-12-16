<?php

namespace TheFeed\Application\API;

use Framework\Application\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use TheFeed\Business\Exception\ServiceException;

class PublicationControllerAPI extends Controller
{
    public function submitFeedy(Request $request) {
        if($content = $request->getContent()) {
            $json = json_decode($content, true);
            $message = $json["message"] ?? null;
            $userService = $this->container->get('utilisateur_service');
            $userId = $userService->getUserId();
            $service = $this->container->get('publication_service');
            try {
                $publication = $service->createNewPublication($userId, $message);
                return new JsonResponse($publication);
            } catch (ServiceException $e) {
                return new JsonResponse(["error" => $e->getMessage()], 400);
            }
        }
        return new JsonResponse(["error" => "No payload"], 400);
    }

    public function removeFeedy($idPublication) {
        $userService = $this->container->get('utilisateur_service');
        $userId = $userService->getUserId();
        $service = $this->container->get('publication_service');
        try {
            $service->removePublication($userId, $idPublication);
            return new JsonResponse(["result" => true]);
        } catch (ServiceException $exception) {
            return new JsonResponse(["result" => false, "error" => $exception->getMessage()], 400);
        }
    }
}