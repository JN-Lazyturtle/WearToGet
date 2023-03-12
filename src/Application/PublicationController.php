<?php

namespace TheFeed\Application;

use Framework\Application\Controller;
use Symfony\Component\HttpFoundation\Request;
use TheFeed\Business\Exception\ServiceException;

class PublicationController extends Controller
{

    public function feed() {
        $service = $this->container->get('publication_service');
        $publications = $service->getAllPublications();
        return $this->render('Publications/home.html.twig', ["publications" => $publications]);
    }

    public function submitFeedy(Request $request) {
        $userService = $this->container->get('utilisateur_service');
            $userId = $userService->getUserId();
            $message = $request->get('message');
            $service = $this->container->get('publication_service');
            try {
                $service->createNewPublication($userId, $message);
            } catch (ServiceException $e) {
                $this->addFlash('error', $e->getMessage());
            }
            return $this->redirectToRoute('feed');
    }
}