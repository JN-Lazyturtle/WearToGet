<?php

namespace TheFeed\Application\API;

use Framework\Application\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use TheFeed\Business\Exception\ServiceException;

class UtilisateurControllerAPI extends Controller
{

    public function removeUtilisateur($idUser) {
        $service = $this->container->get("utilisateur_service");
        try {
            $service->removeUtilisateur($idUser);
            $service->deconnexion();
            return new JsonResponse(["result" => true]);
        } catch (ServiceException $exception) {
            return new JsonResponse(["result" => false, "error" => $exception->getMessage()], 400);
        }
    }

}